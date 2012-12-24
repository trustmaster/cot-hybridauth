<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

// Standalone HybridAuth controller

require_once cot_incfile('hybridauth', 'plug');

$provider = cot_import('provider', 'G', 'ALP');

if (!in_array($provider, array_keys($hybridauth_config['providers'])))
{
	cot_die_message(403);
}

if ($a == 'login' && $usr['id'] == 0)
{
	// Log in with a specific provider
	try
	{
		// Initialize HA and authenticate via provider
		$hybridauth = new Hybrid_Auth($hybridauth_config);
		$adapter = $hybridauth->authenticate($provider);
		// Get remote profile data
		$user_profile = $adapter->getUserProfile();
	}
	catch (Exception $e)
	{
		// Handle auth exception
		switch ($e->getCode())
		{
			case 0 : $error = "Unspecified error."; break;
			case 1 : $error = "Hybridauth configuration error."; break;
			case 2 : $error = "Provider not properly configured."; break;
			case 3 : $error = "Unknown or disabled provider."; break;
			case 4 : $error = "Missing provider application credentials."; break;
			case 5 : $error = "Authentification failed. The user has canceled the authentication or the provider refused the connection."; break;
			case 6 : $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.";
				     $adapter->logout();
				     break;
			case 7 : $error = "User not connected to the provider.";
				     $adapter->logout();
				     break;
		}
		cot_error($error);
		cot_redirect(cot_url('users', 'm=register', '', true));
	}

	// Check if there's a linked account
	$provider_code = strtolower($provider);

	// Save auth session
	$_SESSION['cot_hybridauth'] = array(
		'provider' => $provider_code,
		'identifier' => $user_profile->identifier
	);

	$field_name = "user_{$provider_code}_id";
	$res = $db->query("SELECT * FROM $db_users WHERE `$field_name` = ?", $user_profile->identifier);
	if ($res->rowCount() == 1)
	{
		// Log the user in via auth hook
		cot_redirect(cot_url('login', 'a=check&x='.$sys['xk'], '', true));
	}
	elseif ($cfg['plugin']['hybridauth']['autoreg'])
	{
		// Automatically create a new account
		$ruser = array(
			'user_password' => cot_unique(12)
		);

		$ruser = hybridauth_complete_profile($user_profile, $ruser, $provider_code);

		// Disable activation for this account
		$cfg['users']['regnoactivation'] = true;

		// Register
		$userid = cot_add_user($ruser);

		// Log in
		cot_redirect(cot_url('login', 'a=check&x='.$sys['xk'], '', true));
	}
	else
	{
		// Redirect to raw auth with a message
		cot_message(cot_rc('hybridauth_no_linked_account', array('provider' => $provider)));
		cot_redirect(cot_url('users', 'm=register', '', true));
	}
}
elseif ($a == 'connect' && $usr['id'] > 0)
{
	// Log in shortly
	$hybridauth = new Hybrid_Auth($hybridauth_config);
	$adapter = $hybridauth->authenticate($provider);

	// Redirect back to profile
	cot_redirect(cot_url('users', 'm=profile', '', true));
}
elseif ($a == 'link' && $usr['id'] > 0)
{
	// Link a social account
	$provider_code = strtolower($provider);
	$field_name = "user_{$provider_code}_id";

	// Authenticate via provider
	$hybridauth = new Hybrid_Auth($hybridauth_config);
	$adapter = $hybridauth->authenticate($provider);
	// Get remote profile data
	$user_profile = $adapter->getUserProfile();

	// Save profile fields
	$db->update($db_users, array(
			$field_name => $user_profile->identifier,
			"user_{$provider_code}_url" => $user_profile->profileURL
		), "user_id=?", $usr['id']);

	// Redirect back to profile
	cot_redirect(cot_url('users', 'm=profile', '', true));

}
elseif ($a == 'unlink' && $usr['id'] > 0)
{
	// Unlink social account
	$provider_code = strtolower($provider);
	$field_name = "user_{$provider_code}_id";
	if (!empty($usr['profile'][$field_name]))
	{
		// Erase the link
		$db->update($db_users, array($field_name => ''), "user_id=?", $usr['id']);
		// Logout from account
		$hybridauth = new Hybrid_Auth($hybridauth_config);
		$adapter = $hybridauth->authenticate($provider);
		$adapter->logout();
	}
}
else
{
	cot_die_message(403);
}
