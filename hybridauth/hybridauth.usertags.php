<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=usertags.main
[END_COT_EXT]
==================== */

// Provides social accounts tags

require_once cot_incfile('hybridauth', 'plug');

global $hybridauth_config;

// Sync extra fields if necessary
foreach ($hybridauth_config['providers'] as $key => $val)
{
	$name = strtolower($key);
	if ($val['enabled'])
	{
		$fields = array("{$name}_id", "{$name}_url");
		foreach ($fields as $field_name)
		{
			$temp_array[strtoupper($field_name)] = $user_data['user_' . $field_name];
		}
	}
}
