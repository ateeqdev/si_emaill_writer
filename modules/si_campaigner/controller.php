<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class si_CampaignerController extends SugarController
{
	public function action_getCompanyData()
	{
		$id = $_REQUEST['id'];
		$lead = \BeanFactory::getBean('Leads', $id);

		if (!$lead) {
			echo json_encode(['error' => 'Lead not found']);
			return;
		}
		$lead->load_relationship('accounts');
		$relatedAccount = $lead->accounts->get();
		if (!$relatedAccount || count($relatedAccount) < 0) {
			echo json_encode(['error' => 'No related account found']);
			return;
		}
		$account = reset($relatedAccount);

		$linkedinProfile = $account->linkedin_profile_c;
		$linkedinBio = $account->linkedin_bio_c;

		$response = [
			'company_linkedin_profile_c' => $linkedinProfile,
			'company_linkedin_bio_c' => $linkedinBio
		];
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}
