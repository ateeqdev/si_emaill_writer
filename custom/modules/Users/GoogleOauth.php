<?php

require_once 'custom/include/si_Campaigner/autoload.php';

use si_Campaigner\apiCalls\OAuthApiAdapter;
use si_Campaigner\Sugar\Helpers\UpdateBean;

try {
	global $sugar_config, $current_user;
	if (isset($_GET['code'])) {

		$newCredentials = OAuthApiAdapter::authenticate($_GET['code'], $current_user->id);
		$GLOBALS['log']->debug('File: ' . __FILE__ . ', Line# ' . __LINE__ . ' ' . $newCredentials);
		UpdateBean::update('users', $newCredentials);
		$uBean = new \Users();
		$uBean->retrieve($current_user->id);

		if (!empty($newCredentials) && !empty($uBean->si_google_refresh_code)) {
			//show message authentication done and redirect user where you want
			echo "You have done ....</a>";
			global $current_user;
			SugarApplication::redirect("index.php?module=Users&action=DetailView&record=" . $current_user->id);
		} else {
			//show message authentication fail and redirect user to auth url so that auth code can be grabbed once again
			echo "Error occurred please <a href='$authUrl'>try again</a>";
		}
	} else {
		if (isset($_GET['error'])) {
			echo "Error occurred please <a href='$authUrl'>try again</a>";
		} else {
			//Request authorization
			$GLOBALS['log']->debug('File: ' . __FILE__ . ', Line# ' . __LINE__ . ' ' . "redirecting to ....");
			$authUrl = OAuthApiAdapter::authorize();
			echo '<script type="text/javascript"> top.window.location.href="' . $authUrl . '";</script>'; // SuiteCRM 8 Compatible
			exit;
		}
	}
} catch (Exception $ex) {
	if (strpos($ex->getMessage(), 'invalid_grant') !== false) {
		echo "Already, you have authorized.";
		SugarApplication::redirect("index.php");
	} else {
		echo "Error occurred, try later on...";
	}
	$GLOBALS['log']->fatal("RTGSync Exception in " . __FILE__ . ":" . __LINE__ . ": " . $ex->getMessage());
}
