<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.logout
[END_COT_EXT]
==================== */

// Attemps to log the user out of all accounts

if (isset($_SESSION['cot_hybridauth']))
{
	require_once cot_incfile('hybridauth', 'plug');
	try
	{
		$hybridauth = new Hybrid_Auth($hybridauth_config);
		$hybridauth->logoutAllProviders();
	}
	catch (Exception $e)
	{
		// Ignore it
	}
}
