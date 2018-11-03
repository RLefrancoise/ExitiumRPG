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
$user->setup();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewclanmessages' => 'viewclanmessages.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
));

$clan_id = request_var('id', '');
if($clan_id == '') {
	die();
}

if(RPGClans::isClanMember($user->data['user_id'], $clan_id)) {
	$messages = RPGClans::getMessagesOfClan($clan_id);
	
	foreach($messages as $message) {
		
		$t->assign_block_vars('messages_bloc', array(
			'USER_NAME'	=> $message['user_name'],
			'DATE'		=> $user->format_date($message['date'], 'd/m/Y H:i:s'),
			'TEXT'		=> $message['text'],
		));
	}
}

$t->pparse('viewclanmessages');

?>