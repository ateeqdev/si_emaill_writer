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
    function linkAccountToLead($bean, $event)
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

    function linkGmailAccount($bean, $event)
    {
        global $current_user;
        if ($bean->module_dir == 'Users' && $current_user->id == $bean->id) {
            if (isset($_REQUEST['oauth_redirect']) && $_REQUEST['oauth_redirect'] == '1') {
                $GLOBALS['log']->fatal("redirecting...");
                SugarApplication::redirect("index.php?module=Users&action=GoogleOauth");
            }
        }
        /*as date modified of document is not going to be changed when new revisions are created , this hook will change date_modified
        and also as there is no facility to update file of doc as excel or other type due to this reason we have to unlink that doc to be created new one
        */
        if ($bean->module_dir == 'DocumentRevisions') {
            if (!empty($bean->file_mime_type) && !empty($bean->documents->beans[$bean->document_id]->last_rev_mime_type)) {
                DBHelper::update("documents", ["date_modified" =>  $bean->date_modified], ["id" => ["=", $bean->document_id]]);
            }
        }
    }
};
