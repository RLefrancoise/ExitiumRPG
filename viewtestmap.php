<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewtestmap' => 'viewtestmap.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));



$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL);
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
/*if($player->soundEnabled()) {
	$t->assign_block_vars('background_music', array());
}*/

/*$t->assign_vars(array(
	'REST_PRICE'	=> REST_PRICE,
	'SLEEP_PRICE'	=> SLEEP_PRICE,
));*/
	
$t->pparse('viewtestmap');

?>