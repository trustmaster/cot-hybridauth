<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.profile.tags
Tags=users.profile.tpl:{PHP|hybridauth_accounts}
[END_COT_EXT]
==================== */

// Displays social accounts linking/unlinking buttons in profile
require_once cot_incfile('hybridauth', 'plug');

hybridauth_accounts(null, $t);
