<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Attachment;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
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

                $extractedText = $this->openAIService->extractTextFromFile(
                    $file->getRealPath(),
                    $file->getMimeType()
                );

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

        // Add system message
        $messages[] = $this->openAIService->createSystemMessage($attachmentText);

        // Add conversation history (limit to last 10 messages for context)
        $conversationMessages = $conversation->messages()
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        foreach ($conversationMessages as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        return $messages;
    }
}
