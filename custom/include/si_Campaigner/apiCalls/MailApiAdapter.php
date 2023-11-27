<?php

namespace si_Campaigner\apiCalls;

use si_Campaigner\apiCalls\ApiAdapter;

include_once('include/SugarPHPMailer.php');

/**
 * This class is responsible for making CURL calls to Google Mail API
 */
class MailApiAdapter
{
    /**
     * This function gets a list of messages from the authenticated user's mailbox
     * @param string $userID id of the logged in user
     * @return string $response
     */
    public static function listMessages($userID)
    {
        $url = self::makeRequestURL('listMessages');
        return ApiAdapter::call('GET', $url, false, $userID);
    }

    /**
     * This function gets a specific message by its ID
     * @param string $userID id of the logged in user
     * @param string $messageId ID of the message to retrieve
     * @return string $response
     */
    public static function getMessage($userID, $messageId)
    {
        $url = self::makeRequestURL('getMessage', ['messageId' => $messageId]);
        return ApiAdapter::call('GET', $url, false, $userID);
    }

    /**
     * This function sends an email using SugarPHPMailer
     * @param string $from email address of the sender
     * @param string $fromName name of the sender
     * @param string $to email address of the recipient
     * @param string $subject email subject
     * @param string $message email body in HTML or plain text
     * @param string $signature email signature
     * @return string $response
     */
    public static function sendEmail($to, $toName, $subject, $message, $messageId = null, $oe_id = null)
    {
        $message = nl2br(trim($message));


        $mail = new \SugarPHPMailer(true);
        if ($oe_id) {
            $mailoe = \BeanFactory::getBean(ucfirst('OutboundEmailAccounts'), $oe_id, array('disable_row_level_security' => true));
        } else {
            $mailoe = new \OutboundEmail();
            $mailoe->getUserMailerSettings($current_user);
        }
        $signature = $mailoe->signature;
        if (!empty($signature)) {
            $message .= "<br><br><div style='color: #888;'>$signature</div>";
        }
        $mail->ClearAllRecipients();
        $mail->ClearReplyTos();
        $mail->AddAddress($to, $toName);
        $mail->From = $mailoe->smtp_from_addr;
        $mail->FromName = $mailoe->smtp_from_name;
        $mail->Subject = $subject;
        $mail->Body_html = from_html($message);
        $mail->Body = wordwrap($message, 900);
        if ($messageId && !empty($messageId)) {
            $mail->addCustomHeader('In-Reply-To',  $messageId);
            $mail->addCustomHeader('References',  $messageId);
        }
        $mail->isHTML(true);
        $mail->prepForOutbound();
        $mail->setMailerForSystem();
        $response = $mail->send();

        if (!$response) {
            $GLOBALS['log']->fatal("si_Campaigner ERROR: Mail sending failed!", print_r($response, 1));
            return ['error' => "si_Campaigner ERROR: Mail sending failed!"];
        }
        return ['message_id' => $mail->getLastMessageID()];
    }
}
