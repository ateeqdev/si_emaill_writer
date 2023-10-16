<?php

namespace si_Campaigner\Google;

use si_Campaigner\apiCalls\OAuthApiAdapter;
use si_Campaigner\Sugar\Helpers\UpdateBean;

require_once 'custom/include/si_Campaigner/Helpers/si_Time.php';
require_once 'custom/include/si_Campaigner/lib/GoogleOauthHandler.php';

/**
 * This class is responsible for getting Access Token from Google Client
 */
class AccessToken
{
    /**
     * This function gets the Access Token
     * @param string $id User ID of the logged in User
     * @return string access_token is returned
     */
    public static function getToken($userID)
    {
        $creds = \GoogleOauthHandler::getStoredCredentials($userID);
        if (!$creds)
            return null;

        $currentTime = \si_Time::getCurrent();
        $date = date_create($currentTime);
        $currentTimeinSeconds = date_format($date, "U");
        if ($currentTimeinSeconds > $creds['expires_in']) {
            $newCredentials = OAuthApiAdapter::refreshAccessToken($creds['refresh_token'], $userID);
            UpdateBean::update('users', $newCredentials);
            $creds['access_token'] = $newCredentials[0]['si_google_access_code'];
            $creds['expires_in'] = $newCredentials[0]['si_google_auth_expires_in'];
        }
        return $creds['access_token'];
    }
}
