<?php defined('COT_CODE') or die('Wrong URL');

// Sync user extrafields with hybridauth.config file

require_once cot_incfile('hybridauth', 'plug');

foreach ($hybridauth_config['providers'] as $key => $val)
{
	$name = strtolower($key);
	if ($val['enabled'])
	{
		$fields = array("user_{$name}_id", "user_{$name}_url");
		foreach ($fields as $field_name)
		{
			if (!$db->fieldExists($db_users, $field_name))
			{
				// Create a missing user field
				$db->query("ALTER TABLE `$db_users` ADD COLUMN `$field_name` VARCHAR(96)");
			}
		}
	}
}
