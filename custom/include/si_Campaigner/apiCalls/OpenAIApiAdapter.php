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
        $prompt = DBHelper::select('si_Campaigner', ['description', 'large_language_model'], [
            'assigned_user_id' => ['=', $userId],
            'deleted' => ['=', '0']
        ], 'date_modified');
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

        if (strpos($message, '```json') === 0 && substr($message, -3) === '```')
            $message = substr($message, 7, -3);

        $message = json_decode(json_decode(json_encode($message), 1), 1);
        return $message ? $message : $response;
    }

    public static function followupEmail($conversation, $name, $personDesc = '', $companyDesc = '', $userId = '1')
    {
        $prompt = DBHelper::select('si_Campaigner', ['followup_prompt', 'large_language_model'], [
            'assigned_user_id' => ['=', $userId],
            'deleted' => ['=', '0']
        ], 'date_modified');
        $systemPrompt = $prompt[0]['followup_prompt'];
        if (!$systemPrompt) {
            return ['error' => 'prompt not found'];
        }
        $userPrompt = 'Conversation history: ' . $conversation . '\n';
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
        $message = ltrim($message, '```json');
        $message = trim($message, '```');
        $message = trim($message);

        $message = json_decode(json_decode(json_encode($message), 1), 1);
        return $message ? $message : $response;
    }
}
