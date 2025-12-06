<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $apiKey;
    protected $model;
    protected $maxTokens;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->model = config('services.openai.model', 'gpt-4-turbo-preview');
        $this->maxTokens = config('services.openai.max_tokens', 2000);
    }

    public function chat(array $messages, $stream = false)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => 0.7,
                'stream' => $stream,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenAI Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    public function streamChat(array $messages)
    {
        return $this->chat($messages, true);
    }

    public function extractTextFromFile($filePath, $mimeType)
    {
        // For text files, read directly
        if (str_starts_with($mimeType, 'text/')) {
            return file_get_contents($filePath);
        }

        // For PDFs, use a summary prompt
        if ($mimeType === 'application/pdf') {
            return "PDF file attached. Content extraction requires additional processing.";
        }

        // For images, return description
        if (str_starts_with($mimeType, 'image/')) {
            return "Image file attached.";
        }

        return "File attached: " . basename($filePath);
    }

    public function createSystemMessage($attachmentText = null)
    {
        $systemPrompt = "You are PetrogenAI, a helpful AI assistant for Petrogen company employees. ";
        $systemPrompt .= "You help employees with their questions, provide information, and assist with various tasks. ";
        $systemPrompt .= "Be professional, helpful, and concise in your responses.";

        if ($attachmentText) {
            $systemPrompt .= "\n\nThe user has attached a file with the following content:\n\n" . $attachmentText;
        }

        return [
            'role' => 'system',
            'content' => $systemPrompt
        ];
    }
}
