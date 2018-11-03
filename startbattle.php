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
include_once('./rpg/database/RPGBattleAreas.class.php');
include_once('./rpg/database/RPGPVEBattles.class.php');
include_once('./rpg/database/RPGPVPBattles.class.php');
include_once('./rpg/database/RPGEventBattles.class.php');
include_once('./rpg/database/RPGQuests.class.php');
include_once('./rpg/database/RPGKarmaTopics.class.php');
include_once('./rpg/database/RPGMonsterBooks.class.php');
include_once('./rpg/php/battle_functions.php');
include_once('./rpg/php/numeric_functions.php');
include_once('./rpg/php/post_functions.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

// get mode
$mode = request_var('mode', '');
if($mode === '') { echo 'Mode non valide.'; die(); }



switch($mode) {
	case 'delete_pve':
		{
			$battle = RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($user->data['user_id'])->getId());
			if(!$battle) {
				echo 'Pas de combat à annuler.';
				die();
			}
			
			$exp = get_monster_experience(RPGMonsters::getMonster($battle->getOpponentId()), $player->getLevel(), $player->getKarma());
			if(!player_give_exp($player, -1 * $exp)) { echo 'Erreur lors de la perte d\'XP.'; die(); }
			if(!RPGPVEBattles::deleteBattle($battle->getToken())) { echo 'Erreur lors de la suppression du combat.'; die(); }
			
			$a = request_var('a', -1);
			if($a === -1) { echo 'Aucune zone définie.'; die(); }
			$p = request_var('p', -1);
			if($p === -1) { echo 'Aucune partie de zone définie.'; die(); }
			$r = request_var('r', '');
			
			if($r == 'pve')
				redirect(append_sid("{$phpbb_root_path}startbattle.$phpEx", "mode=pve&a=$a&p=$p"));
			else if($r == 'pvp')
				redirect(append_sid("{$phpbb_root_path}startbattle.$phpEx", "mode=pvp"));
		}
		break;
		
	case 'delete_pvp':
		{
			//$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			$token = request_var('t', '');
			if($token == '') { echo 'error'; return; }
			
			$request = RPGPVPBattles::getPVPRequest($token);
			if(!$request) {
				echo 'Vous tentez d\'accéder à un combat qui n\'existe pas.';
				die();
			}
			
			if($user->data['user_id'] != $request['user2_id']) {
				echo "Vous n'êtes pas autorisé à refuser ce combat.";
				die();
			}
			
			if($request['approved']) {
				echo "Vous ne pouvez pas refuser un combat après l'avoir commencé.";
				die();
			}
			
			$battle = RPGPVPBattles::getBattle($request['battle_token']);
			if(!$battle) { echo 'no_battle'; return; }
			
			
			
			if(!RPGPVPBattles::deleteBattle($battle->getToken())) { echo 'Erreur lors de la suppression du combat.'; die(); }
			
			$subject = 'Demande de PVP';
			$text = "Le joueur {$user->data['username']} a refusé la demande en combat PVP.";
						
			rpg_post($subject, $text, 'reply', $request['forum_id'], $request['topic_id']);
			
			$to = array('u' => array( $request['user1_id'] => 'to'));
			rpg_pm('Défi PVP', "Votre demande de défi PVP a été refusée. [url=viewtopic.php?f={$request['forum_id']}&amp;t={$request['topic_id']}]Voir le topic[/url]", $to);
			
			echo 'Le combat a été annulé.';
		}
		break;
		
	case 'pve':
		{
			//player can battle ?
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($player->getPV() <= 0) { echo 'Vous n\'avez plus de points de vie. Vous ne pouvez pas combattre.'; return; }

			//enough energy ?
			if($player->getEnergy() <= 0) { echo "Vous n'avez plus d'énergie. Vous ne pouvez plus combattre."; return; }
			
			$a = request_var('a', -1);
			if($a === -1) { echo 'Aucune zone définie.'; die(); }
			$p = request_var('p', -1);
			if($p === -1) { echo 'Aucune partie de zone définie.'; die(); }
			$area = RPGBattleAreas::getAreaById($a);
			if(!$area) { echo 'Pas de zone trouvée'; die(); }
			$area_part = $area->getAreaPartById($p);
			if(!$area_part) { echo 'Pas de partie de zone trouvée.'; die(); };

			// BATTLE AREA
			//check if player is already in battle
			//---PVE
			$battle = RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($user->data['user_id'])->getId());
			if($battle) {
				$exp = get_monster_experience(RPGMonsters::getMonster($battle->getOpponentId()), $player);
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pve") . "\">ici</a> pour reprendre le combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}startbattle.php", "mode=delete_pve&a=$a&p=$p&r=pve") . "\">ici</a> pour abandonner le combat (Vous perdrez $exp XP).";
				die();
			}
			//---PVP
			$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
			if($battle and $battle->isStarted()) {
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pvp") . "\">ici</a> pour reprendre le combat.";
				die();
			}
			
			// OPPONENT
			$monsters = $area_part->getMonsters();
			$encounter_rates = $area_part->getEncounterRates();
			
			$monsters_array = array();
			for($i = 0 ; $i < count($monsters) ; $i++) {
				$rate = $encounter_rates[$i];
				
				$monsters_array[$rate][] = $monsters[$i];
			}
			
			ksort($monsters_array, SORT_NUMERIC);
			
			//select lowest encounter below rate
			//$rate = mt_rand(1, 100);
			do {
				$rate = rand_float() * 100;
			} while($rate == 0);
			
			$monsters_list = array();
			foreach($monsters_array as $r => $m) {
				if($rate <= $r) { $monsters_list = $m; break; }
			}
			
			if(count($monsters_list) === 0) { echo 'Aucun monstre trouvé.'; return; }
			
			//select monster among the list
			$choice = mt_rand(0, count($monsters_list) - 1);
			$monster = $monsters_list[$choice];
			
			// BGM
			$bgm = "";
			if($monster->getBGM()) $bgm = $monster->getBGM();
			else if($area->getBGM()) $bgm = $area->getBGM();
			
			echo 'Création du combat...';
			
			$db->sql_transaction('begin');
			
			// create battle
			
			$token = RPGPVEBattles::createBattle($player->getId(), $monster->getId(), $monster->getPV(), $monster->getPF(), $bgm, $area->getBackground(), $p);
			if(!$token) { echo 'Erreur lors de la création du combat.'; return; }
			
			//decrease energy
			/*if(!in_array($user->data['user_id'], $UNLIMITED_USERS)) {
				if(!RPGPlayers::setEnergyOfPlayer($player, $player->getEnergy() - 1)) { echo "Erreur lors du décrément d'énergie."; return; }
			}*/
			
			if(!RPGPlayersStats::incrementStatByPlayer($player, 'pve_total_battles')) {
				echo 'Erreur lors de l\'incrément du compteur de combats pve.';
				return;
			}
			
			//monster book
			$mb = RPGMonsterBooks::getMonsterBook($player->getId());
			$monster_stats = $mb->getMonsterStats($monster->getId(), $p);
			
			if(!$monster_stats) {
				RPGMonsterBooks::addEntry(array(
					'player_id'		=>	$player->getId(),
					'monster_id'	=>	$monster->getId(),
					'area_part_id'	=>	$p,
					'encounters'	=>	1,
				));
			} else {
				RPGMonsterBooks::updateEntry($player->getId(), $monster->getId(), $p, array(
					'encounters'	=>	$monster_stats['encounters'] + 1,
				));
			}
			
			$db->sql_transaction('commit');
			
			//redirect to battle page
			redirect(append_sid("{$phpbb_root_path}viewbattle.$phpEx", "mode=pve"));
		}
		break;
	
	case 'event':
		{
			//player can battle ?
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($player->getPV() <= 0) { echo 'Vous n\'avez plus de points de vie. Vous ne pouvez pas combattre.'; return; }
			
			//check if player is already in battle
			//---PVE
			$battle = RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($user->data['user_id'])->getId());
			if($battle) {
				$exp = get_monster_experience(RPGMonsters::getMonster($battle->getOpponentId()), $player);
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pve") . "\">ici</a> pour reprendre le combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}startbattle.php", "mode=delete_pve&a=$a&p=$p&r=pve") . "\">ici</a> pour abandonner le combat (Vous perdrez $exp XP).";
				die();
			}
			//---PVP
			$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
			if($battle and $battle->isStarted()) {
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pvp") . "\">ici</a> pour reprendre le combat.";
				die();
			}
			
			//if not in battle, go to event
			
			$event_token = request_var('t', '');
			if($event_token == '') { echo 'Pas de token event.'; die(); }
			if(!RPGEventBattles::eventExists($event_token)) { echo 'Event invalide.'; die(); }
			$event_data = RPGEventBattles::getEventGeneralData($event_token);
			
			//if monster is dead, event is over
			if($event_data['monster_hp'] <= 0) {
				if(!RPGEventBattles::manageEventEnding($event_token, $event_data['forum_id'], $event_data['topic_id'])
				or !RPGEventBattles::deleteEvent($event_token)) {
					echo 'Erreur lors de la fin de l\'event.';
					return;
				}
				echo "L'event est terminé. Les résultats seront affichés sur le topic.";
				return;
			}
			
			//if player is not already in event
			if(!RPGEventBattles::isInEvent($event_token, $player->getId())) {
				if(!RPGEventBattles::putPlayerInEvent($player->getId(), $event_token)) { echo 'Erreur lors de l\'ajout du joueur dans l\'event.'; die(); }
			}
			
			$event = RPGEventBattles::getEvent($event_token, $player->getId());
			if(!$event) { echo "Event introuvable pour le joueur {$player->getId()}."; return; }
			
			if($event->playerIsDead()) {
				echo 'Vous ne pouvez plus participer à l\'event car vous avez été tué.';
				return;
			}
			
			if(!RPGEventBattles::isRegisteredInEvent($event_token, $player->getId())) {
				echo 'Vous ne pouvez pas participer à cet event car vous n\'êtes pas inscrit.';
				return;
			}
			
			redirect(append_sid("{$phpbb_root_path}viewbattle.$phpEx", "mode=event&t=$event_token"));
		}
		break;
		
	/* Quest battle */
	case 'quest':
		{
			//player can battle ?
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($player->getPV() <= 0) { echo 'Vous n\'avez plus de points de vie. Vous ne pouvez pas combattre.'; return; }
			
			//check if player is already in battle
			//---PVE
			$battle = RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($user->data['user_id'])->getId());
			if($battle) {
				$exp = get_monster_experience(RPGMonsters::getMonster($battle->getOpponentId()), $player);
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pve") . "\">ici</a> pour reprendre le combat.<br>";
				die();
			}
			//---PVP
			$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
			if($battle and $battle->isStarted()) {
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pvp") . "\">ici</a> pour reprendre le combat.";
				die();
			}
			//--- CHECK EVENT HERE
			if($player->isInEvent()) {
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=event") . "\">ici</a> pour reprendre le combat.";
				die();
			}
			
			//if not in battle, go to quest battle
			
			$quest_token = request_var('t', '');
			if($quest_token == '') { echo 'Pas de token combat de quête.'; die(); }
			
			//if player is not already in quest
			if(!RPGQuests::isInBattle($quest_token, $player->getId())) {
				if(!RPGQuests::putPlayerInBattle($player->getId(), $quest_token)) { echo 'Erreur lors de l\'ajout du joueur dans le combat de quête.'; die(); }
			}
			
			//get quest battle
			$battle = RPGQuests::getQuestBattle($quest_token, $player->getId());
			if(!$battle) { echo 'Combat de quête invalide.'; die(); }
			
			//print_r($battle);
			
			$quest_over = ($battle->getMonsterHP() <= 0);
			//echo "quest_over : $quest_over"; 
			
			//if monster is dead, battle is over
			if($quest_over) {
				if(!RPGQuests::setBattleIsOver($battle, true)) {
					echo 'Erreur lors de la fin du combat de quête.';
					return;
				}
				echo "Le combat est terminé. La quête sera terminée sous peu.";
				return;
			}
			
			if($battle->playerIsDead()) {
				echo 'Vous ne pouvez plus participer au combat car vous avez été tué.';
				return;
			}
			
			$quest_data = RPGQuests::getPlayerActiveQuestData($player->getId());
			if(!$quest_data or ($quest_data['topic_id'] != $battle->getTopicId())) {
				echo 'Vous ne pouvez pas participer à ce combat car vous n\'êtes pas inscrit.';
				return;
			}
			
			redirect(append_sid("{$phpbb_root_path}viewbattle.$phpEx", "mode=quest&t=$quest_token"));
		}
		break;
		
	/* Start pvp battle */
	case 'pvp':
		{
			//player can battle ?
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($player->getPV() <= 0) { echo 'Vous n\'avez plus de points de vie. Vous ne pouvez pas combattre.'; return; }
			
			//check if player is already in battle
			//---PVE
			$battle = RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($user->data['user_id'])->getId());
			if($battle) {
				$sid = request_var('sid', '');
				$exp = get_monster_experience(RPGMonsters::getMonster($battle->getOpponentId()), $player);
				echo "Vous êtes déjà en combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pve") . "\">ici</a> pour reprendre le combat.<br>
				Cliquer <a href=\"" . append_sid("{$phpbb_root_path}startbattle.php", "mode=delete_pve&a=$a&p=$p&r=pvp") . "\">ici</a> pour abandonner le combat (Vous perdrez $exp XP).";
				die();
			}
			//---PVP
			$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
			if($battle) {
				if( ( ($player->getId() == $battle->getPlayerId()) and $battle->player1InBattle() ) or
					( ($player->getId() == $battle->getOpponentId()) and $battle->player2InBattle() ) ) {
					
					echo "Vous êtes déjà en combat.<br>
					Cliquer <a href=\"" . append_sid("{$phpbb_root_path}viewbattle.php", "mode=pvp") . "\">ici</a> pour reprendre le combat.";
					die();
				}
			}
			/*else {
				echo 'Vous tentez d\'accéder à un combat qui n\'existe pas.';
				return;
			}*/
			
			//get pvp request
			$token = request_var('t', '');
			if($token == '') {
				trigger_error('Aucun id de combat trouvé.', E_USER_ERROR);
				return;
			}
			
			$pvp_request = RPGPVPBattles::getPVPRequest($token);
			if(!$pvp_request) {
				echo 'Vous tentez d\'accéder à un combat qui n\'existe pas.';
				return;
			}
			
			//check if current user is one of the request's users
			if( ($user->data['user_id'] != $pvp_request['user1_id']) and ($user->data['user_id'] != $pvp_request['user2_id']) ) {
				echo 'Ce combat ne vous concerne pas.';
				return;
			}
			
			//current user is challenged player ?
			if($user->data['user_id'] == $pvp_request['user2_id']) {
				
				$opponent 	= RPGUsersPlayers::getPlayerByUserId($pvp_request['user1_id']);
				if($opponent->isInBattle()) {
					echo 'Votre adversaire est déjà en combat. Merci de retenter plus tard.';
					return;
				}
				
				$db->sql_transaction('begin');
				
				//approve request
				if(!$pvp_request['approved'] and !RPGPVPBattles::approvePVPRequest($token)) {
					trigger_error('Erreur lors de l\'approbation du combat.', E_USER_ERROR);
					return;
				}
				
				//put player in battle
				$battle = RPGPVPBattles::getBattle($pvp_request['battle_token']);
				if(!$battle) {
					trigger_error('La bataille associée à la requête n\'existe pas.', E_USER_ERROR);
					return;
				}
				
				if(	!$battle->player2InBattle() and 
					(	!RPGPVPBattles::setPlayerInBattle($battle->getToken(), 2, true) 
					or !RPGPlayersStats::incrementStatByPlayer($opponent, 'pvp_total_battles') ) ) {
						trigger_error('Erreur lors du marquage de l\'entrée du joueur 2 dans le combat.', E_USER_ERROR);
						return;
				} else {
					$to = array('u' => array( $pvp_request['user1_id'] => 'to'));
					rpg_pm('Acceptation du défi PVP', "Le joueur {$user->data['user_name']} a accepté votre défi et attend que vous rejoignez le combat.", $to);
				}
				
				$db->sql_transaction('commit');
			}
			
			//current user is battle initiator
			else if($user->data['user_id'] == $pvp_request['user1_id']) {
			
				$opponent 	= RPGUsersPlayers::getPlayerByUserId($pvp_request['user2_id']);
				if($opponent->isInBattle()) {
					echo 'Votre adversaire est déjà en combat. Merci de retenter plus tard.';
					return;
				}
				
				//put player in battle
				$battle = RPGPVPBattles::getBattle($pvp_request['battle_token']);
				if(!$battle) {
					trigger_error('La bataille associée à la requête n\'existe pas.', E_USER_ERROR);
					return;
				}
				
				if(!$battle->player1InBattle()
				and ( !RPGPVPBattles::setPlayerInBattle($battle->getToken(), 1, true)
					or !RPGPlayersStats::incrementStatByPlayer($player, 'pvp_total_battles') ) ) {
					trigger_error('Erreur lors du marquage de l\'entrée du joueur 1 dans le combat.', E_USER_ERROR);
					return;
				}
			}
			
			redirect(append_sid("{$phpbb_root_path}viewbattle.$phpEx", "mode=pvp"));
		}
		break;
		
	/* Display pvp opponents according to topic */
	case 'getopponents':
		{
			//player can battle ?
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($player->getPV() <= 0) { echo 'Vous n\'avez plus de points de vie. Vous ne pouvez pas combattre.'; return; }
			
			$forum_id = request_var('f', -1);
			$topic_id = request_var('t', -1);
			if($forum_id == -1) {
				trigger_error("Pas d'id de forum", E_USER_ERROR);
				return;
			}
			if($topic_id == -1) {
				trigger_error("Pas d'id de topic", E_USER_ERROR);
				return;
			}
			
			global $db;
			//check if player has posted any message on this topic
			$sql = "SELECT DISTINCT poster_id
					FROM phpbb_posts
					WHERE forum_id = $forum_id
					AND topic_id = $topic_id
					AND post_approved = 1
					AND poster_id = " . $user->data['user_id'];
			$result = $db->sql_query($sql);
			
			$info = $db->sql_fetchrow($result);
			if(!$info) {
				echo "Vous devez poster au moins un message dans ce topic pour défier un joueur.";
				return;
			}
			
			//to get players who posted on the topic, we need to get posts first
			
			
			$sql = "SELECT DISTINCT poster_id
					FROM phpbb_posts
					WHERE forum_id = $forum_id
					AND topic_id = $topic_id
					AND post_approved = 1";
			$result = $db->sql_query($sql);
			
			$user_ids = array();
			$online_users = RPGUsersPlayers::getOnlineUsers();
			
			while($info = $db->sql_fetchrow($result)) {
				//if(!array_key_exists($info['poster_id'], $online_users)) continue;
				
				if($info['poster_id'] == RPG_POST_USER_ID) continue;
				
				//if player is already in battle, no battle
				if(RPGPVPBattles::getPVPRequestByUserId($info['poster_id']) or RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($info['poster_id'])->getId())) continue;
				
				
				$opponent = RPGUsersPlayers::getPlayerByUserId($info['poster_id']);
				if($opponent->getPV() <= 0) continue;
				
				if(!array_key_exists($info['poster_id'], $user_ids) and ($info['poster_id'] != $user->data['user_id']))
					$user_ids[] = $info['poster_id'];
			}
			
			$db->sql_freeresult($result);
			
			$t = new CustomTemplate('./rpg/tpl');
			$t->set_filenames(array('viewpvpselect' => 'viewpvpselect.tpl'));
			
			$t->assign_vars(array(
					'FORUM_ID'	=> $forum_id,
					'TOPIC_ID'	=> $topic_id,
				));
				
			foreach($user_ids as $uid) {
				$data = RPGUsersPlayers::getUserData($uid);
				if(!$data) continue;
				
				$t->assign_block_vars('opponent_bloc', array(
					'ID'	=> $uid,
					'NAME'	=> $data['username'],
				));
			}
			
			$t->pparse('viewpvpselect');
			
		}
		break;
		
	/* Request pvp with another player */
	case 'pvp_request':
		{
			//player can battle ?
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($player->getPV() <= 0) { echo 'Vous n\'avez plus de points de vie. Vous ne pouvez pas combattre.'; return; }
			
			//check if player is already in battle
			$battle = RPGPVEBattles::getBattleByPlayerId(RPGUsersPlayers::getPlayerByUserId($user->data['user_id'])->getId());
			if($battle) { echo 'Vous êtes déjà en combat. Merci de le terminer avant de défier un joueur.'; return; }
			
			//check if player hasn't any other requests
			if(RPGPVPBattles::getPVPRequestByUserId($user->data['user_id'])) {
				echo 'Vous avez déjà effectué une demande de PVP.';
				return;
			}
			
			//get request data
			$opponent_id = request_var('opponent_id', -1);
			if($opponent_id == -1) { echo 'Aucun adversaire choisi.'; return; }
			
			//if opponent id is same as own, no request
			if($opponent_id == $user->data['user_id']) {
				echo 'Vous ne pouvez pas vous défier.';
				return;
			}
			
			$opponent_data = RPGUsersPlayers::getUserData($opponent_id);
			if(!$opponent_data) {
				trigger_error("Aucune donnée trouvée pour cet adversaire.", E_USER_ERROR);
				return;
			}
			
			$opponent_player = RPGUsersPlayers::getPlayerByUserId($opponent_id);
			if(!$opponent_player) {
				trigger_error('Les données RPG de l\'adversaire n\'ont pas été trouvées.', E_USER_ERROR);
				return;
			}
			
			//look if opponent is already in battle
			if(RPGPVPBattles::getPVPRequestByUserId($opponent_id) or RPGPVEBattles::getBattleByPlayerId($opponent_player->getId())) {
				echo 'Votre adversaire est déjà en combat';
				return;
			}
			
			$forum_id = request_var('f', -1);
			$topic_id = request_var('t', -1);
			if($forum_id == -1) {
				trigger_error("Pas d'id de forum", E_USER_ERROR);
				return;
			}
			if($topic_id == -1) {
				trigger_error("Pas d'id de topic", E_USER_ERROR);
				return;
			}
			
			//check if pvp is allowed on this forum
			if(!RPGPVPBattles::forumIsPVP($forum_id)) {
				echo "Le PVP n'est pas disponible sur ce forum.";
				return;
			}
			
			if(RPGKarmaTopics::getNumberOfPostsInTopic($forum_id, $topic_id, $user->data['user_id']) <= 0) {
				echo "Vous devez avoir posté au moins un message avant de défier quelqu'un.";
				return;
			}
			
			$db->sql_transaction('begin');
			
			//create request
			$token = RPGPVPBattles::createPVPRequest($user->data['user_id'], $opponent_id, $forum_id, $topic_id);
			if(!$token) {
				trigger_error("Erreur lors de la création de la requête.", E_USER_ERROR);
				return;
			}
			
			//create battle
			$battle_token = RPGPVPBattles::createBattle($player->getId(), $opponent_player->getId(), $player->getPV(), $opponent_player->getPV(), $player->getPF(), $opponent_player->getPF(), $player->getBGM(), $opponent_player->getBGM());
			if(!$battle_token) {
				trigger_error('Erreur lors de la création du combat.', E_USER_ERROR);
				return;
			}
			
			if(!RPGPVPBattles::setBattleToRequest($token, $battle_token)) {
				trigger_error('Erreur lors de l\'assignation du combat à la requête.', E_USER_ERROR);
				return;
			}
			
			$db->sql_transaction('commit');
			
			//post message on topic
			/*$sid = request_var('sid', '');
			
			$subject = utf8_normalize_nfc('Demande de PVP');
			$text	 = "Le joueur {$user->data['username']} défie le joueur {$opponent_data['username']}.
						Les deux joueurs sont invités à cliquer sur ce lien pour démarrer le combat : [pvp]" . $token . "[/pvp]";
			
			$text    = utf8_normalize_nfc($text);

			// variables to hold the parameters for submit_post
			$poll = $uid = $bitfield = $options = ''; 

			generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
			generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

			$data = array( 
				'forum_id'      => $forum_id,
				'topic_id'		=> $topic_id,
				'icon_id'       => false,

				'enable_bbcode'     => true,
				'enable_smilies'    => true,
				'enable_urls'       => true,
				'enable_sig'        => true,

				'message'      		=> $text,
				'message_md5'   	=> md5($text),
							
				'bbcode_bitfield'   => $bitfield,
				'bbcode_uid'        => $uid,

				'post_edit_locked'  => 1,
				'topic_title'       => $subject,
				'notify_set'        => false,
				'notify'            => false,
				'post_time'         => 0,
				'forum_name'        => '',
				'enable_indexing'   => true,
			);

			submit_post('reply', $subject, '', POST_NORMAL, $poll, $data);*/
			
			$subject = 'Demande de PVP';
			$text = "Le joueur {$user->data['username']} défie le joueur {$opponent_data['username']}.
					Les deux joueurs sont invités à cliquer sur ce lien pour démarrer le combat : [pvp]" . $token . "[/pvp].
					{$opponent_data['username']} peut refuser le combat via ce lien : [cancelpvp]" . $token . "[/cancelpvp]";
						
			rpg_post($subject, $text, 'reply', $forum_id, $topic_id);
			
			//send MP to challenged player
			$to = array('u' => array( $opponent_data['user_id'] => 'to'));
			rpg_pm("Défi PVP", "Vous avez reçu un défi PVP de la part de {$user->data['username']} sur [url=viewtopic.php?f={$forum_id}&amp;t={$topic_id}]ce topic[/url].", $to);
			
			echo 'Le lien vers le combat a été posté sur le topic.';
		}
		break;
}



?>