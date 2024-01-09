<?php

function pre_uninstall()
{
	try {
		deleteSchedulerJobs();
		removeFieldsFromLayout();
		$GLOBALS['log']->fatal("SIEmailWriter pre uninstall script successful...");
	} catch (Exception $ex) {
		$GLOBALS['log']->fatal("Exception occurred in pre_uninstall of SI Email Writer: " . $ex->getMessage());
	}
}

function removeFieldsFromLayout()
{
	require "ModuleInstall/ModuleInstaller.php";
	$installer_func = new ModuleInstaller();
	$installer_func->removeFieldsFromLayout(['Accounts' => 'si_linkedin_profile']);
	$installer_func->removeFieldsFromLayout(['Accounts' => 'si_leads_contacted']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_linkedin_profile']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'description']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_company_linkedin_profile']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_company_description']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_email_body']);
	$installer_func->removeFieldsFromLayout(['Leads' => 'si_email_subject']);
	$installer_func->removeFieldsFromLayout(['Users' => 'si_gmail_id']);
}

function deleteSchedulerJobs()
{
	$job_names = array('SIEmailWriter - Prepare Email', 'SIEmailWriter - Send Emails', 'SIEmailWriter - Sync Replies');
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
