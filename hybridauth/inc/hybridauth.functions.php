<?php defined('COT_CODE') or die('Wrong URL');

// Common hybridauth functions

require_once cot_incfile('users', 'module');

require_once cot_langfile('hybridauth', 'plug');

require_once $cfg['plugins_dir'] . '/hybridauth/lib/Hybrid/Auth.php';

// Read the config into a global
global $hybridauth_config;
$hybridauth_config = include $cfg['plugins_dir'] . '/hybridauth/conf/hybridauth.config.php';

/**
 * Completes user profile fields with data retrieved from a hybrid profile
 * @param  object $user_profile  Hybrid_User_Profile object
 * @param  array  $ruser         Profile fields
 * @param  string $provider_code Lowercase provider name
 * @return array                 Completed $ruser
 */
function hybridauth_complete_profile($user_profile, $ruser = array(), $provider_code = '')
{
	global $cfg, $cot_extrafields, $db, $db_users;

	if (empty($user_profile->displayName) && !empty($user_profile->firstName) && !empty($user_profile->lastName))
	{
		$user_profile->displayName = $user_profile->firstName . ' ' . $user_profile->lastName;
	}

	if (empty($ruser['user_name']))
	{
		$user_name = $user_profile->displayName;
		while ($db->query("SELECT COUNT(*) FROM $db_users WHERE user_name = ?", $user_name)->fetchColumn() > 0)
		{
			// Name is busy, generate a random prefix
			$user_name = $user_profile->displayName . mt_rand(2,9999);
		}
		$ruser['user_name'] = $user_name;
	}

	if (empty($ruser['user_email']))
	{
		if ($provider_code == 'vkontakte')
		{
			// Vkontakte does not provide user emails
			$ruser['user_email'] = 'user' . $user_profile->identifier . '@vk.com';
		}
		elseif ($provider_code == 'odnoklassniki')
		{
			// Vkontakte does not provide user emails
			$ruser['user_email'] = 'user' . $user_profile->identifier . '@odnoklassniki.ru';
		}
		elseif ($provider_code == 'twitter')
		{
			// Vkontakte does not provide user emails
			$ruser['user_email'] = 'user' . $user_profile->identifier . '@twitter.com';
		}
		else
			$ruser['user_email'] = $user_profile->email;
	}

	if (empty($ruser['user_birthdate']))
		$ruser['user_birthdate'] = cot_mktime(1, 0, 0, $user_profile->birthMonth, $user_profile->birthDay, $user_profile->birthYear);

	if (empty($ruser['user_country']) && strlen($user_profile->country) == 2)
		$ruser['user_country'] = $user_profile->country;

	if (empty($ruser['user_lang']) && !$cfg['forcedefaultlang'] && !empty($user_profile->language))
	{
		$lang = $user_profile->language;
		if (file_exists("lang/$lang"))
		{
			$ruser['user_lang'] = $lang;
		}
	}

	if (empty($ruser['user_gender']) && $user_profile->gender !== null)
		$ruser['user_gender'] = $user_profile->gender == 'female' ? 'F' : 'M';

	if ((!empty($user_profile->photoURL) || !empty($user_profile->avatarURL)) && $db->fieldExists($db_users, 'user_avatar'))
		$ruser['user_avatar'] = empty($user_profile->avatarURL) ? $user_profile->photoURL : $user_profile->avatarURL;

	if (!empty($user_profile->photoURL) && $db->fieldExists($db_users, 'user_photo'))
		$ruser['user_photo'] = $user_profile->photoURL;

	// Some extra fields
	if (isset($cot_extrafields[$db_users]['firstname']) && empty($ruser['user_firstname']))
		$ruser['user_firstname'] = $user_profile->firstName;

	if (isset($cot_extrafields[$db_users]['lastname']) && empty($ruser['user_lastname']))
		$ruser['user_lastname'] = $user_profile->lastName;

	if (!empty($provider_code))
	{
		$ruser["user_{$provider_code}_id"] = $user_profile->identifier;
		$ruser["user_{$provider_code}_url"] = $user_profile->profileURL;
	}

	return $ruser;
}

/**
 * Generates social login widget for use in templates
 * @param  string $tpl Template code
 * @return string      Rendered HTML
 */
