<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/status_functions.' . $phpEx);
include_once('./template/template.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

if(!isset($_GET['slot'])) { echo 'error'; return; }

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

$slot = request_var('slot', '');
if($slot === '') { echo 'error'; return; }
if($slot < 1 or $slot > 4) { echo 'error'; return; }

//echo get_skill_menu_html($slot);
	
	

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('status_skills_menu' => 'status_skills_menu.tpl'));	
	
	
global $_SKILLS_DATA;

$t->assign_vars(array(
	'SLOT'	=>	$slot,
));

foreach($_SKILLS_DATA as $skill => $data) {
	if($data['kind'] == SKILL_KIND_PHYSICAL) {
		$t->assign_block_vars('skills_physical_bloc', array(
			'NAME'	=>	$data['name'],
			'DESC'	=>	$data['desc'],
			'PF'	=>	$data['pf'],
			'CD'	=>	$data['cooldown'],
			'TYPE'	=>	$skill,
		));
	}
	else if($data['kind'] == SKILL_KIND_MAGICAL) {
		$t->assign_block_vars('skills_magical_bloc', array(
			'NAME'	=>	$data['name'],
			'DESC'	=>	$data['desc'],
			'PF'	=>	$data['pf'],
			'CD'	=>	$data['cooldown'],
			'TYPE'	=>	$skill,
		));
	}
	else if($data['kind'] == SKILL_KIND_BUFF) {
		$t->assign_block_vars('skills_buff_bloc', array(
			'NAME'	=>	$data['name'],
			'DESC'	=>	$data['desc'],
			'PF'	=>	$data['pf'],
			'CD'	=>	$data['cooldown'],
			'TYPE'	=>	$skill,
		));
	}
	else if($data['kind'] == SKILL_KIND_HEAL) {
		$t->assign_block_vars('skills_heal_bloc', array(
			'NAME'	=>	$data['name'],
			'DESC'	=>	$data['desc'],
			'PF'	=>	$data['pf'],
			'CD'	=>	$data['cooldown'],
			'TYPE'	=>	$skill,
		));
	}
	else if($data['kind'] == SKILL_KIND_HELP) {
		$t->assign_block_vars('skills_help_bloc', array(
			'NAME'	=>	$data['name'],
			'DESC'	=>	$data['desc'],
			'PF'	=>	$data['pf'],
			'CD'	=>	$data['cooldown'],
			'TYPE'	=>	$skill,
		));
	}
	
}

$t->pparse('status_skills_menu');

?>