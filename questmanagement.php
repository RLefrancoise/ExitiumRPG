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
include_once($phpbb_root_path . 'rpg/php/post_functions.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "Vous n'êtes pas connecté.";
	die();
}

$mode = request_var('mode', '');
if($mode == '') {
	echo 'Aucun mode choisi';
	return;
}

switch($mode) {
	case 'register':
	{
		$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
		if(!$player) {
			echo "Pas de données de joueur trouvé.";
			return;
		}

		// check if player has quest ?
		if(RPGQuests::playerHasQuest($player->getId())) {
			echo "Vous avez déjà une quête en cours.";
			return;
		}
		
		$topic_id = request_var('t', -1);
		if($topic_id == -1) {
			echo "Pas d'id de quête.";
		}

		//is topic id a quest topic ?
		$is_quest = false;
		$quests = RPGQuests::getActiveQuestsData();
		foreach($quests as $quest) {
			if($quest['topic_id'] == $topic_id) { $is_quest = true; break; }
		}
		
		if(!$is_quest) {
			echo "Ce topic n'est pas un topic de quête.";
			return;
		}
		
		//is quest opened ?
		$quest_data = RPGQuests::getActiveQuestDataByTopicId($topic_id);
		
		if(!$quest_data['is_opened']) {
			echo "Les inscriptions sont fermées pour cette quête.";
			return;
		}
		
		// add player to quest's players
		if(!RPGQuests::registerPlayerToQuest($topic_id, $player->getId())) {
			echo "Erreur de l'inscription à la quête.";
		}
		
		//post message on topic
		rpg_post("Inscription à la quête", "Le joueur {$player->getName()} s'est inscrit à la quête.", 'reply', $quest_data['forum_id'], $topic_id);
	
		echo "Inscription réussie.";
		break;
	}
	
	case 'close':
	{
		$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
		if(!$player) {
			echo "Pas de données de joueur trouvé.";
			return;
		}
		
		$topic_id = request_var('t', -1);
		if($topic_id == -1) {
			echo "Pas d'id de quête.";
		}

		//is topic id a quest topic ?
		$is_quest = false;
		$quests = RPGQuests::getActiveQuestsData();
		foreach($quests as $quest) {
			if($quest['topic_id'] == $topic_id) { $is_quest = true; break; }
		}
		
		if(!$is_quest) {
			echo "Ce topic n'est pas un topic de quête.";
			return;
		}
		
		//is quest opened ?
		$quest_data = RPGQuests::getActiveQuestDataByTopicId($topic_id);
		
		if(!$quest_data['is_opened']) {
			echo "Les inscriptions sont déjà fermées pour cette quête.";
			return;
		}
		
		//player is the quest's owner ?
		if($quest_data['player_id'] != $player->getId()) {
			echo "Vous devez être l'initiateur de la quête pour effectuer cette opération.";
			return;
		}		
		
		//close registration
		if(!RPGQuests::closeActiveQuest($topic_id)) {
			echo "Erreur lors de la clôture des inscriptions.";
			return;
		}
		
		echo "Clôture des inscriptions réussie.";
		
		rpg_post("Inscription à la quête", "Les inscriptions de la quête ont été cloturées.", 'reply', $quest_data['forum_id'], $topic_id);
		
		break;
	}
}

?>