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

$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if(!$player) {
	echo "Pas de données de joueur trouvé.";
	return;
}

// check if player has quest ?
if(!RPGQuests::playerHasQuest($player->getId())) {
	echo "Vous n'avez pas de quête en cours.";
	return;
}

$current_quest = RPGQuests::getPlayerCurrentQuest($player->getId());
if( ($current_quest->getType() != QUEST_TYPE_RIDDLE) || (count($current_quest->getRiddles()) == 0) ) {
	echo "Cette quête ne comporte pas d'énigme.";
	return;
}

// check if riddle id is among riddles of the quest
$can_answer = false;

foreach($current_quest->getRiddles() as $r) {
	if($r->getId() == $riddle_id) { $can_answer = true; break; }
}

if(!$can_answer) {
	echo "Vous ne pouvez pas répondre à cette énigme";
	return;
}

$quest_data = RPGQuests::getPlayerActiveQuestData($player->getId());
if(!$quest_data) {
	echo "Pas de données de quêtes trouvées.";
	return;
}

$topic_id = $quest_data['topic_id'];


//check if player has already answered to this riddle
if(!RPGQuests::canAnswerRiddle($player->getId(), $topic_id)) {
	echo "Vous avez déjà répondu à cette énigme.";
	return;
}

// check if answer is the right one
$answer = request_var('riddle_answer', '');
if($answer == '') {
	echo "Vous devez saisir une réponse";
	return;
}

//set the answered riddle flag to true
if(!RPGQuests::setAnsweredRiddleFlag($player->getId(), $topic_id)) {
	echo "Erreur lors du passage à vrai du drapeau de réponse à l'énigme.";
	return;
}

if(strcmp(strtolower($riddle->getAnswer()), strtolower((string) $answer)) === 0) {
	echo "La réponse est correcte.";
	
	//post message on topic and end the quest
	rpg_post("Fin de la quête", "Le joueur {$player->getName()} a trouvé la réponse à l'énigme. La quête est terminée.", 'reply', $current_quest->getForumId(), $topic_id);

	//give rewards here
	if(!RPGQuests::manageQuestEnding($topic_id)) {
		echo "Erreur lors de la gestion de fin de la quête.";
		return;
	}
	
	//end quest
	if(!RPGQuests::endQuest($topic_id)) {
		echo "La terminaison de la quête a échouée.";
		return;
	}
	
	//lock topic
	/*$change_topic_status = ITEM_LOCKED;
	$sql = 'UPDATE ' . TOPICS_TABLE . "
			SET topic_status = $change_topic_status
			WHERE topic_id = $topic_id
			AND topic_moved_id = 0";
	$db->sql_query($sql);*/
}
else {
	echo 'La réponse est incorrecte.';
	
	//look if quest is lost
	if(RPGQuests::riddleQuestIsLost($topic_id)) {
		//post message on topic and end the quest
		rpg_post("Fin de la quête", "Aucun joueur n'a trouvé la réponse à l'énigme. La quête est terminée.", 'reply', $current_quest->getForumId(), $topic_id);
	
		//end quest
		if(!RPGQuests::endQuest($topic_id)) {
			echo "La terminaison de la quête a échouée.";
			return;
		}
		
		//lock topic
		/*$change_topic_status = ITEM_LOCKED;
		$sql = 'UPDATE ' . TOPICS_TABLE . "
				SET topic_status = $change_topic_status
				WHERE topic_id = $topic_id
				AND topic_moved_id = 0";
		$db->sql_query($sql);*/
	}
}

?>