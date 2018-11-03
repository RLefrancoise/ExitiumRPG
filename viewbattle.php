<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGBattleAreas.class.php');
include_once('./rpg/database/RPGPVEBattles.class.php');
include_once('./rpg/database/RPGPVPBattles.class.php');
include_once('./rpg/database/RPGEventBattles.class.php');
include_once('./rpg/database/RPGQuests.class.php');
include_once('./rpg/database/RPGXP.class.php');

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

// PLAYER
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);



switch($mode) {
	case 'pve':
		{
			$battle = RPGPVEBattles::getBattleByPlayerId($player->getId());
			if(!$battle) { echo 'Vous n\'êtes pas en combat.'; die(); }
			
			// TEMPLATE
			$t = new CustomTemplate('./rpg/tpl');
			$t->set_filenames(array('viewbattle' => 'viewbattle.tpl'));
			
			//HD
			$t->assign_vars(array(
				'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));

			$t->assign_vars(array(
				//session
				'SID'	=> request_var('sid', ''),
				//javascript
				'JAVASCRIPT_FILE'	=> 'pve',
			));

			//GUI
			$t->assign_block_vars('items_allowed_bloc', array());
			$t->assign_block_vars('run_allowed_bloc', array());
			$t->assign_block_vars('pve_counter_bloc', array());
			
			//player data
			$t->assign_vars(array(
				
				/* character info */
				'USER_AVATAR' 	=> "./download/file.php?avatar=" . $user->data['user_avatar'],
				'USER_LEVEL'	=> $player->getLevel(),
				
				'USER_ATTACK'	=> $player->getAttack(),
				'USER_DEFENSE'	=> $player->getDefense(),
				'USER_SPEED'	=> $player->getSpeed(),
				'USER_FLUX'		=> $player->getFlux(),
				'USER_RESISTANCE'	=> $player->getResistance(),
				'USER_POINTS'	=> $player->getPoints(),
				
				'USER_HP'		=> $player->getPV(),
				'USER_MAX_HP'	=> $player->getMaxPV(), // max pv + bonus
				'USER_FP'		=> $player->getPF(),
				'USER_MAX_FP'	=> $player->getMaxPF(), // max pf + bonus
				'USER_XP'		=> ($player->getLevel() < MAX_LEVEL ? $player->getXP() : '---'),
				'USER_MAX_XP'	=> ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : '---'),
			));

			//orbs
			for($i = 1 ; $i <= 4 ; $i++) {
				$orb = $player->getOrb($i);
				if($orb != null) {
					$orb_desc = $orb->getFullDescription();
					$tooltip = 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="'.$orb_desc.'"';
					$orb_img = "images/rpg/icons/" . $orb->getIcon();
					
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> $tooltip,
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> $orb_img,
					));
				} else {
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> '',
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> 'images/rpg/icons/OrbeVIDE.png',
					));
				}
			}
			
			//opponent data
			$monster = RPGMonsters::getMonster($battle->getOpponentId());
			$t->assign_vars(array(
				'OPPONENT_NAME'		=> $monster->getName(),
				'OPPONENT_BACKGROUND' => 'images/rpg/battle/backgrounds/' . $battle->getBackground(),
				'OPPONENT_AVATAR' 	=> ($monster->getImage() !== "" ? 'images/rpg/battle/monsters/' . $monster->getImage() : ''),
				'OPPONENT_INFO'		=> "Niveau : {$monster->getLevel()}<br>Attaque : {$monster->getAttack()}<br>Défense : {$monster->getDefense()}<br>Résistance : {$monster->getResistance()}<br>Vitesse : {$monster->getSpeed()}<br>Flux : {$monster->getFlux()}",
				'OPPONENT_HP'		=> $battle->getMonsterHP(),
				'OPPONENT_MAX_HP'	=> $monster->getMaxPV(), // max pv + bonus
				'OPPONENT_FP'		=> $battle->getMonsterFP(),
				'OPPONENT_MAX_FP'	=> $monster->getMaxPF(), // max pf + bonus
			));

			//current turn
			$t->assign_vars(array(
				'BATTLE_TURN'		=> $battle->getTurn(),
			));
			
			// BGM
			//play BGM ?
			if($player->soundEnabled()) {
				$bgm = $battle->getBGM();
				
				if($bgm) {
					$t->assign_block_vars('background_music', array(
						'BGM'	=> $bgm,
					));
				}
			}

			$t->pparse('viewbattle');
		}
		break;
	
	case 'event':
		{
			$event_token = request_var('t', '');
			if($event_token == '') { echo 'Token invalide'; die(); }
			
			$battle = RPGEventBattles::getEvent($event_token, $player->getId());
			if(!$battle) { echo 'Vous n\'êtes pas en event.'; die(); }
			
			if(!RPGEventBattles::isRegisteredInEvent($event_token, $player->getId())) {
				echo 'Vous ne pouvez pas participer à cet event car vous n\'êtes pas inscrit.';
				return;
			}
			
			//if player already dead in this event, redirect
			if($battle->playerIsDead()) redirect(append_sid("{$phpbb_root_path}startbattle.$phpEx", "mode=event&t=$event_token"));
			
			//if monster if dead, event is over
			if($battle->getMonsterHP() <= 0) redirect(append_sid("{$phpbb_root_path}startbattle.$phpEx", "mode=event&t=$event_token"));
			
			// TEMPLATE
			$t = new CustomTemplate('./rpg/tpl');
			$t->set_filenames(array('viewbattle' => 'viewbattle.tpl'));
			
			//HD
			$t->assign_vars(array(
				'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));

			$t->assign_vars(array(
				//session
				'SID'	=> request_var('sid', ''),
				//javascript
				'JAVASCRIPT_FILE'	=> 'event',
			));

			//GUI
			$t->assign_block_vars('items_not_allowed_bloc', array());
			$t->assign_block_vars('run_not_allowed_bloc', array());
			$t->assign_block_vars('pve_counter_bloc', array());
			
			//player data
			$t->assign_vars(array(
				
				/* character info */
				'USER_AVATAR' 	=> "./download/file.php?avatar=" . $user->data['user_avatar'],
				'USER_LEVEL'	=> $player->getLevel(),
				
				'USER_ATTACK'	=> $player->getAttack(),
				'USER_DEFENSE'	=> $player->getDefense(),
				'USER_SPEED'	=> $player->getSpeed(),
				'USER_FLUX'		=> $player->getFlux(),
				'USER_RESISTANCE'	=> $player->getResistance(),
				'USER_POINTS'	=> $player->getPoints(),
				
				'USER_HP'		=> $player->getPV(),
				'USER_MAX_HP'	=> $player->getMaxPV(), // max pv + bonus
				'USER_FP'		=> $player->getPF(),
				'USER_MAX_FP'	=> $player->getMaxPF(), // max pf + bonus
				'USER_XP'		=> ($player->getLevel() < MAX_LEVEL ? $player->getXP() : '---'),
				'USER_MAX_XP'	=> ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : '---'),
			));

			//orbs
			for($i = 1 ; $i <= 4 ; $i++) {
				$orb = $player->getOrb($i);
				if($orb != null) {
					$orb_desc = $orb->getFullDescription();
					$tooltip = 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="'.$orb_desc.'"';
					$orb_img = "images/rpg/icons/" . $orb->getIcon();
					
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> $tooltip,
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> $orb_img,
					));
				} else {
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> '',
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> 'images/rpg/icons/OrbeVIDE.png',
					));
				}
			}
			
			//opponent data
			$monster = RPGMonsters::getMonster($battle->getOpponentId());
			$t->assign_vars(array(
				'OPPONENT_NAME'		=> $monster->getName(),
				'OPPONENT_BACKGROUND' => 'images/rpg/battle/backgrounds/' . $battle->getBackground(),
				'OPPONENT_AVATAR' 	=> ($monster->getImage() !== "" ? 'images/rpg/battle/monsters/' . $monster->getImage() : ''),
				'OPPONENT_INFO'		=> "Niveau : {$monster->getLevel()}<br>Attaque : {$monster->getAttack()}<br>Défense : {$monster->getDefense()}<br>Résistance : {$monster->getResistance()}<br>Vitesse : {$monster->getSpeed()}<br>Flux : {$monster->getFlux()}",
				'OPPONENT_HP'		=> $battle->getMonsterHP(),
				'OPPONENT_MAX_HP'	=> $monster->getMaxPV(), // max pv + bonus
				'OPPONENT_FP'		=> $battle->getMonsterFP(),
				'OPPONENT_MAX_FP'	=> $monster->getMaxPF(), // max pf + bonus
			));

			//current turn
			$t->assign_vars(array(
				'BATTLE_TURN'		=> $battle->getTurn(),
			));
			
			// BGM
			//play BGM ?
			if($player->soundEnabled()) {
				$bgm = $battle->getBGM();
				
				if($bgm) {
					$t->assign_block_vars('background_music', array(
						'BGM'	=> $bgm,
					));
				}
			}

			$t->pparse('viewbattle');
		}
		break;
		
	case 'quest':
		{
			$quest_token = request_var('t', '');
			if($quest_token == '') { echo 'Token invalide'; die(); }
			
			$battle = RPGQuests::getQuestBattle($quest_token, $player->getId());
			if(!$battle) { echo 'Vous n\'êtes pas en quête.'; die(); }
			
			/*if(!RPGEventBattles::isRegisteredInEvent($event_token, $player->getId())) {
				echo 'Vous ne pouvez pas participer à cet event car vous n\'êtes pas inscrit.';
				return;
			}*/
			
			//if player already dead in this quest, redirect
			if($battle->playerIsDead()) redirect(append_sid("{$phpbb_root_path}startbattle.$phpEx", "mode=quest&t=$quest_token"));
			
			//if monster if dead, battle is over
			if($battle->getMonsterHP() <= 0) redirect(append_sid("{$phpbb_root_path}startbattle.$phpEx", "mode=quest&t=$quest_token"));
			
			// TEMPLATE
			$t = new CustomTemplate('./rpg/tpl');
			$t->set_filenames(array('viewbattle' => 'viewbattle.tpl'));
			
			//HD
			$t->assign_vars(array(
				'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));

			$t->assign_vars(array(
				//session
				'SID'	=> request_var('sid', ''),
				//javascript
				'JAVASCRIPT_FILE'	=> 'quest',
			));

			//GUI
			$t->assign_block_vars('items_not_allowed_bloc', array());
			$t->assign_block_vars('run_not_allowed_bloc', array());
			$t->assign_block_vars('pve_counter_bloc', array());
			
			//player data
			$t->assign_vars(array(
				
				/* character info */
				'USER_AVATAR' 	=> "./download/file.php?avatar=" . $user->data['user_avatar'],
				'USER_LEVEL'	=> $player->getLevel(),
				
				'USER_ATTACK'	=> $player->getAttack(),
				'USER_DEFENSE'	=> $player->getDefense(),
				'USER_SPEED'	=> $player->getSpeed(),
				'USER_FLUX'		=> $player->getFlux(),
				'USER_RESISTANCE'	=> $player->getResistance(),
				'USER_POINTS'	=> $player->getPoints(),
				
				'USER_HP'		=> $player->getPV(),
				'USER_MAX_HP'	=> $player->getMaxPV(), // max pv + bonus
				'USER_FP'		=> $player->getPF(),
				'USER_MAX_FP'	=> $player->getMaxPF(), // max pf + bonus
				'USER_XP'		=> ($player->getLevel() < MAX_LEVEL ? $player->getXP() : '---'),
				'USER_MAX_XP'	=> ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : '---'),
			));

			//orbs
			for($i = 1 ; $i <= 4 ; $i++) {
				$orb = $player->getOrb($i);
				if($orb != null) {
					$orb_desc = $orb->getFullDescription();
					$tooltip = 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="'.$orb_desc.'"';
					$orb_img = "images/rpg/icons/" . $orb->getIcon();
					
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> $tooltip,
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> $orb_img,
					));
				} else {
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> '',
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> 'images/rpg/icons/OrbeVIDE.png',
					));
				}
			}
			
			//opponent data
			$monster = RPGMonsters::getMonster($battle->getOpponentId());
			$t->assign_vars(array(
				'OPPONENT_NAME'		=> $monster->getName(),
				'OPPONENT_BACKGROUND' => 'images/rpg/battle/backgrounds/' . $battle->getBackground(),
				'OPPONENT_AVATAR' 	=> ($monster->getImage() !== "" ? 'images/rpg/battle/monsters/' . $monster->getImage() : ''),
				'OPPONENT_INFO'		=> "Niveau : {$monster->getLevel()}<br>Attaque : {$monster->getAttack()}<br>Défense : {$monster->getDefense()}<br>Résistance : {$monster->getResistance()}<br>Vitesse : {$monster->getSpeed()}<br>Flux : {$monster->getFlux()}",
				'OPPONENT_HP'		=> $battle->getMonsterHP(),
				'OPPONENT_MAX_HP'	=> $monster->getMaxPV(), // max pv + bonus
				'OPPONENT_FP'		=> $battle->getMonsterFP(),
				'OPPONENT_MAX_FP'	=> $monster->getMaxPF(), // max pf + bonus
			));

			//current turn
			$t->assign_vars(array(
				'BATTLE_TURN'		=> $battle->getTurn(),
			));
			
			// BGM
			//play BGM ?
			if($player->soundEnabled()) {
				$bgm = $battle->getBGM();
				
				if($bgm) {
					$t->assign_block_vars('background_music', array(
						'BGM'	=> $bgm,
					));
				}
			}

			$t->pparse('viewbattle');
		}
		break;
		
	case 'pvp':
		{
			$battle_request = RPGPVPBattles::getPVPRequestByUserId($user->data['user_id']);
			if(!$battle_request) { echo 'Vous n\'avez aucun combat PVP en cours.'; die(); }
			
			//get battle data
			$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
			
			// TEMPLATE
			$t = new CustomTemplate('./rpg/tpl');
			$t->set_filenames(array('viewbattle' => 'viewbattle.tpl'));
			
			//HD
			$t->assign_vars(array(
				'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));
			
			$t->assign_vars(array(
				//session
				'SID'	=> request_var('sid', ''),
				//javascript
				'JAVASCRIPT_FILE'	=> 'pvp',
			));
			
			//GUI
			$t->assign_block_vars('items_not_allowed_bloc', array());
			$t->assign_block_vars('run_not_allowed_bloc', array());
			$t->assign_block_vars('pvp_counter_bloc', array());
			
			//player data
			$t->assign_vars(array(
				
				/* character info */
				'USER_AVATAR' 	=> "./download/file.php?avatar=" . $user->data['user_avatar'],
				'USER_LEVEL'	=> $player->getLevel(),
				
				'USER_ATTACK'	=> $player->getAttack(),
				'USER_DEFENSE'	=> $player->getDefense(),
				'USER_SPEED'	=> $player->getSpeed(),
				'USER_FLUX'		=> $player->getFlux(),
				'USER_POINTS'	=> $player->getPoints(),
				
				'USER_HP'		=> $player->getPV(),
				'USER_MAX_HP'	=> $player->getMaxPV(), // max pv + bonus
				'USER_FP'		=> $player->getPF(),
				'USER_MAX_FP'	=> $player->getMaxPF(), // max pf + bonus
				'USER_XP'		=> ($player->getLevel() < MAX_LEVEL ? $player->getXP() : '---'),
				'USER_MAX_XP'	=> ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : '---'),
			));
			
			//orbs
			for($i = 1 ; $i <= 4 ; $i++) {
				$orb = $player->getOrb($i);
				if($orb != null) {
					$orb_desc = $orb->getFullDescription();
					$tooltip = 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="'.$orb_desc.'"';
					$orb_img = "images/rpg/icons/" . $orb->getIcon();
					
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> $tooltip,
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> $orb_img,
					));
				} else {
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> '',
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> 'images/rpg/icons/OrbeVIDE.png',
					));
				}
			}
			
			
			//OPPONENT
			
			//player is player1 ?
			if($player->getId() == $battle->getPlayerId()) {
				$opponent = RPGUsersPlayers::getPlayerByPlayerId($battle->getOpponentId());
			}
			//player is player2 ?
			else if($player->getId() == $battle->getOpponentId()) {
				$opponent = RPGUsersPlayers::getPlayerByPlayerId($battle->getPlayerId());
			}
			
			if(!$opponent) {
				trigger_error('Pas de données de joueur trouvées pour l\'adversaire.', E_USER_ERROR);
				return;
			}
			
			$opponent_data = RPGUsersPlayers::getUserData($opponent->getUserId());
			if(!$opponent_data) {
				trigger_error('Pas de données trouvées pour l\'adversaire.', E_USER_ERROR);
				return;
			}
			
			//opponent data
			$t->assign_vars(array(
				'OPPONENT_NAME'		=> $opponent->getName(),
				'OPPONENT_AVATAR' 	=> ($opponent_data['user_avatar'] != '') ? "./download/file.php?avatar=" . $opponent_data['user_avatar'] : '',
				'OPPONENT_INFO'		=> "Niveau : {$opponent->getLevel()}<br>Attaque : {$opponent->getAttack()}<br>Défense : {$opponent->getDefense()}<br>Vitesse : {$opponent->getSpeed()}<br>Flux : {$opponent->getFlux()}",
				'OPPONENT_HP'		=> $opponent->getPV(),
				'OPPONENT_MAX_HP'	=> $opponent->getMaxPV(), // max pv + bonus
				'OPPONENT_FP'		=> $opponent->getPF(),
				'OPPONENT_MAX_FP'	=> $opponent->getMaxPF(), // max pf + bonus
			));
			
			//current turn
			$t->assign_vars(array(
				'BATTLE_TURN'		=> $battle->getTurn(),
			));
			
			// BGM
			//play BGM ?
			if($player->soundEnabled()) {
				
				if($opponent->getBGM())
					$bgm = $opponent->getBGM();
				else
					$bgm = DEFAULT_PVP_BGM;
				
				$t->assign_block_vars('background_music', array(
					'BGM'	=> $bgm,
				));
			}
			
			$t->pparse('viewbattle');
		}
		break;
}

?>