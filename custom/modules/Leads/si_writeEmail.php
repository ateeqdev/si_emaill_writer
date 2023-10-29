<?php

require_once 'custom/include/si_Campaigner/autoload.php';

use si_Campaigner\apiCalls\OpenAIApiAdapter;

try {
    global $current_user;

    $bean = \BeanFactory::getBean(ucfirst($_REQUEST['module']), $_REQUEST['id']);
    $toEmailAddress = $bean->si_ ?? $bean->email2 ?? '';

    if (!$bean)
        return sendError('Record not found');

    $bean->load_relationship('accounts');
    $relatedAccount = $bean->accounts->get();
    if (!$relatedAccount || count($relatedAccount) < 0) {
        return sendError('No related account found');
    }
    $account = \BeanFactory::getBean('Accounts', $relatedAccount[0]);

    $response = OpenAIApiAdapter::firstEmail($bean->first_name . ' ' . $bean->last_name, $bean->si_linkedin_bio,  $account->description);
    if (isset($response['error']) && $response['error'])
        return sendError($response['error']);
    $bean->si_email_subject = $response['subject'];
    $bean->si_email_body = $response['body'] ? nl2br($response['body']) : (isset($response['choices'][0]['message']['content']) ? $response['choices'][0]['message']['content'] : '');
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
