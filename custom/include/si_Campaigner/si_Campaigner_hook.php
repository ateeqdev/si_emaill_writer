<?php
require_once("custom/include/si_Campaigner/autoload.php");

use si_Campaigner\Sugar\Helpers\DBHelper;

class si_CampaignerHook
{
    /**
     * This function associates a lead with an account based on company linkedin url
     *
     *
     * @param  object  $bean    bean
     * @param  string  $event   sugar status
     * @access public
     */
    function linkAccount($bean, $event)
    {
        if (empty($bean->si_company_linkedin_profile) && empty($bean->company_website)) {
            return false;
        }

        if (!empty($bean->si_company_linkedin_profile))
            $account = DBHelper::select("accounts", "id", ["si_linkedin_profile" => ["=", $bean->si_company_linkedin_profile]]);

        if (empty($account) && !empty($bean->si_company_website))
            $account = DBHelper::select("accounts", "id", ["website" => ["=", $bean->si_company_website]]);

        if (!empty($account)) {
            $bean->account_id = $account[0]['id'];
            $bean->si_company_linkedin_profile = '';
            $bean->si_company_website = '';
        }
    }
};
