<?php defined('COT_CODE') or die('Wrong URL');

// Drop hybrid auth field on uninstall

// require_once cot_incfile('hybridauth', 'plug');

// $providers = array_filter(array_keys($hybridauth_config['providers']));

// For some weird reason the following loop halts the script

// foreach ($providers as $k)
// {
// 	$name = mb_strtolower($k);
// 	$fields = array("user_{$name}_id", "user_{$name}_url");
// 	foreach ($fields as $field_name)
// 	{
// 		if ($db->fieldExists($db_users, $field_name))
// 		{
// 			// Create a missing user field
// 			$db->query("ALTER TABLE `$db_users` DROP COLUMN `$field_name`");
// 		}
// 	}
// }
