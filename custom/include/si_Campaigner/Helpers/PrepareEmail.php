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
        $subWhere = ['status' => ['=', 'New'], 'si_linkedin_bio' => ['!=', null], 'si_linkedin_bio' => ['!=', '']];

        // Run the query using the selectWithSubquery method
        $result = DBHelper::selectWithSubquery($mainTable, $mainFields, $mainWhere, $subTable, $subFields, $subWhere);

        foreach ($result as $key => $value) {
            $written = self::writeEmailforRecord($module, $value['id']);
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
    public static function writeEmailforRecord($module, $id)
    {
        $bean = \BeanFactory::getBean($module, $id);

        if (!$bean)
            return 'Record not found';
        $toEmailAddress = $bean->email1 ?? $bean->email2 ?? '';
        if (!$toEmailAddress)
            return 'The record does not have an email address';

        $bean->load_relationship('accounts');
        $relatedAccount = $bean->accounts->get();
        if (!$relatedAccount || count($relatedAccount) < 0)
            return 'No related account found';

        $account = \BeanFactory::getBean('Accounts', $relatedAccount[0]);

        $response = OpenAIApiAdapter::firstEmail($bean->first_name . ' ' . $bean->last_name, $bean->si_linkedin_bio,  $account->description);
        if (isset($response['error']) && $response['error'])
            $response['error'];

        $emailBody = $response['body'] ? nl2br($response['body']) : (isset($response['choices'][0]['message']['content']) ? $response['choices'][0]['message']['content'] : '');
        $emailBody = str_replace('<br />', "\n", $emailBody);
        $emailBody = str_replace('<br >', "\n", $emailBody);
        $emailBody = str_replace('<br>', "\n", $emailBody);
        $bean->si_email_subject = $response['subject'];
        $bean->si_email_body = $emailBody;
        $bean->status = 'ready_for_approval';
        $bean->save();

        return 'true';
    }
}
