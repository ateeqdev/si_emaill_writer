<?php

namespace si_Campaigner\Helpers;

use si_Campaigner\Sugar\Helpers\DBHelper;
use si_Campaigner\apiCalls\OpenAIApiAdapter;


/**
 * Class PrepareEmail
 *
 * This class provides functionality for preparing email content.
 */
class PrepareEmail
{
    /**
     * Run the email preparation process.
     *
     * @param string $module             The module to process (default: 'Leads').
     * @param bool   $requireCompleteData Whether to require complete data (default: true).
     */
    public static function firstEmail($module = 'Leads', $requireCompleteData = true)
    {
        $startTime = strtotime('now');
        $mainTable = 'RankedLeads'; //temporary table generated via subquery
        $mainFields = 'id';
        $mainWhere = ['row_num' => ['=', 1]];

        // Define the parameters for the subquery
        $subTable = 'leads';
        $subFields = ['id', 'account_id', 'ROW_NUMBER() OVER (PARTITION BY account_id ORDER BY date_modified) AS row_num'];
        $subWhere = ['status' => ['=', 'ready_for_email'], 'si_linkedin_bio' => ['!=', null], 'si_linkedin_bio' => ['!=', '']];

        // Run the query using the selectWithSubquery method
        $result = DBHelper::selectWithSubquery($mainTable, $mainFields, $mainWhere, $subTable, $subFields, $subWhere);

        foreach ($result as $key => $value) {
            $written = self::writeEmail($module, $value['id'], 'first');
            $GLOBALS['log']->fatal($written);
            if ((strtotime('now') - $startTime) > 60) {
                break; // Exit the loop if more than a minute has passed
            }
        }
        return true;
    }

    /**
     * Prepare followup emails.
     *
     * @param string $module The module to process (default: 'Leads').
     */
    public static function wrieFollowups($module = 'Leads')
    {
        $startTime = strtotime('now');
        $currentTimestampUTC = strtotime(gmdate('Y-m-d H:i:s'));
        $fourDaysAgoUTC = date('Y-m-d H:i:s', strtotime('-4 days', $currentTimestampUTC));

        $result = DBHelper::select(
            strtolower($module),
            'id, si_conversation_history',
            [
                'si_conversation_history' => ['!=', ''],
                'status' => ['!=', 'reply_received'],
                'si_emailed_at' => ['<', $fourDaysAgoUTC]
            ],
            'si_emailed_at DESC'
        );

        foreach ($result as $key => $value) {
            $written = self::writeEmail($module, $value['id'], 'followup');
            $GLOBALS['log']->fatal($written);
            if ((strtotime('now') - $startTime) > 60) {
                break; // Exit the loop if more than a minute has passed
            }
        }
        return true;
    }

    /**
     * Write an email for a specific record.
     *
     * @param string $module The module of the record.
     * @param int    $id     The ID of the record.
     *
     * @return string 'true' or the reason why it failed.
     */
    public static function writeEmail($module, $id, $emailType = '')
    {
        $bean = \BeanFactory::getBean($module, $id, array('disable_row_level_security' => true));

        if (!$bean)
            return 'Record not found';

        $toEmailAddress = $bean->email1 ?? $bean->email2 ?? '';
        if (!$toEmailAddress)
            return 'The record does not have an email address';

        if ($bean->si_conversation_history)
            $emailType = 'followup';
        else
            $emailType = 'first';

        $bean->load_relationship('accounts');
        $relatedAccount = $bean->accounts->get();
        if (!$relatedAccount || count($relatedAccount) < 0)
            return 'No related account found';

        $account = \BeanFactory::getBean('Accounts', $relatedAccount[0], array('disable_row_level_security' => true));

        // Select the appropriate email sending method based on the email type
        switch ($emailType) {
            case 'followup':
                $response = OpenAIApiAdapter::followupEmail($bean->si_conversation_history, $bean->first_name . ' ' . $bean->last_name, $bean->si_linkedin_bio, $account->description);
                break;

            case 'first':
                $response = OpenAIApiAdapter::firstEmail($bean->first_name . ' ' . $bean->last_name, $bean->si_linkedin_bio, $account->description, $bean->assigned_user_id);
                break;

            default:
                return 'Invalid email type';
        }

        if (isset($response['error']) && $response['error'])
            return $response['error'];


        $emailBody = $response['body'] ? nl2br($response['body']) : (isset($response['choices'][0]['message']['content']) ? $response['choices'][0]['message']['content'] : '');
        $emailBody = str_replace('<br />', "\n", $emailBody);
        $emailBody = str_replace('<br >', "\n", $emailBody);
        $emailBody = str_replace('<br>', "\n", $emailBody);

        if (isset($response['subject'])) {
            $bean->si_email_subject = $response['subject'];
        }
        $bean->si_email_body = $emailBody;
        $bean->status = $emailType == 'first' ? 'ready_for_approval' : 'followup_written';
        $bean->save();

        return 'true';
    }
}
