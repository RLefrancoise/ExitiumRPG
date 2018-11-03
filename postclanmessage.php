<?php

header('Content: text/plain');

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
	echo "not_connected";
	die();
}

if(isset($_POST['clan']) and isset($_POST['m'])) {
	$clan_id = htmlspecialchars($_POST['clan']);
	//$message = htmlspecialchars(urldecode($_POST['m']));
	$message = request_var('m', '', true);
	$user_id = $user->data['user_id'];
	
	// check if connected user is allowed to post in the chatbox of the specified clan
	if(RPGClans::isClanMember($user_id, $clan_id)) {
		if(!RPGClans::postChatBoxMessage($clan_id, $user_id, $message)) echo 'error';
		else echo 'message_sent';
	}
	else echo 'not_allowed';
}
else
	echo 'error';
?>