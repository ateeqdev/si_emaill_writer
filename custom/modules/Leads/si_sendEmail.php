<?php

require_once 'custom/include/si_Campaigner/autoload.php';

use si_Campaigner\apiCalls\MailApiAdapter;

try {
    global $current_user;

    $bean = \BeanFactory::getBean(ucfirst($_REQUEST['module']), $_REQUEST['id']);
    $toEmailAddress = $bean->email1 ?? $bean->email2 ?? '';

    if (!$bean)
        return sendError('Record not found');
    if (!$toEmailAddress)
        return sendError('The record does not have an email address');
    if (!$bean->si_email_body)
        return sendError('Email body is empty');

    $senderName =  $current_user->first_name ? $current_user->first_name . ' ' : '';
    $senderName .= $current_user->last_name;
    $senderName = trim($senderName);
    $response = MailApiAdapter::sendEmail('themeetapps@gmail.com', $bean->first_name . $bean->last_name, $bean->si_email_subject ?? '', $bean->si_email_body, 'Malik Usman<br />CTO StackImagine<br />ServiceNow Developer<br />19x Certified');
    if (isset($response['error']) && $response['error'])
        return sendError($response['error']);

    // $bean->si_email_body = '';
    $bean->save();
    echo json_encode($response);
} catch (Exception $ex) {
    $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
}

function sendError($error)
{
    $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $error);
    echo json_encode(['error' => $error]);
}
