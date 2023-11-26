<?php

namespace si_Campaigner\SMTP;

use si_Campaigner\Sugar\Helpers\DBHelper;
use si_Campaigner\Sugar\Helpers\UpdateJob;
use si_Campaigner\Helpers\HandleSending;

/**
 * Class Send
 *
 * This class is responsible for sending emails based on specified criteria.
 */
class Send
{
    /**
     * Sends an email based on the given criteria.
     *
     * @param string $module The target module.
     * @param string $type The type of email (default is 'first').
     * @return boolean Returns true if the job was successful, false otherwise.
     */
    private static function sendEmail($module, $type = 'first')
    {
        try {
            // Define the job based on the email type
            $job = $type == 'first' ? 'function::si_sendFirstEmail' : 'function::si_sendFollowupEmail';

            // Check if the current time is working hours of a weekday
            if (!self::shouldRun($job)) {
                return true;
            }

            // Define criteria based on email type
            $history_criteria = $type == "first" ? "(si_conversation_history = '' OR si_conversation_history IS NULL) AND status = 'approved'" : "(si_conversation_history != '' AND si_conversation_history IS NOT NULL) AND status = 'followup_approved'";

            // Retrieve outbound email ids with valid SMTP credentials
            $outboundIds = DBHelper::select('outbound_email', ['id', 'assigned_user_id'], [
                'deleted' => ['=', 0],
                'mail_smtppass' => ['!=', '']
            ]);

            foreach ($outboundIds as $key => $outbound) {
                // Add a random chance of 10%
                $randomNumber = rand(1, 100);
                if ($randomNumber <= 10) {
                    continue;
                }

                $userId = $outbound['assigned_user_id'];

                // Fetch a lead meeting criteria for sending emails
                $select = DBHelper::executeQuery(
                    "SELECT id
                    FROM leads
                    WHERE $history_criteria
                        AND (si_email_body != '' AND si_email_body IS NOT NULL)
                        AND (si_email_subject != '' AND si_email_subject IS NOT NULL)
                        AND assigned_user_id = '" . $userId . "' " .
                        "AND deleted = 0
                    ORDER BY date_modified ASC
                    LIMIT 0, 1;"
                );

                $res2 = $GLOBALS['db']->fetchByAssoc($select);

                // Send the email using HandleSending helper
                HandleSending::send($res2['id'], $module, $userId);

                return true;
            }
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sends an introduction email.
     *
     * @param string $module The target module (default is 'Leads').
     * @return boolean Returns true if the job was successful, false otherwise.
     */
    public static function firstEmail($module = 'Leads')
    {
        return self::sendEmail($module, 'first');
    }

    /**
     * Sends a follow-up email.
     *
     * @param string $module The target module (default is 'Leads').
     * @return boolean Returns true if the job was successful, false otherwise.
     */
    public static function followupEmail($module = 'Leads')
    {
        return self::sendEmail($module, 'followup');
    }

    /**
     * Checks if the job should be executed or not based on day, time, and a random function.
     *
     * @param string $job The function to be executed.
     * @return boolean Returns true if the job should run, false otherwise.
     */
    public static function shouldRun($job)
    {
        try {
            // Get current time in PST
            $currentTime = new \DateTime('now', new \DateTimeZone('PST'));

            // Check if it's a weekend (Saturday or Sunday)
            $isWeekend = in_array($currentTime->format('N'), [6, 7]);

            // Check if the current time is outside 7am - 7pm PST
            $isOutsideWorkingHours = $currentTime->format('H') < 7 || $currentTime->format('H') >= 19;

            // If it's a weekend or outside working hours, set scheduler job to run after one hour
            if ($isWeekend || $isOutsideWorkingHours) {
                UpdateJob::setInterval($job, '0::7::*::*::*');
                return false;
            }

            // Set the scheduler job to run every minute during the week within working hours
            UpdateJob::setInterval($job, '*::*::*::*::*');

            return true;
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }
}
