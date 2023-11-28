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
            $outbounds = DBHelper::select('outbound_email', ['id', 'user_id'], [
                'type' => ['=', 'user'],
                'mail_smtppass' => ['IS NOT', 'null'],
                'deleted' => ['=', '0']
            ], 'date_modified');

            $configRes = DBHelper::select('si_Campaigner', ['timezone', 'require_approval', 'campaign_days', 'email_frequency', 'start_time', 'end_time', 'assigned_user_id'], [
                'deleted' => ['=', '0']
            ], 'date_modified');

            $configs = [];
            foreach ($outbounds as $outboundVal) {
                foreach ($configRes as $configVal) {
                    if ($configVal['assigned_user_id'] == $outboundVal['user_id'] && !$configs[$outboundVal['user_id']]) {
                        $configs[$outboundVal['user_id']] = $configVal;
                    }
                }
            }
            foreach ($configs as $key => $value) {
                if ($key == 'start_time' || $key == 'end_time') {
                    $configs[$key] = str_replace(':', '', $configs[$key]);
                } else if ($key == 'campaign_days') {
                    $daysArray = explode(',', str_replace('^', '', $configs[$key]));
                    foreach ($daysArray as &$day) {
                        $day = trim($day);
                    }
                    $configs[$key] = $daysArray;
                }
            }
            foreach ($outbounds as $outbound) {

                $config = $configs[$outbound['user_id']];
                // Check if the current time is working hours of a weekday
                if (!self::shouldRun($job, $config['timezone'], $config['campaign_days'], $config['start_time'], $config['end_time'])) {
                    continue;
                }

                if ($type == 'first') {
                    $criteria = "(si_conversation_history = '' OR si_conversation_history IS NULL)";
                } else {
                    $criteria = "(si_conversation_history != '' AND si_conversation_history IS NOT NULL)";
                }

                if ($config['require_approval'] == 'Yes') {
                    $criteria .= " AND (status = 'approved' OR status = 'followup_approved')";
                }

                $randomNumber = rand(1, 100);
                if ($randomNumber > $config['email_frequency']) {
                    continue;
                }

                $userId = $outbound['user_id'];

                // Fetch a lead meeting criteria for sending emails
                $select = DBHelper::executeQuery(
                    "SELECT id
                    FROM leads
                    WHERE $criteria
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
    public static function shouldRun($job, $timezone, $days, $start_time, $end_time)
    {
        try {
            // Get current time in the given timezone
            $currentTime = new \DateTime('now', new \DateTimeZone($timezone));
            // Check if it's a weekend
            $isWeekend = !in_array($currentTime->format('l'), $days);

            // Check if the current time is outside the desired time to contact
            $isOutsideWorkingHours = $currentTime->format('H') < $start_time || $currentTime->format('H') >= $end_time;

            // If it's a weekend or outside working hours, return false
            if ($isWeekend || $isOutsideWorkingHours)
                return false;

            return true;
        } catch (\Exception $e) {
            $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $e->getMessage());
            return false;
        }
    }
}
