<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if (empty($_REQUEST['method'])) {
    header('HTTP/1.1 400 Bad Request');
    $response = ["error" => "method is required."];
    echo json_encode($response);
}

if ($_REQUEST['method'] == 'getCompanyData') {

    $id = $_REQUEST['id'];
    $lead = \BeanFactory::getBean('Leads', $id);

    if ($lead) {
        $lead->load_relationship('accounts');
        $relatedAccount = $lead->accounts->get();
        if ($relatedAccount && count($relatedAccount) > 0) {
            $account = reset($relatedAccount);

            $linkedinProfile = $account->linkedin_profile_c;
            $linkedinBio = $account->linkedin_bio_c;

            $response = [
                'company_linkedin_profile_c' => $linkedinProfile,
                'company_linkedin_bio_c' => $linkedinBio
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'No related account found']);
        }
    } else {
        echo json_encode(['error' => 'Lead not found']);
    }
}
