# HybridAuth plugin for Cotonti

[HybridAuth](hybridauth.sourceforge.net) is a self-hosted single sign on solution for PHP.

## Requirements

* Cotonti >= 0.9.11
* PHP extensions: curl, json

## Installation

1. Copy the plugin to your Cotonti plugins folder.
2. Install it in Administration / Extensions.
3. Edit plugins/hybridauth/conf/hybridauth.config.php according to [HybridAuth User Guide](http://hybridauth.sourceforge.net/userguide/Configuration.html).
4. Go to Administration / Configuration / HybridAuth and click Update.
5. Add `{PHP|hybridauth_login}` to your theme's login.tpl and `{PHP|hybridauth_accounts}` to your theme's users.profile.tpl.
