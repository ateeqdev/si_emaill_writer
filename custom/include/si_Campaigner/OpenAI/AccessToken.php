<?php

namespace si_Campaigner\OpenAI;

use si_Campaigner\Sugar\Helpers\DBHelper;


/**
 * This class is responsible for getting Access Token to be used for OpenAI
 * 
 * It's in the OpenAI folder, not the Sugar folder, because it mirrors the hierarchy of the corresponding Google file, which interacts with Google.
 */
class AccessToken
{
    /**
     * This function gets the Access Token
     * @return string API key is returned
     */
    public static function getToken()
    {
        return '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';
        $creds = DBHelper::select('si_campaigner', 'openai_key', ['id', ['=', 1]]);
        return $creds[0]['openai_key'];
    }
}
