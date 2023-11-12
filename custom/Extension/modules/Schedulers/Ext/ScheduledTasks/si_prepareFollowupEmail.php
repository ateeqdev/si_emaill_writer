<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

$job_strings[] = 'si_prepareFollowupEmail';

/**
 * This function writes email(s) for leads who have not been contacted so far
 * @return bool  true if syncing is successful, false otherwise
 * @access public
 */
function si_prepareFollowupEmail()
{
    $file = 'custom/include/si_Campaigner/autoload.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        $GLOBALS['log']->fatal('File ' . $file . ' NOT Found');
        return false;
    }
    return si_Campaigner\Helpers\PrepareEmail::followupEmail('Leads');
}
