<?php defined('COT_CODE') or die('Wrong URL');

// Drop hybrid auth field on uninstall

$hybridauth_config = include $cfg['plugins_dir'] . '/hybridauth/conf/hybridauth.config.php';

foreach ($hybridauth_config['providers'] as $key => $val)
{
	$name = strtolower($key);
	$fields = array("user_{$name}_id", "user_{$name}_url");
	foreach ($fields as $field_name)
	{
		if ($db->fieldExists($db_users, $field_name))
		{
			// Create a missing user field
			$db->query("ALTER TABLE `$db_users` DROP COLUMN `$field_name`");
		}
	}
}
