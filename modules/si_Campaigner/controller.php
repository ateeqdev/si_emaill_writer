<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class si_CampaignerController extends SugarController
{
	public function action_getCompanyData()
	{
		if (!$this->acl())
			return;

		header('Content-Type: application/json');
		$id = $_REQUEST['leadId'];
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
		$account = \BeanFactory::getBean('Accounts', $relatedAccount[0]);

		$response = [
			'si_company_linkedin_profile' => $account->si_linkedin_profile,
			'si_company_linkedin_bio' => $account->si_linkedin_bio
		];
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function acl()
	{
		global $current_user;
		if (!$current_user->id) {
			return false;
		}
		return true;
	}
}
