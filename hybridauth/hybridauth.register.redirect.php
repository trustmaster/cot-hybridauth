<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.add.done
[END_COT_EXT]
==================== */

// Redirect the user to automatic login after registration

if (isset($_SESSION['cot_hybridauth']))
{
	cot_redirect(cot_url('login', 'a=check&x='.$sys['xk'], '', true));
}
