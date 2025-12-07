<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Attachment;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function index()
    {
        $conversations = auth()->user()->conversations()
            ->with('messages')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'nullable|exists:conversations,id',
                'message' => 'required|string',
                'attachments.*' => 'file|max:10240', // 10MB max
            ]);

            $user = auth()->user();

        // Create or get conversation
        if ($request->conversation_id) {
            $conversation = Conversation::findOrFail($request->conversation_id);
        } else {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title' => 'New Conversation',
            ]);
        }

        // Store user message
        $userMessage = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'role' => 'user',
            'content' => $request->message,
        ]);

        // Handle file attachments
        $attachmentText = '';
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('attachments', $filename);

                Log::info('Processing attachment', [
                    'filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]);

                $extractedText = $this->openAIService->extractTextFromFile(
                    $file->getRealPath(),
                    $file->getMimeType()
                );

                Log::info('Extracted text preview', [
                    'filename' => $file->getClientOriginalName(),
                    'text_length' => strlen($extractedText),
                    'preview' => substr($extractedText, 0, 200)
                ]);

                $attachment = Attachment::create([
                    'message_id' => $userMessage->id,
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'extracted_text' => $extractedText,
                ]);

                $attachmentText .= "\n\nFile: " . $file->getClientOriginalName() . "\n" . $extractedText;
            }
        }

        // Prepare messages for OpenAI
        $messages = $this->prepareMessagesForAI($conversation, $attachmentText);

        // Get AI response
        $aiResponse = $this->openAIService->chat($messages);

        if ($aiResponse && isset($aiResponse['choices'][0]['message']['content'])) {
            $assistantMessage = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'role' => 'assistant',
                'content' => $aiResponse['choices'][0]['message']['content'],
                'metadata' => [
                    'model' => $aiResponse['model'] ?? null,
                    'tokens' => $aiResponse['usage']['total_tokens'] ?? null,
                ],
            ]);

            // Generate conversation title if it's the first message
            if ($conversation->messages()->count() <= 2 && $conversation->title === 'New Conversation') {
                $conversation->generateTitle();
            }

            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'user_message' => $userMessage->load('attachments'),
                'assistant_message' => $assistantMessage,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to get AI response. Please try again.',
        ], 500);
        } catch (\Exception $e) {
            Log::error('Chat store error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $conversation->load('messages.attachments');

        return response()->json($conversation);
    }

    public function destroy(Conversation $conversation)
    {
        $this->authorize('delete', $conversation);

        $conversation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation deleted successfully',
        ]);
    }

    protected function prepareMessagesForAI(Conversation $conversation, $attachmentText = '')
    {
        $messages = [];

        // Add system message without attachment (we'll add attachment to user message)
        $messages[] = $this->openAIService->createSystemMessage();

        // Add conversation history (limit to last 10 messages for context)
        // Exclude the very last message since we'll add it with attachment text
        $conversationMessages = $conversation->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Get all messages except the last one (current message)
        $historyMessages = $conversationMessages->slice(0, -1)->take(18);

        foreach ($historyMessages as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        // Add the current message with attachment text
        if ($conversationMessages->count() > 0) {
            $lastMessage = $conversationMessages->last();
            $content = $lastMessage->content;
            
            // If there's attachment text, append it to the user's message
            if (!empty($attachmentText)) {
                $content .= "\n\n" . $attachmentText;
                Log::info('Adding attachment to message', [
                    'attachment_length' => strlen($attachmentText),
                    'has_pdf_marker' => strpos($attachmentText, '[PDF Content Extracted]') !== false
                ]);
            }
            
            $messages[] = [
                'role' => $lastMessage->role,
                'content' => $content,
            ];
        }

        Log::info('Prepared messages for AI', [
            'total_messages' => count($messages),
            'last_message_length' => isset($content) ? strlen($content) : 0
        ]);

        return $messages;
    }
}
