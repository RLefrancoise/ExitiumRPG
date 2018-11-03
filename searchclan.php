<?php

//header('Content: text/plain');

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

if(isset($_GET['name'])) {
	$name = htmlspecialchars(urldecode($_GET['name']));
	
	$clans = RPGClans::searchClans($name);
	
	$t = new CustomTemplate('./rpg/tpl');
	$t->set_filenames(array('viewsearchclanlist' => 'viewsearchclanlist.tpl'));

	foreach($clans as $clan) {
		$t->assign_block_vars('clan_list', array(
			'CLAN_ID'	=> $clan->getId(),
			'CLAN_NAME'	=> $clan->getName(),
			'CLAN_LEVEL'=> $clan->getLevel(),
			'CLAN_MEMBERS_NUMBER'	=> $clan->getMembersNumber(),
		));
	}

	$t->pparse('viewsearchclanlist');
}
else
	echo 'error';
?>