function hybridauth_login($tpl = 'hybridauth.login')
{
	global $hybridauth_config;

	if (empty($tpl))
		$tpl = 'hybridauth.login';

	$t = new XTemplate(cot_tplfile($tpl, 'plug'));

	foreach ($hybridauth_config['providers'] as $key => $val)
	{
		if ($val['enabled'])
		{
			$t->assign(array(
				'HYBRID_PROVIDER_NAME' => $key,
				'HYBRID_PROVIDER_CODE' => strtolower($key),
				'HYBRID_PROVIDER_URL' => cot_url('plug', 'e=hybridauth&a=login&provider='.$key)
			));
			$t->parse('MAIN.HYBRID_PROVIDER');
		}
	}

	$t->parse();
	return $t->text();
}

/**
 * Renders social account link/unlink widget or assigns appropriate profile tags
 * @param  string    $tpl Template code
 * @param  XTemplate $t   Existing template object
 * @return mixed          HTML output if $t is null or TRUE otherwise
 */
function hybridauth_accounts($tpl = 'hybridauth.accounts', $t = null)
{
	global $hybridauth_config, $usr, $L;

	$render = false;
	if (!is_object($t))
	{
		if (empty($tpl))
			$tpl = 'hybridauth.accounts';

		$t = new XTemplate(cot_tplfile($tpl, 'plug'));
		$render = true;
	}

	$hybridauth = new Hybrid_Auth($hybridauth_config);

	foreach ($hybridauth_config['providers'] as $key => $val)
	{
		if ($val['enabled'])
		{
			$code = strtolower($key);
			$linked = !empty($usr['profile']["user_{$code}_id"]);
			$action = $linked ? 'unlink' : 'link';
			$connected = $hybridauth->isConnectedWith($key);
			$t->assign(array(
				'HYBRID_ACCOUNT_NAME' => $key,
				'HYBRID_ACCOUNT_CODE' => $code,
				'HYBRID_ACCOUNT_LINK_URL' => cot_url('plug', 'e=hybridauth&a='.$action.'&provider='.$key),
				'HYBRID_ACCOUNT_LINKED' => $linked,
				'HYBRID_ACCOUNT_ACTION' => $L['hybridauth_' . $action],
				'HYBRID_ACCOUNT_PROFILE' => $linked ? $usr['profile']["user_{$code}_url"] : '',
				'HYBRID_ACCOUNT_CONNECTED' => $connected,
				'HYBRID_ACCOUNT_STATE' => $connected ? $L['hybridauth_connected'] : $L['hybridauth_disconnected'],
				'HYBRID_ACCOUNT_CONNECT' => $connected ? '' : $L['hybridauth_connect'],
				'HYBRID_ACCOUNT_CONNECT_URL' => $connected ? '' :  cot_url('plug', 'e=hybridauth&a=connect&provider='.$key)
			));
			$t->parse('MAIN.HYBRID_ACCOUNT');
		}
	}

	if ($render)
	{
		$t->parse();
		return $t->text();
	}
	else
	{
		return true;
	}
}

/**
 * A wrapper for HybridAuth objects for use across plugins
 */
class HybridAuth
{
	public $provider_name = '';
	public $provider_code = '';
	public $identifier = null;
	public $profile = null;
	public $auth = null;
	public $adapter = null;

	function __construct($provider_name = '')
	{
		global $hybridauth_config;

		if (isset($_SESSION['cot_hybridauth']))
		{
			$this->provider_name = ucfirst($_SESSION['cot_hybridauth']['provider']);
			$this->provider_code = $_SESSION['cot_hybridauth']['provider'];
		}
		else
		{
			$this->provider_name = $provider_name;
			$this->provider_code = strtolower($provider_name);
		}

		try
		{
			// Initialize HA and authenticate via provider
			$this->auth = new Hybrid_Auth($hybridauth_config);
			$this->adapter = $this->auth->authenticate($this->provider_name);
			// Get remote profile data
			$this->profile = $this->adapter->getUserProfile();
			$this->identifier = $this->profile->identifier;
		}
		catch (Exception $e)
		{
			// Something got wrong, redirect to login
			cot_redirect(cot_url('plug', 'e=hybridauth&a=login&provider='.$provider_name));
		}
	}
}
