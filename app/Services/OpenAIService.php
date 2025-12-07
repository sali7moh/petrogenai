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
        $this->maxTokens = (int) config('services.openai.max_tokens', 2000);
    }

    public function chat(array $messages, $stream = false)
    {
        try {
            // Clean all messages to ensure valid UTF-8
            $cleanMessages = array_map(function($message) {
                return [
                    'role' => $message['role'],
                    'content' => $this->cleanUtf8($message['content'])
                ];
            }, $messages);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $cleanMessages,
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
        try {
            // For text files, read directly
            if (str_starts_with($mimeType, 'text/')) {
                $content = file_get_contents($filePath);
                return mb_convert_encoding($content, 'UTF-8', 'UTF-8');
            }

            // For PDFs, extract text using PDF parser
            if ($mimeType === 'application/pdf') {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($filePath);
                    $text = $pdf->getText();
                    
                    if (empty(trim($text))) {
                        return "[PDF file attached - Unable to extract text. May be image-based or encrypted]";
                    }
                    
                    // Clean and ensure valid UTF-8
                    $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
                    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
                    
                    // Format the extracted text
                    return "[PDF Content Extracted]:\n" . trim($text);
                } catch (\Exception $e) {
                    return "[PDF file attached - Error extracting text: " . $e->getMessage() . "]";
                }
            }

            // For images, return description
            if (str_starts_with($mimeType, 'image/')) {
                return "[Image file attached]";
            }

            return "[File attached: " . basename($filePath) . "]";
        } catch (\Exception $e) {
            return "[File attached - Error reading: " . $e->getMessage() . "]";
        }
    }

    public function createSystemMessage()
    {
        $systemPrompt = "You are PetrogenAI, a helpful AI assistant for Petrogen company employees. ";
        $systemPrompt .= "You help employees with their questions, provide information, and assist with various tasks. ";
        $systemPrompt .= "Be professional, helpful, and concise in your responses. ";
        $systemPrompt .= "When users attach files (like PDFs), analyze the content and answer their questions about it. ";
        $systemPrompt .= "Always reference specific information from attached files when answering questions about them.";

        return [
            'role' => 'system',
            'content' => $this->cleanUtf8($systemPrompt)
        ];
    }

    /**
     * Clean and ensure valid UTF-8 encoding
     */
    private function cleanUtf8($text)
    {
        if (empty($text)) {
            return $text;
        }
        
        // Convert to UTF-8 and remove invalid sequences
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        // Remove control characters except newlines and tabs
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text);
        
        return $text;
    }
}
