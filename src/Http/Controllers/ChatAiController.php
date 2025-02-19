<?php

namespace Salamat\chat_ai\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Salamat\chat_ai\Model\Question;
use Illuminate\Support\Facades\Http;
use Throwable;

class ChatAiController extends Controller
{
    protected $client;


    public function generateText(Request $request)
    {

        $request->validate([
            'message' => 'required',
        ]);

        $userMessage = $request->input('message');

        // Query the database for all questions
        $allQuestions = Question::all();
        $matchingAnswers = [];

        // Iterate through all questions and calculate similarity
        foreach ($allQuestions as $question) {
            $similarity = 0;
            similar_text(strtolower($userMessage), strtolower($question->question), $similarity);

            // Set a threshold for similarity (adjust as needed) from the configration file
            $threshold = config('chat.threshold', 70); // 70% similarity

            if ($similarity >= $threshold) {
                $matchingAnswers[] = $question;
            }
        }

        if (count($matchingAnswers) > 0) {
            return response()->json(['answers' => $matchingAnswers]);
        } else {
            // get the answer from the chatpgt fucntion below
            $chatPgtResponse = $this->getAiResponse($userMessage);
            if (isset($chatPgtResponse['error']['code']) && $chatPgtResponse['error']['code'] === 'insufficient_quota') {
                // get the gimini answer
                $giminiResponse = $this->getGeminiResponse($userMessage);
                if (isset($giminiResponse['candidates'][0]['content']['parts'][0]['text'])) {
                    $answer = $giminiResponse['candidates'][0]['content']['parts'][0]['text'];
                    $formattedAnswer = Str::markdown($answer);

                    // add the data to our database to train data from google
                    $train_data  = new Question();
                    $train_data->question =  $userMessage;
                    $train_data->answer = $formattedAnswer;
                    $train_data->refrence = 'gemini';
                    $train_data->save();
                    $sentData = [
                        [
                            'status' => 'success',
                            'question' => $userMessage,
                            'answer' => $formattedAnswer,
                            'refrence' => 'gemini',
                        ]
                    ];

                    return response()->json(['answers' => $sentData]);
                } else {
                    $google_searchResponse = $this->searchGoogle($userMessage);

                    if ($google_searchResponse['items'] > 0) {
                        if (!empty($google_searchResponse['spelling'])) {
                            $title = $google_searchResponse['spelling']['correctedQuery'];
                        } else {
                            $title = $google_searchResponse['items'][0]['title'];
                        }
                        $snippet = $google_searchResponse['items'][0]['snippet'];
                        $url = $google_searchResponse['items'][0]['link'];
                        $refrence = $google_searchResponse['items'][0]['kind'];
                        // add the data to our database to train data from google
                        $train_data  = new Question();
                        $train_data->question =  $title;
                        $train_data->answer = $snippet;
                        $train_data->refrence = $refrence;
                        $train_data->moreinfo = json_encode(
                            [
                                "url" => $url,
                            ]
                        );

                        $train_data->save();
                    }
                    $sentData = [
                        [
                            'status' => 'success',
                            'question' => $title,
                            'answer' => $snippet,
                            'refrence' => $refrence,
                            "moreinfo" => [
                                "url" => $url,
                            ]
                        ]
                    ];
                    return response()->json(['answers' => $sentData]);
                }
            } else {
                // Check if response has the expected data
                if (isset($responseData['choices'][0]['message']['content'])) {
                    $formattedAnswer = Str::markdown($responseData['choices'][0]['message']['content']);

                    // add the data to our database to train data from ChatGPT
                    $train_data  = new Question();
                    $train_data->question =  $userMessage;
                    $train_data->answer = $formattedAnswer;
                    $train_data->refrence = 'ChatGPT';
                    $train_data->save();
                    $sentData = [
                        [
                            'status' => 'success',
                            'question' => $userMessage,
                            'answer' => $formattedAnswer,
                            'refrence' => 'ChatGPT',
                        ]
                    ];
                }
                return "Sorry, something went wrong with ChatGPT's response.";
            }
            $defaultResponse = "Sorry, your request is not there. Please try something else.";
            return response()->json(['answers' => [$defaultResponse]]);
        }
    }

    function getAiResponse($content)
    {
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('CHATGPT_API_KEY')
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

            return $responseData;
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

    function searchGoogle($query)
    {
        $apiKey = env('GOOGLE_SEARCH_API_KEY');
        $cx = env('GOOGLE_SEARCH_CX');
        $url = "https://www.googleapis.com/customsearch/v1?q={$query}&key={$apiKey}&cx={$cx}";

        $response = Http::get($url);
        return $response->json();
    }

    function getGeminiResponse($data)
    {
        $apiKey = env('GEMINI_API_KEY');

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
