<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

require_once cot_incfile('hybridauth', 'plug');

// Sync extra fields if necessary
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
				cot_message($L['Added'] . " `$db_users`.`$field_name`");
			}
		}
	}
}

$tt = new XTemplate(cot_tplfile('hybridauth.tools', 'plug'));

cot_display_messages($tt);

// Display the main page


$tt->assign(array(
	'HYBRIDAUTH_URL'    => cot_url('admin', 'm=other&p=hybridauth'),
	'HYBRIDAUTH_ACTION' => cot_url('admin', 'm=other&p=hybridauth&a=send')
));

$tt->parse();
$plugin_body = $tt->text('MAIN');
