<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGXP.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

$mode = request_var('mode', '');
if($mode == 'update') {
	if($user->data['username'] == "Anonymous") {
		echo "not_connected";
		die();
	}
	
	$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL | PLAYER_INVENTORY | PLAYER_ORBS | PLAYER_EQUIPMENT);
	
	$data = array();
	$data['hp'] = $player->getPV();
	$data['maxhp'] = $player->getMaxPV();
	$data['fp'] = $player->getPF();
	$data['maxfp'] = $player->getMaxPF();
	$data['xp'] = ($player->getLevel() < MAX_LEVEL ? $player->getXP() : 0);
	$data['maxxp'] = ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : 0);
	$data['energy'] = $player->getEnergy();
	$data['maxenergy'] = MAX_ENERGY + $player->getMaxEnergyBonus();
	$data['honor'] = $player->getHonor();
	$data['ralz'] = $player->getRalz();
	
	echo json_encode($data);
	return;
}


if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewrpgmenu' => 'viewrpgmenu.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
));

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

$t->assign_vars(array(
	'USER_HP'		=> $player->getPV(),
	'USER_MAX_HP'	=> $player->getMaxPV(), // max pv + bonus
	'USER_FP'		=> $player->getPF(),
	'USER_MAX_FP'	=> $player->getMaxPF(), // max pf + bonus
	'USER_XP'		=> ($player->getLevel() < MAX_LEVEL ? $player->getXP() : 0),
	'USER_MAX_XP'	=> ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : 0),
	'USER_ENERGY'	=> $player->getEnergy(),
	'USER_MAX_ENERGY' => MAX_ENERGY + $player->getMaxEnergyBonus(),
	'USER_HONOR'	=> $player->getHonor(),
	'USER_RALZ'		=> $player->getRalz(),
));
	
$t->pparse('viewrpgmenu');

?>