<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

//get players
$sql = 'SELECT *
		FROM ' . USERS_TABLE . ' u, rpg_users_players p
		WHERE u.user_id = p.user_id';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$player = RPGUsersPlayers::getPlayerByUserId($info['user_id']);
	
	$db->sql_transaction('begin');
	
	RPGPlayers::giveDefaultItems($player);
	
	$db->sql_transaction('commit');
}

echo 'end of script';

?>