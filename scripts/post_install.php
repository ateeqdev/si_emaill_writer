<?php

require_once('modules/UpgradeWizard/UpgradeRemoval.php');

function post_install()
{
    try {
        require_once('modules/Configurator/Configurator.php');
        require_once("modules/Administration/QuickRepairAndRebuild.php");
        $configurator = new Configurator();
        $rt_config = array(
            'GOOGLE' => array(
                'APP_NAME' => 'SI Campaigner',
                'PROJECT_ID' => 'sincere-office-185506',
                'CLIENT_ID' => '764812510524-8bcbajv8ibve4lhd7sealuvg50l2qqi9.apps.googleusercontent.com',
                'CLIENT_SECRET' => 'hV_3dKkt6_OZbwCzwOpa4scl',
                'REDIRECT_URI' => 'https://cdn-plugins.rolustech.com/campaigner/redirect.php',
                'SCOPES' =>
                array(
                    0 => 'https://www.googleapis.com/auth/drive', //google drive
                    1 => 'https://www.googleapis.com/auth/calendar', //google calendar
                    2 => 'https://www.google.com/m8/feeds/', //google contacts
                ),
                'APP_ID' => 'AIzaSyATWT-A_a7sm3elgL4w3DXh1WIMxN63FwM',
            ),
        );
        //Load config
        $configurator->loadConfig();
        $configurator->config = array_merge($configurator->config, $rt_config);
        //Save the new setting
        if (!array_key_exists('http_referer', $configurator->config)) {
            $configurator->config['http_referer'] = array();
            $configurator->config['http_referer']['list'] = array();
            $configurator->config['addAjaxBannedModules'] = array();
        }
        if (!in_array("si_Campaigner", $configurator->config['addAjaxBannedModules'])) {
            $configurator->config['addAjaxBannedModules'][] = 'si_Campaigner';
        }
        if (!in_array("https://cdn-plugins.stackimagine.com/campaigner/redirect.php", $configurator->config['http_referer'])) {
            $configurator->config['http_referer']['list'][] = 'https://cdn-plugins.stackimagine.com/campaigner/redirect.php';
        }
        if (!in_array("accounts.google.com", $configurator->config['http_referer'])) {
            $configurator->config['http_referer']['list'][] = 'accounts.google.com';
        }
        $configurator->handleOverride();

        if (createJOB('Campaigner - Prepare Email', 'function::siPrepareEmail', '*/5::*::*::*::*') === true) {
            $GLOBALS['log']->fatal('Campaigner - Prepare Email job created');
        }
        if (createJOB('Campaigner - Send Emails', 'function::siSendEmails', '*/5::*::*::*::*') === true) {
            $GLOBALS['log']->fatal('Campaigner - Send Emails job created');
        }
        if (createJOB('Campaigner - Sync Replies', 'function::siSSyncReplies', '*/5::*::*::*::*') === true) {
            $GLOBALS['log']->fatal('Campaigner - Sync Replies job created');
        }
        addFieldsToLayout();
        repair_and_rebuild();
        $GLOBALS['log']->fatal("SICampaigner installed successfully...");
    } catch (Exception $ex) {
        $GLOBALS['log']->fatal("SICampaigner Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
    }
    $php_v = (int)PHP_VERSION;
    if ($php_v == 8) {
        replaceVendorFile();
    }
}

function addFieldsToLayout()
{

    require 'custom/include/ModuleInstaller/CustomModuleInstaller.php';
    $installer_func = new CustomModuleInstaller();
    //add gmail id field in users' editview
    $installer_func->removeFieldsFromLayout(['Users' => 'si_gmail_id_c']);
    $installer_func->addFieldsToLayout(['Users' => 'si_gmail_id_c']);
    $installer_func->removeFieldsFromLayout(['Accounts' => 'si_linkedin_profile_c']);
    $installer_func->addFieldsToLayout(['Accounts' => 'si_linkedin_profile_c']);
    $installer_func->removeFieldsFromLayout(['Accounts' => 'si_linkedin_bio_c']);
    $installer_func->addFieldsToLayout(['Accounts' => 'si_linkedin_bio_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_email_status_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_email_status_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_linkedin_profile_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_linkedin_profile_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_linkedin_bio_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_linkedin_bio_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_company_linkedin_profile_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_company_linkedin_profile_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_company_linkedin_bio_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_company_linkedin_bio_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_prompt_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_prompt_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_email_body_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_email_body_c']);
    // $installer_func->removeFieldsFromLayout(['Leads' => 'si_email_subject_c']);
    // $installer_func->addFieldsToLayout(['Leads' => 'si_email_subject_c']);
}
/**
 * This function replaces contents of File 'vendor/zf1/zend-xml/library/Zend/Xml/Security.php' with file 'custom/include/vendor_replace/Security.php'.
 * In file 'vendor/zf1/zend-xml/library/Zend/Xml/Security.php' on line 172 version_compare function is called with gte operator, php8 does not support
 * gte operator in version_compare function. That function will only be executed when php version is 8.
 */
function replaceVendorFile()
{
    if (file_exists(realpath('custom/include/vendor_replace/Security.php'))) {
        require_once('include/upload_file.php');
        $uploadFile = new UploadFile();
        $uploadFile->temp_file_location = 'custom/include/vendor_replace/Security.php';
        $file_contents = $uploadFile->get_file_contents();
        file_put_contents('vendor/zf1/zend-xml/library/Zend/Xml/Security.php', $file_contents);

        unlink(realpath('custom/include/vendor_replace/Security.php'));
        rmdir(realpath('custom/include/vendor_replace'));
    }
}

function createJOB($name, $job, $job_interval, $fields = [])
{
    $scheduler = BeanFactory::getBean('Schedulers');
    $scheduler->retrieve_by_string_fields(array('job' => $job, 'deleted' => '0'));
    //If there is no job by that name, create a new one
    //and set its job interval to the default interval
    if (empty($scheduler->id)) {
        $scheduler->job_interval = $job_interval;
        $scheduler->name = $name;
        $scheduler->job = $job;
        $scheduler->date_time_start = '2005-01-01 00:00:00';
        $scheduler->status = 'Inactive';
        $scheduler->catch_up = '1';
    }
    foreach ($fields as $field => $value) {
        $scheduler->$field = $value;
    }
    //Undelete the job
    $scheduler->mark_undeleted($scheduler->id);
    if (method_exists($scheduler, 'save')) {
        $scheduler->save();
        return true;
    } else
        $GLOBALS['log']->fatal("SICampaigner Exception: Failed to save " . $scheduler->name . __FILE__ . ":" . __LINE__);
    return false;
}

function repair_and_rebuild()
{
    global $mod_strings;
    // force developer mode for full vardef/dictionary refresh
    $backupDevMode = inDeveloperMode();
    $sugar_config['developerMode'] = true;
    // setup LBL_ALL_MODULES for full QRR
    $backupModStrings = isset($mod_strings) ? $mod_strings : null;
    require 'modules/Administration/language/en_us.lang.php';
    // perform full QRR - execute query mode
    require_once 'modules/Administration/QuickRepairAndRebuild.php';
    $repairer = new RepairAndClear();
    $repairer->repairAndClearAll(array('clearAll'), array($mod_strings['LBL_ALL_MODULES']), true, false);
    // reset altered flags to it's original state
    $sugar_config['developerMode'] = $backupDevMode;
    $mod_strings = $backupModStrings;
}
