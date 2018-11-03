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
$t->set_filenames(array('monster_form' => 'monster_form.tpl'));

$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$mode = request_var('mode', '');
if($mode == '') {
	echo 'mode invalide';
	return;
}

$t->assign_vars(array(
	'MODE'	=> $mode,
));

if($mode == 'edit') {

	$monster_id = request_var('id', -1);
	if($monster_id == -1) {
		echo 'Id de monstre invalide.';
		return;
	}
	
	$monster = RPGMonsters::getMonster($monster_id);
	if(!$monster) {
		echo 'Aucun monstre trouvé avec cet id';
		return;
	}
	
	$behaviors = $monster->getBehaviors();
	
	$t->assign_vars(array(
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
		
		'ATTACK_CHECKED'	=> (in_array('attack', $behaviors)) ? 'checked' : '',
		'SKILL_CHECKED'		=> (in_array('skill', $behaviors)) ? 'checked' : '',
		'DEFEND_CHECKED'	=> (in_array('defend', $behaviors)) ? 'checked' : '',
	));
}


$t->pparse('monster_form');

?>