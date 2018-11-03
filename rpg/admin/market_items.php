<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGBlackMarket.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

// Have they authenticated (again) as an admin for this session?
/*if (!isset($user->data['session_admin']) || !$user->data['session_admin'])
{
	login_box('', $user->lang['LOGIN_ADMIN_CONFIRM'], $user->lang['LOGIN_ADMIN_SUCCESS'], true, false);
}*/

// Is user any type of admin? No, then stop here, each script needs to
// check specific permissions but this is a catchall
if (!$auth->acl_get('a_'))
{
	trigger_error('NO_ADMIN');
}

$t = new CustomTemplate($phpbb_root_path . 'rpg/admin/tpl');
$t->set_filenames(array('market_items' => 'market_items.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

global $db;

//syringes
$sql = 'SELECT DISTINCT item_id, place
		FROM rpg_blackmarket
		WHERE category = LOWER(\'' . CATEGORY_SYRINGES . '\')
		ORDER BY place';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$s = RPGSyringes::getSyringe($info['item_id']);
	
	$t->assign_block_vars('syringe_bloc', array(
		'NAME'	=>	$s->getName(),
		'DESC'	=>	$s->getDescription(),
		'PRICE'	=>	$s->getPrice(),
		'PLACE'	=>	$info['place'],
	));
}

$db->sql_freeresult($result);

//specials
$sql = 'SELECT DISTINCT item_id, place
		FROM rpg_blackmarket
		WHERE category = LOWER(\'' . CATEGORY_SPECIAL . '\')
		ORDER BY place';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$s = RPGSpecials::getSpecial($info['item_id']);
	
	$t->assign_block_vars('specials_bloc', array(
		'NAME'	=>	$s->getName(),
		'DESC'	=>	$s->getDescription(),
		'PRICE'	=>	$s->getPrice(),
		'PLACE'	=>	$info['place'],
	));
}

$db->sql_freeresult($result);

//sets
$sql = 'SELECT DISTINCT item_id, place
		FROM rpg_blackmarket
		WHERE category = LOWER(\'' . CATEGORY_SETS . '\')
		ORDER BY place';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$s = RPGSets::getSet($info['item_id']);
	
	$t->assign_block_vars('sets_bloc', array(
		'NAME'	=>	$s->getName(),
		'DESC'	=>	$s->getDescription(),
		'PRICE'	=>	$s->getPrice(),
		'PLACE'	=>	$info['place'],
	));
}

$db->sql_freeresult($result);

//equips
$sql = 'SELECT DISTINCT item_id, place
		FROM rpg_blackmarket
		WHERE category = LOWER(\'' . CATEGORY_EQUIPS . '\')
		ORDER BY place';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$e = RPGBlackMarket::getPart($info['item_id']);
	
	$t->assign_block_vars('equips_bloc', array(
		'NAME'	=>	$e->getName(),
		'DESC'	=>	$e->getDescription(),
		'PRICE'	=>	$e->getPrice(),
		'PLACE'	=>	$info['place'],
	));
}

$db->sql_freeresult($result);

$t->pparse('market_items');

?>