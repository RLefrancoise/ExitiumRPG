<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGEventBattles.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/string_functions.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "Vous n'êtes pas connecté.";
	die();
}

$token = request_var('t', '');
if($token == '') { echo 'Aucun identifiant d\'event trouvé.'; return; }

if(!RPGEventBattles::eventExists($token)) { echo 'Cet event n\'existe pas.'; return; }

$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

if(RPGEventBattles::isRegisteredInEvent($token, $player->getId())) {
	echo 'Vous êtes déjà inscrit à cet event.';
	return;
}

if(!RPGEventBattles::registerPlayerInEvent($token, $player->getId())) {
	echo 'Erreur lors de l\'inscription à l\'event.';
	return;
}

echo 'Inscription effectuée.';

?>