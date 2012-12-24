<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.validate
[END_COT_EXT]
==================== */

// Fills missing fields after form submission and disables account validation

if ($cfg['plugin']['hybridauth']['autoreg'])
{
	cot_error('hybridauth_autoreg_inaction');
}

if (isset($_SESSION['cot_hybridauth']) && !cot_error_found())
{
	require_once cot_incfile('hybridauth', 'plug');

	$provider = ucfirst($_SESSION['cot_hybridauth']['provider']);
	$provider_code = $_SESSION['cot_hybridauth']['provider'];
	$identifier = $_SESSION['cot_hybridauth']['identifier'];

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
		// Something got wrong, redirect to login
		cot_redirect(cot_url('plug', 'e=hybridauth&a=login&provider='.$provider));
	}

	// Prefill data if necessary
	$ruser = hybridauth_complete_profile($user_profile, $ruser, $provider_code);

	// Disable activation for this account
	$cfg['users']['regnoactivation'] = true;
}
