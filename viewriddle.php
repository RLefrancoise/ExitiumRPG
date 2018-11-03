<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGQuests.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$riddle_id = request_var('r', -1);
if($riddle_id == -1) {
	echo "Pas d'id d'énigme.";
	return;
}

$riddle = RPGQuests::getRiddle($riddle_id);
if(!$riddle) {
	echo "Aucune énigme trouvée.";
	return;
}

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewriddle' => 'viewriddle.tpl'));

$t->assign_vars(array(
	'RIDDLE_ID'		=> $riddle->getId(),
	'RIDDLE_NAME' 	=> $riddle->getName(),
	'RIDDLE_DESC' 	=> $riddle->getDesc(),
));

$t->pparse('viewriddle');

?>