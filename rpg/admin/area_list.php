<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGBattleAreas.class.php');

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
$t->set_filenames(array('area_list' => 'area_list.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$areas = RPGBattleAreas::getAreas();

foreach($areas as $a) {
	
	$t->assign_block_vars('area_bloc', array(
		'ID'		=> $a->getId(),
		'NAME'		=> $a->getName(),
		'DESC'		=> $a->getDescription(),
		'LEVEL'		=> $a->getLevel(),
		'BGM'		=> $a->getBGM(),
		'BACKGROUND' => $a->getBackground(),
	));
}

foreach($areas as $a) {
	$parts = $a->getAreaParts();
	
	foreach($parts as $p) {
		$t->assign_block_vars('part_bloc', array(
			'NAME'	=>	$p->getName(),
			'AREA'	=>	$a->getName(),
			'MINLEVEL'	=> $p->getMinLevel(),
			'MAXLEVEL'	=> $p->getMaxLevel(),
		));
	}
}

$t->pparse('area_list');

?>