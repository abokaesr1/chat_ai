<?php

namespace Salamat\chat_ai\App\Http\Controllers\ChatAI;

use App\Http\Controllers\Controller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Salamat\chat_ai\App\Models\Question;
use Salamat\chat_ai\App\Http\Helper\OpenAIHelper;

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

            // Set a threshold for similarity (adjust as needed)
            $threshold = 30; // 60% similarity

            if ($similarity >= $threshold) {
                $matchingAnswers[] = $question;
            }
        }

        if (count($matchingAnswers) > 0) {
            return response()->json(['answers' => $matchingAnswers]);
        } else {
            // get the answer from the chatpgt fucntion below
            $chatPgtResponse = OpenAIHelper::getAiResponse($userMessage);
            if (isset($chatPgtResponse['error']['code']) && $chatPgtResponse['error']['code'] === 'insufficient_quota') {
                // get the gimini answer
                $giminiResponse = OpenAIHelper::getGeminiResponse($userMessage);
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
                            'answer' => $answer,
                            'reference' => 'gemini',
                        ]
                    ];

                    return response()->json(['answers' => $formattedAnswer]);
                } else {

                    $google_searchResponse = OpenAIHelper::searchGoogle($userMessage);

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
                dd($chatPgtResponse);
            }
            $defaultResponse = "Sorry, your request is not there. Please try something else.";
            return response()->json(['answers' => [$defaultResponse]]);
        }
    }
}
