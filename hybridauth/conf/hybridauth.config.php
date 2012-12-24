<?php

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

global $cfg;

return
	array(
		// Do not change this
		"base_url" => $cfg['mainurl'] . '/' . $cfg['plugins_dir'] . '/hybridauth/lib/',

		// Fill your data below
		"providers" => array(
			"Facebook" => array(
				"enabled" => true,
				"keys"    => array(
					"id" => "1234567890",
					"secret" => "your secret here"
				),
				"scope"   => "email, user_birthday",
				"display" => "page"
			),

			"Google" => array(
				"enabled" => true,
				"keys"    => array(
					"id" => "something.apps.googleusercontent.com",
					"secret" => "Google Secret Here"
				),
				"scope" => "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email",
			),

			"Twitter" => array(
				"enabled" => true,
				"keys"    => array(
					"key" => "twitter key",
					"secret" => "twitter secret"
				)
			),

			// // openid providers
			// "OpenID" => array(
			// 	"enabled" => false
			// )
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => realpath('.')."/datas/tmp/hybridauth.log"
	);
