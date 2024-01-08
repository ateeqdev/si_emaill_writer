<?php

require_once 'custom/include/si_Campaigner/autoload.php';

use si_Campaigner\Helpers\PrepareEmail;

try {
    global $current_user;
    $result = PrepareEmail::writeEmail(ucfirst($_REQUEST['module']), $_REQUEST['id']);
    $GLOBALS['log']->fatal($result);

    if ($result !== 'true')
        sendError($result);
} catch (Exception $ex) {
    $GLOBALS['log']->fatal("si_Campaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
}

function sendError($error)
{
    $GLOBALS['log']->fatal("si_Campaigner Error in " . __FILE__ . ":" . __LINE__ . ": " . $error);
    echo json_encode(['error' => $error]);
}
