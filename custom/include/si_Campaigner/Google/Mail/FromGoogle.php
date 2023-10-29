<?php

namespace si_Campaigner\Google\Mail;

use si_Campaigner\Sugar\Helpers\DBHelper;
use si_Campaigner\apiCalls\MailApiAdapter;

/**
 * This class is responsible for getting data from Google
 */
class FromGoogle
{
    /**
     * This function gets a list of messages from the authenticated user's mailbox
     * @param string $userID id of the logged in user
     * @return array $res response from the API
     */
    public static function listMessages($userID)
    {
        return MailApiAdapter::listMessages($userID);
    }

    /**
     * This function gets a specific message by its ID
     * @param string $userID id of the logged in user
     * @param string $messageId ID of the message to retrieve
     * @return array $res response from the API
     */
    public static function getMessage($userID, $messageId)
    {
        return MailApiAdapter::getMessage($userID, $messageId);
    }

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
