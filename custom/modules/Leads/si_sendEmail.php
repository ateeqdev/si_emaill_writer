<?php

require_once 'custom/include/si_Campaigner/autoload.php';

use si_Campaigner\apiCalls\MailApiAdapter;
use si_Campaigner\Sugar\Helpers\DBHelper;

try {
    global $current_user;
    $id = $_REQUEST['id'];

    $bean = \BeanFactory::getBean(ucfirst($_REQUEST['module']), $id);
    $emailAddress = $bean->email1 ?? $bean->email2 ?? '';

    if (!$bean)
        return sendError('Record not found');
    if (!$emailAddress)
        return sendError('The record does not have an email address');
    if (!$bean->si_email_body)
        return sendError('Email body is empty');


    $senderName =  $current_user->first_name ? $current_user->first_name . ' ' : '';
    $senderName .= $current_user->last_name;
    $senderName = trim($senderName);
    return MailApiAdapter::sendEmail($current_user->emailAddress, $senderName, $emailAddress, $bean->si_email_subject ?? '', $bean->si_email_body);
} catch (Exception $ex) {
    $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
}

function sendError($error)
{
    $GLOBALS['log']->fatal($error);
    return ['error' => $error];
}
