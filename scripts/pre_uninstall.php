<?php

function pre_uninstall()
{
	try {
		deleteSchedulerJobs();
		removeFieldsFromLayout();
		$GLOBALS['log']->fatal("SICampaigner pre uninstall script successful...");
	} catch (Exception $ex) {
		$GLOBALS['log']->fatal("Exception occurred in pre_uninstall of SI Campaigner: " . $ex->getMessage());
	}
}

function removeFieldsFromLayout()
{
	require "ModuleInstall/ModuleInstaller.php";
	$installer_func = new ModuleInstaller();
	$installer_func->removeFieldsFromLayout(['Accounts' => 'si_linkedin_profile_c']);
	$installer_func->removeFieldsFromLayout(['Accounts' => 'si_linkedin_bio_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_email_status_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_linkedin_profile_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_linkedin_bio_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_company_linkedin_profile_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_company_linkedin_bio_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_prompt_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_email_body_c']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_email_subject_c']);
	$installer_func->removeFieldsFromLayout(['Users' => 'si_gmail_id_c']);
}

function deleteSchedulerJobs()
{
	$job_names = array('Campaigner - Prepare Email', 'Campaigner - Send Emails', 'Campaigner - Sync Replies');
	foreach ($job_names as $job_name) {
		$scheduler = BeanFactory::getBean('Schedulers');
		$scheduler->retrieve_by_string_fields(array('name' => $job_name));
		if (!empty($scheduler->id)) {
			$scheduler->mark_deleted($scheduler->id);
			if (method_exists($scheduler, 'save')) {
				$scheduler->save();
			} else
				$GLOBALS['log']->fatal("SI Exception: Failed to save " . $scheduler->name . __FILE__ . ":" . __LINE__);
		}
	}
	return true;
}
