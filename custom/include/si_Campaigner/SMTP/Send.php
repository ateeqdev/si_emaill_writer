<?php


namespace si_Campaigner\SMTP;

use si_Campaigner\Sugar\Helpers\DBHelper;
use si_Campaigner\apiCalls\MailApiAdapter;

/**
 * This class is responsible for getting data from Google
 */
class Send
{
    /**
     * This function sends an email
     * @param array $emailData email data payload
     * @return array $res response from the API
     */
    public static function run($module = 'Leads')
    {
        $outboundIds = DBHelper::select('outbound_email', ['id', 'assigned_user_id'], ['deleted' => ['=', 0], 'mail_smtppass' => ['!=', ''], 'mail_smtppass' => ['!=', null]]);

        foreach ($outboundIds as $key => $outbound) {

            $beanIds = DBHelper::select(strtolower($module), ['id'], ['si_email_body' => ['!=', null], 'si_email_body' => ['!=', ''], 'si_email_subject' => ['!=', null], 'si_email_subject' => ['!=', ''], 'assigned_user_id' => ['=', $outbound['assigned_user_id']], 'deleted' => ['=', 0]]);

        }
        return MailApiAdapter::sendEmail($userID, $fromName, $to, $subject, $emailData, $signature);
    }
}
