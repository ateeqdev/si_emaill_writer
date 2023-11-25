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

    $email_type = 'first';
    if ($bean->si_message_id) {
        $email_type = 'followup';
    }

    $senderName = trim($current_user->first_name . ' ' . $current_user->last_name);
    $toName = trim($bean->first_name . ' ' . $bean->last_name);
    $messageId = base64_decode(html_entity_decode($bean->si_message_id));

    $response = MailApiAdapter::sendEmail('themeetapps@gmail.com', $toName, $bean->si_email_subject ?? '', $bean->si_email_body, 'Malik Usman<br />CTO StackImagine<br />ServiceNow Developer<br />19x Certified', $messageId, 'd5f67553-a563-734c-4ac4-65617dec45b5');

    if (isset($response['error']) && $response['error'])
        return sendError($response['error']);

    $bean->si_message_id = base64_encode($response['message_id']);

    // Update conversation history
    $sentAt = gmdate('Y-m-d H:i:s', strtotime('now'));
    $historyItem = [
        'type' => 'sent',
        'sent_at' => $sentAt,
        'message' => [
            'from' => $senderName,
            'subject' => $bean->si_email_subject ?? '',
            'body' => strip_tags($bean->si_email_body),
            'thread_id' => $response['thread_id'] ?? null,
        ],
        'read_at' => null,
    ];

    if (!$bean->si_conversation_history) {
        $bean->si_conversation_history = json_encode([$historyItem]);
    } else {
        $history = json_decode(html_entity_decode($bean->si_conversation_history), true);
        $history[] = $historyItem;
        $bean->si_conversation_history = json_encode($history);
    }

    if (isset($response['thread_id']) && $response['thread_id'])
        $bean->si_thread_id = $response['thread_id'];

    // $bean->si_email_body = '';
    $bean->status = $email_type == 'first' ? 'sent' : 'followup_sent';
    $bean->si_followups_counter = $bean->si_followups_counter + 1;
    $bean->si_emailed_at = $sentAt;
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
