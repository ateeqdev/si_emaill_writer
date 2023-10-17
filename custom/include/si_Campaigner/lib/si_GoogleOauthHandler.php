<?php
require_once 'modules/Users/User.php';

class si_GoogleOauthHandler
{
	public static function getStoredCredentials($user_id)
	{
		global $sugar_config;
		$user = new \User();
		$user->retrieve($user_id);
		if (empty($user->id)) {
			$GLOBALS['log']->fatal("Unable to fetch current user's google auth credentials");
			return false;
		}
		$uBean = \BeanFactory::getBean('users', $user_id);
		if (!empty($uBean->si_google_refresh_code))
			return [
				'access_token'	=> $uBean->si_google_access_code,
				'refresh_token'	=> $uBean->si_google_refresh_code,
				'expires_in'	=> $uBean->si_google_auth_expires_in,
				'client_id' 	=> $sugar_config['GOOGLE']['CLIENT_ID'],
				'client_secret' => $sugar_config['GOOGLE']['CLIENT_SECRET']
			];

		return false;
	}
}
