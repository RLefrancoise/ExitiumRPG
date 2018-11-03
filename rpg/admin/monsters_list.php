<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGMonsters.class.php');

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
$t->set_filenames(array('monsters_list' => 'monsters_list.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$monsters = RPGMonsters::getMonsters();

foreach($monsters as $monster) {
	$behaviors = $monster->getBehaviors();
	$behavior = '';
	
	foreach($behaviors as $b) {
		$behavior .= $b . ' ';
	}

	$skills = $monster->getSkills();
	$skills_names = $monster->getSkillsNames();
	
	$s = '';
	
	for($i = 0 ; $i < count($skills) ; $i++) {
		$s .= $skills_names[$i] . " [{$skills[$i]->getName()}/{$skills[$i]->getElement()}]<br>";
	}
	
	$t->assign_block_vars('monster_bloc', array(
		'ID'		=> $monster->getId(),
		'NAME'		=> $monster->getName(),
		'IMG'		=> $monster->getImage(),
		'BGM'		=> $monster->getBGM(),
		'LEVEL'		=> $monster->getLevel(),
		'PV'		=> $monster->getPV(),
		'PF'		=> $monster->getPF(),
		'ATTACK'	=> $monster->getAttack(),
		'DEFENSE'	=> $monster->getDefense(),
		'SPEED'		=> $monster->getSpeed(),
		'FLUX'		=> $monster->getFlux(),
		'RESISTANCE' => $monster->getResistance(),
		'RALZ'		=> $monster->getRalz(),
		'BEHAVIOR'	=> $behavior,
		'SKILLS'	=> $s,
	));
}

$t->pparse('monsters_list');

?>