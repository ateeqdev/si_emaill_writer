<?php

require_once 'custom/include/si_Campaigner/autoload.php';


try {

    $bean = \BeanFactory::getBean(ucfirst(ucfirst($_REQUEST['module'])), $_REQUEST['id'], array('disable_row_level_security' => true));

    if (!$bean)
        return sendError('Record not found');
    if (!$bean->si_email_body)
        return sendError('Email body is empty');

    if ($bean->status == 'ready_for_approval')
        $bean->status = 'approved';
    else if ($bean->status == 'followup_written')
        $bean->status = 'followup_approved';
    else
        return sendError('The Lead status does not require approval.');

    $bean->save();

    echo json_encode(['success' => 'success']);
} catch (Exception $ex) {
    $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
}

function sendError($error)
{
    $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $error);
    echo json_encode(['error' => $error]);
}
