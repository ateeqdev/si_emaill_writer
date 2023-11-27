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
    public static function getToken($userId = 1)
    {
        $creds = DBHelper::select('si_campaigner', 'api_key', ['id', ['=', $userId]]);
        return $creds[0]['api_key'];
    }
}
