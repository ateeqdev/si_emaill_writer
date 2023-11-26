<?php


namespace si_Campaigner\SMTP;

use si_Campaigner\Sugar\Helpers\DBHelper;
use si_Campaigner\Helpers\HandleSending;

/**
 * This class is responsible for getting data from Google
 */
class Send
{
    /**
     * This function sends an introduction email
     * @param array $emailData email data payload
     * @return array $res response from the API
     */
    public static function firstEmail($module = 'Leads')
    {
        try {
            $outboundIds = DBHelper::select('outbound_email', ['id', 'assigned_user_id'], ['deleted' => ['=', 0], 'mail_smtppass' => ['!=', ''], 'mail_smtppass' => ['!=', null]]);

            foreach ($outboundIds as $key => $outbound) {
                $userId = $outbound['assigned_user_id'];
                $select = DBHelper::executeQuery(
                    "SELECT id
                    FROM leads
                    WHERE (si_conversation_history = '' OR si_conversation_history IS NULL)
                        AND (si_email_body != '' AND si_email_body IS NOT NULL)
                        AND (si_email_subject != '' AND si_email_subject IS NOT NULL)
                        AND assigned_user_id = '" . $userId . "' " .
                        "AND deleted = 0
                    ORDER BY date_modified ASC
                    LIMIT 0, 1;"
                );

                $res2 = $GLOBALS['db']->fetchByAssoc($select);
                HandleSending::send($res2['id'], $module, $userId);
                return true;
            }
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }
}
