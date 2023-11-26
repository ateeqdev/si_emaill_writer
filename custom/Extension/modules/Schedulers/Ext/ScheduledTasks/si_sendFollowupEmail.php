<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

$job_strings[] = 'si_sendFollowupEmail';

/**
 * This function checks if a reply has been received, if so, it marks the lead to not be contacted via automated email.
 * @return bool  true if syncing is successful, false otherwise
 * @access public
 */
function si_sendFollowupEmail()
{
    $file = 'custom/include/si_Campaigner/autoload.php';
    if (file_exists($file)) {
        try {
            require_once $file;
        } catch (\Throwable $th) {
            $GLOBALS['log']->fatal($th->getMessage());
            return false;
        }
    } else {
        $GLOBALS['log']->fatal('File ' . $file . ' NOT Found');
        return false;
    }
    return si_Campaigner\SMTP\Send::followupEmail('Leads');
}
