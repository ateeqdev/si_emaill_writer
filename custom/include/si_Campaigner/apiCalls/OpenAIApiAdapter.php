<?php

namespace si_Campaigner\apiCalls;

use si_Campaigner\apiCalls\ApiAdapter;
use si_Campaigner\Sugar\Helpers\DBHelper;

/**
 * This class is responsible for making CURL calls to OpenAI GPT API
 */
class OpenAIApiAdapter extends ApiAdapter
{
    public static $baseURL = 'https://api.openai.com/v1/chat/completions';

    public static function firstEmail($name, $personDesc = '', $companyDesc = '', $userId = '')
    {
        $prompt = DBHelper::select('si_Campaigner', ['description', 'large_language_model'], ['assigned_user_id' => ['=', $userId]]);
        $systemPrompt = $prompt[0]['description'];
        if (!$systemPrompt) {
            return ['error' => 'prompt not found'];
        }
        $userPrompt = 'Name of the person: ' . $name . '\n';
        $userPrompt .= $personDesc ? 'Bio of the person: ' . $personDesc . '\n' : '';
        $userPrompt .= $companyDesc ? 'The rest of the information is about the person\'s company\n' . $companyDesc . '\n' : '';
        $body = [
            "model" => $prompt[0]['large_language_model'] ? $prompt[0]['large_language_model'] : 'gpt-3.5-turbo',
            "messages" => [
                [
                    "role" => "system",
                    "content" => $systemPrompt
                ],
                [
                    "role" => "user",
                    "content" => $userPrompt
                ]
            ]
        ];
        $response = ApiAdapter::call(
            'POST',
            self::$baseURL,
            false,
            '',
            $body,
            'openai'
        );
        $message = isset($response['choices'][0]['message']['content']) ? $response['choices'][0]['message']['content'] : '';
        $message = json_decode(json_decode(json_encode($message), 1), 1);
        return $message ? $message : $response;
    }
    public static function followupEmail($conversation, $name, $personDesc = '', $companyDesc = '')
    {
        $systemPrompt = 'Write a 1-2 sentence (~20 words) concise, entity-rich, casual but respectful followup email based on the conversation history. Keep the tone friendly but avoid being pushy. The purpose is to get them on a call by either reminding them of the offer or to send a new case study (do not repeat the one already sent). Do not include the signature, I will add the signature myself. Respond with an actual followup email body (no placeholders). Answer in English. Here are some example case studies of my past work. I migrated over 50% of customers to a new portal in a quarter for Teradata GCC, retiring old instances and saving revenue. I integrated ServiceNow with xMatters at Teradata GCC, enabling timely SMS notifications and increasing SLA adherence. I led all reporting and Performance Analytics modules for leadership projects at Teradata GCC, reducing customer churn by 10%. I developed custom applications and workflows that streamlined our processes, saving time and money.';

        $userPrompt = 'Conversation history: ' . $conversation . '\n';
        $userPrompt = 'Name of the person: ' . $name . '\n';
        $userPrompt .= $personDesc ? 'Bio of the person: ' . $personDesc . '\n' : '';
        $userPrompt .= $companyDesc ? 'The rest of the information is about the person\'s company\n' . $companyDesc . '\n' : '';
        $body = [
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" => $systemPrompt
                ],
                [
                    "role" => "user",
                    "content" => $userPrompt
                ]
            ]
        ];
        $response = ApiAdapter::call(
            'POST',
            self::$baseURL,
            false,
            '',
            $body,
            'openai'
        );
        $message = isset($response['choices'][0]['message']['content']) ? $response['choices'][0]['message']['content'] : '';
        $message = json_decode(json_decode(json_encode($message), 1), 1);
        return $message ? $message : $response;
    }
}
