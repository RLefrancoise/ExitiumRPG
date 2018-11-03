<?php

//header('Content: text/plain');

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGPVEBattles.class.php');
include_once('./rpg/database/RPGPVPBattles.class.php');
include_once('./rpg/database/RPGEventBattles.class.php');
include_once('./rpg/database/RPGQuests.class.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGMonsters.class.php');
include_once('./rpg/php/battle_functions.php');
include_once('./rpg/php/lock_functions.php');
include_once('./rpg/php/post_functions.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}
$mode = request_var('mode', '');

if($mode == '') {
	echo 'error';
}
else {
	$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
	$msg = '';
	
	switch($mode) {
		case 'skill_menu':
			{
				$battle_mode = request_var('bm', '');
				if($battle_mode === '') { echo 'error'; return; }
				
				$player_nb = -1;
				
				if($battle_mode == 'pve') {
					//get battle
					$battle = RPGPVEBattles::getBattleByPlayerId($player->getId());
					if(!$battle) { echo 'no_battle'; return; }
					
					$player_nb = 1;
				}
				else if($battle_mode == 'event') {
					$token = request_var('t', '');
					if($token == '') { echo 'error'; return; }
					$battle = RPGEventBattles::getEvent($token, $player->getId());
					if(!$battle) { echo 'no_battle'; return; }
					
					$player_nb = 1;
				}
				else if($battle_mode == 'quest') {
					$token = request_var('t', '');
					if($token == '') { echo 'error'; return; }
					$battle = RPGQuests::getQuestBattle($token, $player->getId());
					if(!$battle) { echo 'no_battle'; return; }
					
					$player_nb = 1;
				}
				else if($battle_mode == 'pvp') {
					$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
					if(!$battle) { echo 'no_battle'; return; }
					
					if($player->getId() == $battle->getPlayerId()) $player_nb = 1;
					if($player->getId() == $battle->getOpponentId()) $player_nb = 2;
					
				}
				
				if($player_nb == -1) { echo 'error'; return; }
				
				global $_SKILLS_REQUIRED_LEVELS;
				
				$html = '<ul>';
				for($i = 1 ; $i < 5 ; $i++) {
					if($player->getLevel() < $_SKILLS_REQUIRED_LEVELS[$i]) continue; //level too low to use skill slot
					
					$skill = $player->getSkill($i);
					if(!$skill) continue;
					if(!can_use_skill($battle_mode, $player_nb, $player, $skill, $battle)) {
						if($player_nb == 1) $skill_turn = $battle->getLastTurnOfPlayer1Skill($skill->getType());
						else if($player_nb == 2) $skill_turn = $battle->getLastTurnOfPlayer2Skill($skill->getType());
						
						$remaining_turns = $skill->getCooldown() - ($battle->getTurn() - $skill_turn) + 1;
						$html .= "<li onmouseover=\"tooltip.show(this)\" onmouseout=\"tooltip.hide(this)\" title=\"{$skill->getDescription()}\" style=\"color:grey\">" . (($player->getSkillName($i) !== "") ? $player->getSkillName($i) : $skill->getName()) /*. ' : ' . $skill->getDescription()*/ . " (Encore $remaining_turns tours)</li>";
					} else {
						$html .= "<li onmouseover=\"tooltip.show(this)\" onmouseout=\"tooltip.hide(this)\" title=\"{$skill->getDescription()}\"><a href=\"javascript:skill($i)\">".(($player->getSkillName($i) !== "") ? $player->getSkillName($i) : $skill->getName()) /*. ' : ' . $skill->getDescription()*/ . ' ('.$skill->getPF().' PF, CD : ' . $skill->getCooldown() . ' tours)</a></li>';
					}
				}
				$html .= '<li><a href="javascript:close_menu()">Fermer le menu</a></li></ul>';
				
				echo $html;
			}
			break;
			
		case 'item_menu':
			{
				$html = '<ul>';
				$inventory = $player->getInventory();
				for($i = 0 ; $i < INVENTORY_SIZE ; $i++) {
					$item = $inventory->getItem($i);
					if(!$item) continue;
					if(get_class($item) === "Syringe") {
						$html .= "<li><a href=\"javascript:item(".($i+1).")\">".$item->getName().' : '.$item->getDescription().' (Restant : '.$inventory->getQuantityOfItem($i).')</a></li>';
					}
				}
				
				$html .= '<li><a href="javascript:close_menu()">Fermer le menu</a></li></ul>';
				
				echo $html;
			}
			break;
		/* PVE */
		case 'pve':
		/* EVENT */
		case 'event':
		/* QUEST */
		case 'quest':
			{
				//get battle
				if($mode == 'pve') {
					$battle = RPGPVEBattles::getBattleByPlayerId($player->getId());
				}
				else if($mode == 'event') {
					$event_token = request_var('t', '');
					if($event_token == '') { echo 'error'; return; }
					$battle = RPGEventBattles::getEvent($event_token, $player->getId());
					if(!$battle->playerIsInEvent()) { echo 'not_in_event'; return; }
					
					if( ($action == 'item') or ($action == 'run') ) { echo 'error'; return; }
				}
				else if($mode == 'quest') {
					$quest_token = request_var('t', '');
					if($quest_token == '') { echo 'error'; return; }
					$battle = RPGQuests::getQuestBattle($quest_token, $player->getId());
					if(!$battle->playerIsInBattle()) { echo 'not_in_battle'; return; }
					
					if( ($action == 'item') or ($action == 'run') ) { echo 'error'; return; }
				}
				
				if(!$battle) { echo 'no_battle'; return; }
				
				//get action
				$action = request_var('a', '');
				if($action === '') { echo 'error'; return; }
				//get monster
				$monster = RPGMonsters::getMonster($battle->getOpponentId());
				if(!$monster) { echo 'error'; return; }
							
				switch($action) {
					
					case 'attack':
					case 'defend':
					case 'skill':
					case 'item':
					case 'run':
						{	
							$player_skill = false;
							$player_item = false;
							if($action === 'skill') {
								$s = request_var('s', -1);
								if($s === -1) { echo 'error'; return; }
								
								$player_skill = $player->getSkill($s);
								
								if($player_skill === null) { echo 'error'; return; }
								if(!can_use_skill($mode, 1, $player, $player_skill, $battle)) { echo 'cant_use'; return; }
								
								$player_skill = array( 	'skill'	=> $player_skill,
														'name'	=> ($player->getSkillName($s) !== "") ? $player->getSkillName($s) : $player_skill->getName(),
													);
							}
							else if($action === 'item') {
								$i = request_var('i', -1);
								if($i === -1) { echo 'error'; return; }
								if($i < 0 or $i > INVENTORY_SIZE) { echo 'error'; return; }
								
								$player_item = $i;
							}
							
							$action_ok = false;
							$monster_skill = false;
							
							do{
								$behavior = choose_monster_behavior($monster);
								$monster_action = '';
								if($behavior === MONSTER_BEHAVIOR_ATTACK) {
									$monster_action = 'attack';
									$action_ok = true;
								}
								else if($behavior === MONSTER_BEHAVIOR_DEFEND) {
									$monster_action = 'defend';
									$action_ok = true;
								}
								else if($behavior === MONSTER_BEHAVIOR_SKILL) {
									
									//can use any skill among its skill ?
									$can_use = false;
									$skills = $monster->getSkills();
									for($i = 0 ; !$can_use and ($i < count($skills)) ; $i++){
										$can_use = can_use_skill($mode, 2, $monster, $skills[$i], $battle);
									}
									
									if($can_use) {
										$monster_action = 'skill';
										$monster_skill = choose_monster_skill($monster);

										//enough FP ?
										if($monster_skill and ($battle->getMonsterFP() >= $monster_skill['skill']->getPF())) {
											$skill_last_turn = $battle->getLastTurnOfPlayer2Skill($monster_skill['skill']->getType());
											//cooldown is over ?
											if($skill_last_turn !== false) {
												if($skill_last_turn === 0) $action_ok = true;
												else if(($battle->getTurn() - $skill_last_turn) > $monster_skill['skill']->getCooldown()) $action_ok = true;
											}
											else {echo 'error'; return; } //skill type is invalid
										}
									}
									else {
										$behaviors = $monster->getBehaviors();
										$idle = true;
										for($i = 0 ; $idle and $i < count($behaviors) ; $i++) {
											if( ($behaviors[$i] === MONSTER_BEHAVIOR_ATTACK) or ($behaviors[$i] === MONSTER_BEHAVIOR_DEFEND) )
												$idle = false;
										}
										
										if($idle) {
											$monster_action = MONSTER_BEHAVIOR_IDLE;
											$action_ok = true;
										}
									}
								}
								
							}while(!$action_ok);
							
							//---if pve, store battle data, may be used for achievements etc
							if($mode == 'pve') {
								/*$data = array(
									'player_id' => 	$player->getId(),
									'turn'		=>	$battle->getTurn(),
									'action'	=>	$action,
									'battle_token'	=>	$battle->getToken(),
								);
								
								if($player_skill) $data['skill_type'] = $player_skill['skill']->getType();
								if($player_item) {
									$item = RPGInventories::getItemByPlayerAndSlot($player, $player_item);
									if(!$item) {
										trigger_error('No item for player' . $player->getId() . ' at slot ' . $player_item, E_USER_ERROR);
										return false;
									}
									$data['item_type'] 	=	$item->getType();
									$data['item_id']	=	$item->getId();
								}
								
								if(!RPGPVEBattles::storeTurnData($data)) {
									trigger_error('Failed to store turn data', E_USER_ERROR);
									return false;
								}*/
							}
	
							//$json = play_pve_battle($action, $player, $monster, $battle);
							$json = play_battle($player, $mode, $player, $action, $monster, $monster_action, $battle, $player_skill, $monster_skill, $player_item);
							if($json === false) echo 'error';
							else echo json_encode($json);
							
						}
						break;
					default:
						echo 'error';
						break;
				}
			}
			break;
			
		case 'pvp':
			{
				//get battle
				$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
				if(!$battle) { echo 'no_battle'; return; }
				if(!$battle->isStarted()) { echo 'action_ok'; return; }
				
				$player_nb = -1;
				
				if($player->getId() == $battle->getPlayerId()) $player_nb = 1;
				if($player->getId() == $battle->getOpponentId()) $player_nb = 2;
				
				if($player_nb == -1) { echo 'error'; return; }
				
				//get action
				$action = request_var('a', '');
				if($action === '') { echo 'error'; return; }
				
				switch($action) {
					case 'attack':
					case 'defend':
					case 'skill':
						{
							//if player has already play his turn, stop here
							$actions = RPGPVPBattles::getBattleActionsByTurn($battle->getToken(), $player->getId(), $battle->getTurn());
							if($actions) { echo 'played_already'; return; }
						
							$skill_name = false;
							$skill_slot = false;
							
							//get player skill if needed
							if($action === 'skill') {
								$s = request_var('s', -1);
								if($s === -1) { echo 'error'; return; }
								
								$player_skill = $player->getSkill($s);
								
								if($player_skill === null) { echo 'error'; return; }
								if(!can_use_skill('pvp', $player_nb, $player, $player_skill, $battle)) { echo 'cant_use'; return; }
													
								$skill_name = ($player->getSkillName($s) !== "") ? $player->getSkillName($s) : $player_skill->getName();
								$skill_slot = $s;
							}
							
							if(RPGPVPBattles::setBattleTurnAction($player->getId(), $battle->getToken(), $battle->getTurn(), $action, $skill_name, $skill_slot) and RPGPVPBattles::setPlayerLastActiveTurn($battle->getToken(), $player_nb, $battle->getTurn()))
								echo 'action_ok';
							else
								echo 'error';
						}
						break;
					default:
						echo 'error';
						break;
				}
			}
			break;
			
		case 'get_pvp_status':
			{
				$json = array();
				$json['general'] = array();
				
				//get battle
				$battle = RPGPVPBattles::getBattleByPlayerId($player->getId());
				if(!$battle) { echo 'no_battle'; return; }
				
				
						
				//battle is over (and current player is the one who created it) ?
				if($battle->isOver() /*and ($battle->getPlayerId() == $player->getId())*/) {
				
					$lock = new sqlLock($battle->getToken());
					if($lock->lock()) {
					
						//check if both players saw turns results
						$i = 1;
						$can_delete = true;
						
						for( ; $can_delete && $i < $battle->getTurn() ; $i++) {
							$turn_results = RPGPVPBattles::getBattleTurnResults($battle->getToken(), $i);
							if(!$turn_results) continue;
							
							if(!$turn_results['player1_read'] or !$turn_results['player2_read']) $can_delete = false;
						}
						
						//if both players saw results, we can delete battle
						if($can_delete) {
							$player1 = RPGUsersPlayers::getPlayerByPlayerId($battle->getPlayerId());
							$player2 = RPGUsersPlayers::getPlayerByPlayerId($battle->getOpponentId());
							if(!$player1 or !$player2) { echo 'error'; return; }
								
							//post result on topic
							$is_draw = false;
							
							$player1_life = $player1->getPV();
							$player2_life = $player2->getPV();
							if($player1_life == 0 and $player2_life == 0) $is_draw = true;
							
							$text = '';
							
							if($is_draw) {
								$text = utf8_normalize_nfc("Le combat s'est terminé sur une égalité (Nombre de tours : {$battle->getTurn()}).");
							}
							else {
								$winner = ($player1->getPV() > 0) ? $player1 : $player2;
								$loser = ($player1->getPV() == 0) ? $player1 : $player2;
								
								$text = "Le joueur {$winner->getName()} a battu le joueur {$loser->getName()}.
								
								Nombre de tours : {$battle->getTurn()}
								PV restants : {$winner->getPV()}";
								$text = utf8_normalize_nfc($text);
							}
							
							$battle_request = RPGPVPBattles::getPVPRequestByBattleToken($battle->getToken());
							if(!$battle_request) { echo 'error'; return; }
							
										
							rpg_post('Résultat du combat PVP', $text, 'reply', $battle_request['forum_id'], $battle_request['topic_id']);
							
							
							/*$subject = utf8_normalize_nfc('Résultat du combat PVP');
							
							// variables to hold the parameters for submit_post
							$poll = $uid = $bitfield = $options = ''; 

							generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
							generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

							$data = array( 
								'forum_id'      => $battle_request['forum_id'],
								'topic_id'		=> $battle_request['topic_id'],
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
							
							if (!RPGPVPBattles::deleteBattle($battle->getToken()))
								echo 'error';
							else
								echo 'delete_ok';
							
							return;
							
						} 
						
					} else { echo 'battle_over'; return; }
				}
				
				
				$player_nb = -1;
				
				if($player->getId() == $battle->getPlayerId()) $player_nb = 1;
				if($player->getId() == $battle->getOpponentId()) $player_nb = 2;
				
				if($player_nb == -1) { echo 'error'; return; }
				
				//update active time of player
				/*if(!RPGPVPBattles::setPlayerLastActiveTime($battle->getToken(), $player_nb, time())) {
					echo 'error';
					return;
				}*/
				
				//look if opponent has joined the battle
				if( ($player_nb == 1) and !$battle->player2InBattle() ) {
					$json['general']['pvp_info'] = "L'adversaire n'a pas encore rejoint le combat.";
					echo json_encode($json);
					return;
				}
				else if( ($player_nb == 2) and !$battle->player1InBattle() ) {
					$json['general']['pvp_info'] = "L'adversaire n'a pas encore rejoint le combat.";
					echo json_encode($json);
					return;
				}
				
				//if both players in battle, start it if not already
				if($battle->player1InBattle() and $battle->player2InBattle() and !$battle->isStarted()) {
					
					$lock = new sqlLock($battle->getToken());
					if($lock->lock()) {
					
						$db->sql_transaction('begin');
						
						if(!RPGPVPBattles::setBattleStartedFlag($battle->getToken()) or !RPGPVPBattles::setBattleTurnTime($battle->getToken(), time())) {
							echo 'error';
							return;
						}
						
						$db->sql_transaction('commit');
						
						$lock->release();
					}
					else {
						echo 'update_ok';
						return;
					}
				}
				
				//look if one of players is afk
				$player1_last_active = $battle->getPlayer1LastActiveTurn();
				$player2_last_active = $battle->getPlayer2LastActiveTurn();
				
				//player1 or player2 is afk ?
				if( ( ($battle->getTurn() - $player1_last_active) > PVP_MAX_TURNS_FOR_CANCEL ) or
					( ($battle->getTurn() - $player2_last_active) > PVP_MAX_TURNS_FOR_CANCEL ) ) {
				
					$lock = new sqlLock($battle->getToken());
					if($lock->lock()) {
						
						$battle_request = RPGPVPBattles::getPVPRequestByBattleToken($battle->getToken());
						if(!$battle_request) { echo 'error'; return; }
							
						/*$subject = utf8_normalize_nfc('Résultat du combat PVP');
						$text = utf8_normalize_nfc('Le combat a été interrompu car un joueur n\'a pas joué pendant ' . PVP_MAX_TURNS_FOR_CANCEL . ' tours.');
						
						// variables to hold the parameters for submit_post
						$poll = $uid = $bitfield = $options = ''; 

						generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
						generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

						$data = array( 
							'forum_id'      => $battle_request['forum_id'],
							'topic_id'		=> $battle_request['topic_id'],
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
						
						rpg_post('Résultat du combat PVP', 'Le combat a été interrompu car un joueur n\'a pas joué pendant ' . PVP_MAX_TURNS_FOR_CANCEL . ' tours.', 'reply', $battle_request['forum_id'], $battle_request['topic_id']);
						
						if(!RPGPVPBattles::deleteBattle($battle->getToken())) { echo 'error'; return; }
						else { echo 'cancelled'; return; }
						
						$lock->release();
					}
					
				}
				
				//get battle time
				$battle_time = PVP_TURN_SECONDS - (time() - $battle->getTurnTime());
				if($battle_time < 0) $battle_time = 0;
				$json['general']['battle_time'] = $battle_time;
				
				//check if player has read previous turns info
				$i = 1;
				while($i <= $battle->getTurn()) {
					$turn_results = RPGPVPBattles::getBattleTurnResults($battle->getToken(), $i);
					if($turn_results) {
						if( ( ($player_nb == 1) and !$turn_results['player1_read'] ) or
							( ($player_nb == 2) and !$turn_results['player2_read'] ) ) {
							
							if(!RPGPVPBattles::setBattleTurnResultReadFlag($battle->getToken(), $i, $player_nb)) {
								echo 'error';
								return;
							}
							
							$json = json_decode($turn_results['result'], true);
							
							//if player is second player, reverse players in json for display
							if($player->getId() == $battle->getOpponentId()) {
								$tmp = $json['player1'];
								$json['player1'] = $json['player2'];
								$json['player2'] = $tmp;
							}
							
							echo json_encode($json);
							return;
						}
						
					}
					
					$i++;
				}
				
				$player1 = RPGUsersPlayers::getPlayerByPlayerId($battle->getPlayerId());
				$player2 = RPGUsersPlayers::getPlayerByPlayerId($battle->getOpponentId());
				if(!$player1 or !$player2) { echo 'error'; return; }
				
				if($player1->getPV() > 0 and $player2->getPV() > 0) {
				
					//get actions for this turn
					$player_actions = RPGPVPBattles::getBattleActions($battle->getToken(), $player->getId());
					
					if($player_nb == 1)
						$opponent_actions = RPGPVPBattles::getBattleActions($battle->getToken(), $battle->getOpponentId());
					else
						$opponent_actions = RPGPVPBattles::getBattleActions($battle->getToken(), $battle->getPlayerId());
					
					//if player hasn't played
					if( ($battle_time > 0) and !$player_actions[$battle->getTurn()]) {
						$json['general']['pvp_info'] = "En attente de votre action.";
						echo json_encode($json);
						return;
					}
					
					//if opponent hasn't played
					if( ($battle_time > 0) and !$opponent_actions[$battle->getTurn()]) {
						$json['general']['pvp_info'] = "En attente de l'action de l'adversaire.";
						echo json_encode($json);
						return;
					}
					
					//get actions of players
					$player1_action = RPGPVPBattles::getBattleActionsByTurn($battle->getToken(), $battle->getPlayerId(), $battle->getTurn());
					$player2_action = RPGPVPBattles::getBattleActionsByTurn($battle->getToken(), $battle->getOpponentId(), $battle->getTurn());
					
					$player1_skill = false;
					$player2_skill = false;
					
					if($battle_time > 0) {
						// if we are here players should have played their turn, so if not, there's an error
						if( !$player1_action or !$player2_action) { echo 'error'; return; }
					}
					else {
						
						if(!$player1_action) {
							$player1_action = array();
							$player1_action['action'] = 'idle';
						}
						if(!$player2_action) {
							$player2_action = array();
							$player2_action['action'] = 'idle';
						}
					}
					
					//if player1 use a skill, get skill data
					if($player1_action['action'] == 'skill') {
						$player1_skill = $player1->getSkill($player1_action['skill_slot']);
						if($player1_skill === null) { echo 'error'; return; }
						$player1_skill = array( 'skill'	=> $player1_skill,
												'name'	=> ($player1->getSkillName($player1_action['skill_slot']) !== "") ? $player1->getSkillName($player1_action['skill_slot']) : $player1_skill->getName(),
											);

					}
					
					//if player2 use a skill, get skill data
					if($player2_action['action'] == 'skill') {
						$player2_skill = $player2->getSkill($player2_action['skill_slot']);
						if($player2_skill === null) { echo 'error'; return; }
						$player2_skill = array( 'skill'	=> $player2_skill,
												'name'	=> ($player2->getSkillName($player2_action['skill_slot']) !== "") ? $player2->getSkillName($player2_action['skill_slot']) : $player2_skill->getName(),
											);
					}		
						
					//mutex here to prevent turn being played multiple times
					$lock = new sqlLock($battle->getToken());
					if($lock->lock()) {
					
						//play turn if not already played
						$turn_results = RPGPVPBattles::getBattleTurnResults($battle->getToken(), $battle->getTurn());
						
						
						if(!$turn_results['result'] /*and ($player->getId() == $battle->getPlayerId())*/ ) { 
							
							//play turn
							$db->sql_transaction('begin');
							
							$json = play_battle($player, 'pvp', $player1, $player1_action['action'], $player2, $player2_action['action'], $battle, $player1_skill, $player2_skill, false);
							
							if($json === false) {
								echo 'error';
								return;
							}
							
							if(!RPGPVPBattles::storeBattleTurnResult($battle->getToken(), $battle->getTurn() - 1, json_encode($json))) {
								echo 'error';
								return;
							}
							
							//reset turn counter
							if(!RPGPVPBattles::setBattleTurnTime($battle->getToken(), time())) {
								echo 'error';
								return;
							}
							
							$db->sql_transaction('commit');

						} else
							$json = $turn_results['result'];
					
						if($json === false) { echo 'error'; return; }
					
						$lock->release();
					}
					
				}
				else {
				
					//mutex here to prevent message being posted multiple times and battle deleted more than once
					$lock = new sqlLock($battle->getToken());
					if($lock->lock()) {
					
					
						//if one of player dead, delete battle
						$battle_request = RPGPVPBattles::getPVPRequestByBattleToken($battle->getToken());
						if(!$battle_request) { echo 'error'; return; }
							
						/*$subject = utf8_normalize_nfc('Résultat du combat PVP');
						$text = utf8_normalize_nfc('Le combat a été interrompu suite à une erreur.');
						
						// variables to hold the parameters for submit_post
						$poll = $uid = $bitfield = $options = ''; 

						generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
						generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);

						$data = array( 
							'forum_id'      => $battle_request['forum_id'],
							'topic_id'		=> $battle_request['topic_id'],
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
						
						rpg_post('Résultat du combat PVP', 'Le combat a été interrompu suite à une erreur.', 'reply', $battle_request['forum_id'], $battle_request['topic_id']);
						
						if(!RPGPVPBattles::deleteBattle($battle->getToken())) { echo 'error'; return; }
						
						echo 'interrupted';
						return;
						
						$lock->release();
					}
					
				}
				
				echo 'update_ok';
			}
			break;
			
		default:
			echo 'error';
			break;
	}
}

?>