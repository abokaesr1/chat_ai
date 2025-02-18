<?php

namespace Salamat\chat_ai\Helper;

use Illuminate\Support\Facades\Http;
use Throwable;

class OpenAIHelper
{
    public static function getAiResponse($content)
    {
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHAT_GPT_KEY')
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => 'gpt-4o-mini',
                "store" =>  true,
                "messages" => [
                    [
                        "role" => "user",
                        "content" => $content
                    ],
                ],

            ])->body();

            return $response['choices'][0]['message']['content'];

            // Decode JSON response
            $responseData = json_decode($response->body(), true);

            // Check if response has the expected data
            if (isset($responseData['choices'][0]['message']['content'])) {
                return $responseData['choices'][0]['message']['content'];
            }

            return "Sorry, something went wrong with ChatGPT's response.";
        } catch (Throwable $e) {
            // Custom error message
            return [
                "error" => [
                    "code" => "insufficient_quota",
                    "message" => "An error occurred while fetching the response from ChatGPT."
                ]
            ];
        }
    }

    public static  function searchGoogle($query)
    {
        $apiKey = 'AIzaSyDmZuEdvOGObABhaZAqPeHuZT0iZWy3eRI';
        $cx = 'e17c63701a1a84a85';
        $url = "https://www.googleapis.com/customsearch/v1?q={$query}&key={$apiKey}&cx={$cx}";

        $response = Http::get($url);
        return $response->json();
    }

    public static  function getGeminiResponse($data)
    {
        $apiKey = 'AIzaSyBOpKIGHBNMTm-NvzUPVXkVF9fltiGVPhk';

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        $response = Http::post($url, [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $data]
                    ]
                ]
            ]
        ]);
        return $response->json();
    }
}
