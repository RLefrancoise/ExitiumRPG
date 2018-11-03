<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGClans.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewsearchclan' => 'viewsearchclan.tpl'));

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
));

$clans = RPGClans::getClans();

foreach($clans as $clan) {
	$t->assign_block_vars('clan_list', array(
		'CLAN_ID'	=> $clan->getId(),
		'CLAN_NAME'	=> $clan->getName(),
		'CLAN_LEVEL'=> $clan->getLevel(),
		'CLAN_MEMBERS_NUMBER'	=> $clan->getMembersNumber(),
	));
}


$t->pparse('viewsearchclan');

?>