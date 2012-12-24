<?php defined('COT_CODE') or die('Wrong URL');
/*
 * English langfile for hybridauth
 */


/*
 * Meta & configuration
 */
$L['info_desc'] = 'Self-hosted single sign-on solution using several social networks for authentication, including Facebook, Twitter, Google, OpenID, Vk and others';
$L['info_notes'] = 'Edit plugins/hybridauth/conf/hybridauth.config.php after installation. Go to Admin / Configuration / HybridAuth and click Update every time you add more providers.';

$L['cfg_autoreg'] = 'Auto-register with social networks and disable built-in registration';

/*
 * Main strings
 */
$L['hybridauth_autoreg_inaction'] = 'Manual registration is disabled. Please log in with your social network account.';
$L['hybridauth_connect'] = 'Connect now';
$L['hybridauth_connected'] = 'Connected';
$L['hybridauth_disconnected'] = 'Disconnected';
$L['hybridauth_link'] = 'Link';
$L['hybridauth_title'] = 'Hybrid Authentication System';
$L['hybridauth_signin_with'] = 'Sign in with';
$L['hybridauth_unlink'] = 'Unlink';

/*
 * Messages
 */
$L['hybridauth_no_linked_account'] = 'There are no site accounts linked with your {$provider} account. Please log in with your existing site account or register a new account in a few clicks.';
