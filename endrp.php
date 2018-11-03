<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/classes/rpgconfig.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGPlayersStats.class.php');
include_once('./rpg/database/RPGKarmaTopics.class.php');
include_once('./rpg/database/RPGRPForums.class.php');
include_once('./rpg/php/lock_functions.php');
include_once('./rpg/php/post_functions.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$forum_id = request_var('f', -1);
if($forum_id == -1)  { trigger_error("Pas d'id de forum.", E_USER_ERROR); return; }
$topic_id = request_var('t', -1);
if($topic_id == -1)  { trigger_error("Pas d'id de topic.", E_USER_ERROR); return; }

//check if RP is allowed on this forum
if(!RPGRPForums::forumIsRP($forum_id)) {
	echo "Vous ne pouvez pas RP sur ce forum.";
	return;
}

//check if used has posted at least one message
if(RPGKarmaTopics::getNumberOfPostsInTopic($forum_id, $topic_id, $user->data['user_id']) == 0) {
	echo "Vous devez avoir posté au moins un message pour terminer votre RP.";
	return;
}

//if already ended, no need to
if(RPGKarmaTopics::userHasEndRP($forum_id, $topic_id, $user->data['user_id'])) { echo 'Vous avez déjà terminé ce RP.'; return; }

//end RP
$end_success = RPGKarmaTopics::endRP($forum_id, $topic_id, $user->data['user_id']);
if(!$end_success) {
	trigger_error("Erreur lors du marquage de la fin du RP.", E_USER_ERROR);
	return;
} else {
	echo 'Votre demande de fin de RP a été enregistrée.';
}



//post on topic to inform player has end RP
rpg_post('Fin du RP', "Le joueur {$user->data['username']} a terminé le RP.", 'reply', $forum_id, $topic_id);



//look if all users have ended the RP
$users = RPGKarmaTopics::getUsersIDSOfPosts($forum_id, $topic_id);

if(count($users) <= 0) return;

$all_ended = true;

foreach($users as $user_id) {
	if( ($user_id != RPG_POST_USER_ID) and !RPGKarmaTopics::userHasEndRP($forum_id, $topic_id, $user_id) ) { $all_ended = false; break; }
}



if($all_ended) {
	
	$lock = new sqlLock("endrp_{$forum_id}_{$topic_id}");
	if($lock->lock()) {
					
		foreach($users as $user_id) {
			if($user_id == RPG_POST_USER_ID) continue;
			
			//get number of messages inside topic for this user
			$posts_number = RPGKarmaTopics::getNumberOfPostsInTopic($forum_id, $topic_id, $user_id);
			
			if($posts_number >= RPG_KARMA_MIN_POSTS) {
				//give karma if needed
				$player = RPGUsersPlayers::getPlayerByUserId($user_id);
				if(!$player) { trigger_error("Pas de données RPG trouvés pour le joueur $user_id", E_USER_ERROR); return; }
				
				if($player->getKarma() < MAX_KARMA) {
					$db->sql_transaction('begin');
					
					if(!RPGPlayers::setKarmaOfPlayer($player, $player->getKarma() + 1)) { trigger_error("Erreur lors du don de karma", E_USER_ERROR); return; }
					if(!RPGPlayersStats::incrementStatByPlayer($player, 'karma_points')) { echo 'error'; return; }
					
					$db->sql_transaction('commit');
					
					//send pm to player to notice him of his karma change
					if(!rpg_pm('Don de Karma', 'Vous avez gagné un point de karma suite à votre RP.', array('u' => array($user_id => 'to')))) { trigger_error("Erreur lors du l'envoi du MP pour le don de karma", E_USER_ERROR); return; }
				}
				
			}
		}
		
		//post on topic to inform of the end of rp
		rpg_post('Fin du RP', "Tous les joueurs ont mis fin au RP. Chaque joueur va recevoir un point de karma s'il a posté au moins " . RPG_KARMA_MIN_POSTS . " messages.", 'reply', $forum_id, $topic_id);
		
		//lock topic
		$change_topic_status = ITEM_LOCKED;
		$sql = 'UPDATE ' . TOPICS_TABLE . "
				SET topic_status = $change_topic_status
				WHERE topic_id = $topic_id
				AND topic_moved_id = 0";
		$db->sql_query($sql);
		
		$lock->release();
	}
}

?>