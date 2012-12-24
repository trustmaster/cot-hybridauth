<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.auth.check.query
[END_COT_EXT]
==================== */

if (isset($_SESSION['cot_hybridauth']))
{
	require_once cot_incfile('hybridauth', 'plug');

	$provider = $_SESSION['cot_hybridauth']['provider'];
	$identifier = $_SESSION['cot_hybridauth']['identifier'];
	if (!empty($provider) && in_array($provider, array_map('strtolower', array_keys($hybridauth_config['providers']))) && !empty($identifier))
	{
		$field = "user_{$provider}_id";
		$user_select_condition = "`$field` = " . $db->quote($identifier);
	}
}
