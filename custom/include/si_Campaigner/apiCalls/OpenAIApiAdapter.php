<?php

namespace si_Campaigner\apiCalls;

use si_Campaigner\apiCalls\ApiAdapter;

/**
 * This class is responsible for making CURL calls to OpenAI GPT API
 */
class OpenAIApiAdapter extends ApiAdapter
{
    public static $baseURL = 'https://api.openai.com/v1/chat/completions';

    public static function firstEmail($name, $personDesc = '', $companyDesc = '')
    {
        $systemPrompt = 'Write a 4-5 sentence (~80 words) concise, entity-rich, casual but respectful email from Malik to a contact offering ServiceNow development services. The email should begin with a meaningful, tailored compliment about a notable accomplishment achieved by the recipient, then briefly describe a case study relevant to the client role and their company and then offer to discuss potential needs and solutions over a meeting. Maintain a friendly, helpful tone without pressure or hidden obligations. Keep the tone friendly but avoid urgency. The purpose is to start a polite conversation about their potential needs without being pushy. Do not include the signature, I will add the signature myself. The subject cannot be longer than 3 words. Respond with an actual email (no placeholders) in JSON format (use subject and body as keys for the json). Answer in English. Here are some example case studies of my past work. I migrated over 50% of customers to a new portal in a quarter for Teradata GCC, retiring old instances and saving revenue. I integrated ServiceNow with xMatters at Teradata GCC, enabling timely SMS notifications and increasing SLA adherence. I led all reporting and Performance Analytics modules for leadership projects at Teradata GCC, reducing customer churn by 10%. I developed custom applications and workflows that streamlined our processes, saving time and money.';

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
