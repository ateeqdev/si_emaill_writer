<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

$job_strings[] = 'markReplyReceived';

/**
 * This function checks if a reply has been received, if so, it marks the lead to not be contacted via automated email.
 * @return bool  true if syncing is successful, false otherwise
 * @access public
 */
function markReplyReceived()
{
    $file = 'custom/include/si_Campaigner/autoload.php';
    if (file_exists($file)) {
        require $file;
    } else {
        $GLOBALS['log']->fatal('File ' . $file . ' NOT Found');
        return false;
    }
    return si_Campaigner\Helpers\MarkReplyReceived::run('Leads');
}
