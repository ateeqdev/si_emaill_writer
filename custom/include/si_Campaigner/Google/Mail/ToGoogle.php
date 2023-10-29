<?php

namespace si_Campaigner\Google\Mail;

use si_Campaigner\Sugar\Helpers\DBHelper;
use si_Campaigner\apiCalls\MailApiAdapter;

/**
 * This class is responsible for getting data from Google
 */
class ToGoogle
{
    /**
     * This function sends an email
     * @param string $userID id of the logged in user
     * @param array $emailData email data payload
     * @return array $res response from the API
     */
    public static function sendEmail($userID, $emailData)
    {
        DBHelper::select('users', ['si_gmail_id', 'first_name', 'last_name'], ['id' => ['=', $userID]]);
        return MailApiAdapter::sendEmail($userID, $emailData);
    }
}
