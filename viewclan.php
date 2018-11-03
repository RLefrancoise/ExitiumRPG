<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewclan' => 'viewclan.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//play BGM ?
if($player->soundEnabled()) {
	$t->assign_block_vars('background_music', array());
}

//if player has a clan
if($player->getClan() !== null) {
	$t->assign_block_vars('create_button_disabled_bloc', array());
	
	$t->assign_block_vars('see_button_enabled_bloc', array(
		'CLAN_ID'	=> $player->getClan()->getId(),
		'CLAN_LINK'	=> append_sid("{$phpbb_root_path}viewclanpage.$phpEx", "id={$player->getClan()->getId()}"),
	));
}
//if player has no clan
else {
	$t->assign_block_vars('create_button_enabled_bloc', array());
	$t->assign_block_vars('see_button_disabled_bloc', array());
}



$t->pparse('viewclan');

?>