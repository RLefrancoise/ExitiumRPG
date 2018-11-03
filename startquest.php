<?php
 
//header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGQuests.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/string_functions.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "Vous n'êtes pas connecté.";
	die();
}

$quest_id = request_var('q', -1);
if($quest_id == -1) {
	echo "Pas d'id de quête.";
	return;
}

$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

// player has quest ?
if(RPGQuests::playerHasQuest($player->getId())) {
	echo "Vous avez déjà une quête en cours. Terminez-la avant de démarrer une nouvelle quête.";
	return;
}

//quest available ?
$quest = RPGQuests::getQuest($quest_id);
if(!$quest) {
	echo "Cette quête n'existe pas.";
	return;
}

if(!$quest->isAvailable()) {
	echo "Cette quête n'est pas disponible.";
	return;
}

// check if unique quest can be started
if($quest->isUnique()) {
	if(RPGQuests::questIsPlayed($quest_id)) {
		echo "Cette quête est unique et a déjà été lancée.";
		return;
	}
}


if(!RPGQuests::startQuest($quest_id, $player->getId())) {
	echo 'Erreur lors du lancement de la quête.';
	return;
}

echo 'Quête lancée.';

?>