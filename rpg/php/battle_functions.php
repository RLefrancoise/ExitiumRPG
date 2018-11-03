<?php

include_once(__DIR__ . '/../../common.php');
include_once('./rpg/database/RPGPlayers.class.php');
include_once('./rpg/database/RPGMonsters.class.php');
include_once('./rpg/database/RPGPVEBattles.class.php');
include_once('./rpg/database/RPGPVPBattles.class.php');
include_once('./rpg/database/RPGEventBattles.class.php');
include_once('./rpg/database/RPGQuests.class.php');
include_once('./rpg/database/RPGClothes.class.php');
include_once('./rpg/database/RPGLeggings.class.php');
include_once('./rpg/database/RPGGloves.class.php');
include_once('./rpg/database/RPGShoes.class.php');
include_once('./rpg/database/RPGSyringes.class.php');
include_once('./rpg/database/RPGSpecials.class.php');
include_once('./rpg/database/RPGOrbs.class.php');
include_once('./rpg/database/RPGMonsterBooks.class.php');
include_once('array_functions.php');
include_once('item_functions.php');
include_once('player_functions.php');
include_once('status_functions.php');
include_once('string_functions.php');
include_once('numeric_functions.php');
include_once('battle/BattleManager.class.php');

$MODE = '';
$PLAYER = null;
$MANAGER = null;
$BATTLE_DATA = array(
	'player1_last_damage'	=> false,
	'player2_last_damage'	=> false,
	'player1_total_damage_given' => 0,
	'player1_total_damage_received' => 0,
	'player2_total_damage_given' => 0,
	'player2_total_damage_received' => 0,
);


function play_battle(Player& $user_player, $mode, Player &$player1, $action1, Creature &$player2, $action2, AbstractBattle &$battle, $skill1 = false, $skill2 = false, $item1_slot = false) {
	global $db, $MODE, $PLAYER, $MANAGER;
	
	if($mode != 'pve' and $mode != 'event' and $mode != 'pvp' and $mode != 'quest') { trigger_error('Erreur', E_USER_ERROR); return false; }
	
	$MODE = $mode;
	$PLAYER = $user_player;
	$MANAGER = new BattleManager($battle, $mode);
	
	$json_array = array();
	$json_array['player1'] 	= array();
	$json_array['player2'] 	= array();
	$json_array['general']	= array();
	
	$msg = '';
	$m = '';
	
	$db->sql_transaction('begin');
	
	//---before battle actions
	
	$m = before_battle($player1, $player2, $battle);
	if($m === false) { trigger_error('Erreur', E_USER_ERROR); return false; }
	else $msg .= $m;
	
	//---play battle
	
	if($player1->getSpeed() >= $player2->getSpeed()) {
		// player1 action
		$m = actor_action(1, $json_array['player1'], $json_array['player2'], $player1, $action1, $player2, $action2, $battle, $skill1, $skill2, $item1_slot);
		if($m !== false) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
		// player2 action
		$m = actor_action(2, $json_array['player2'], $json_array['player1'], $player2, $action2, $player1, $action1, $battle, $skill2, $skill1, false);
		if($m !== false) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
	} else {
		// player2 action
		$m = actor_action(2, $json_array['player2'], $json_array['player1'], $player2, $action2, $player1, $action1, $battle, $skill2, $skill1, false);
		if($m !== false) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
		// player1 action
		$m = actor_action(1, $json_array['player1'], $json_array['player2'], $player1, $action1, $player2, $action2, $battle, $skill1, $skill2, $item1_slot);
		if($m !== false) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
	}
	
	
	//---after battle
	
	// battle effects
	//---player1
	$m = check_battle_effects(1, $player1, $player2, $battle);
	if($m !== false) $msg .= $m;
	else { trigger_error('Erreur Battle Effects P1', E_USER_ERROR); return false; }
	
	//---player2
	$m = check_battle_effects(2, $player2, $player1, $battle);
	if($m !== false) $msg .= $m;
	else { trigger_error('Erreur Battle Effects P2', E_USER_ERROR); return false; }
	
	$m = after_battle($player1, $player2, $battle);
	if($m === false) { trigger_error('Erreur', E_USER_ERROR); return false; }
	else $msg .= $m;
	
	//--------------------------------
	
	// increment battle turn
	if(!$MANAGER->increment_turn()) { trigger_error('Erreur Increment Turn', E_USER_ERROR); return false; }
	
	// PLAYERS STATUS
	$player1_dead = false;
	$player1_run = $json_array['player1']['run'];
	$player2_dead = false;
	$player2_run = $json_array['player2']['run'];
	
	$player1_dead = ( $player1->getPV() == 0 );
	if($MODE == 'pvp') $player2_dead = ( $player2->getPV() == 0 );
	else if($MODE == 'pve' or $MODE == 'event' or $MODE == 'quest') $player2_dead = ( $battle->getMonsterHP() == 0 );
	else { trigger_error('Erreur MODE', E_USER_ERROR); return false; }
	
	//player1 dead
	if($player1_dead) {
		if($player1->soundEnabled()) $json_array['player1']['jingle'] = BATTLE_JINGLE_GAMEOVER;
		if($MODE == 'pvp' ) {
			if($player2->soundEnabled() and !$player2_dead) $json_array['player2']['jingle'] = BATTLE_JINGLE_VICTORY;
			
			
		}
	}
	//player2 dead
	if($player2_dead) {
		if($MODE == 'pvp') {
			if($player2->soundEnabled()) $json_array['player2']['jingle'] = BATTLE_JINGLE_GAMEOVER;
		}
		if($player1->soundEnabled()) $json_array['player1']['jingle'] = BATTLE_JINGLE_VICTORY;
	}
	
	if($MODE == 'pvp') {
		$player1_honor = $player1->getHonor();
		$player2_honor = $player2->getHonor();
	}
	
	//player1 status
	if($player1_dead) {
		$msg .= "{$player1->getName()} succombe à ses blessures.<br>";
		//remove xp
		$exp = get_player_lose_experience($player1);
		if( ($exp > 0) /*and ($player1->getXP() > 0)*/ ) {
			if(!player_give_exp($player1, -1 * $exp)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			$msg .= colorize_string("{$player1->getName()} perd $exp XP.<br>", 128, 255, 128);
		}
		
		if($MODE == 'pve') {
			//increase pve lost battles
			if(!RPGPlayersStats::incrementStatByPlayer($player1, 'pve_total_loses')) {
				trigger_error("Erreur lors de l'incrément du nombre de combats pve perdus.", E_USER_ERROR);
				return false;
			}
			
			//update monster book
			$mb = RPGMonsterBooks::getMonsterBook($player1->getId());
			$monster_stats = $mb->getMonsterStats($player2->getId(), $battle->getAreaPartId());
			if(!$monster_stats) {
				trigger_error('Pas de données de monstre trouvées dans le bestiaire.', E_USER_ERROR);
				return false;
			}
			
			if(!RPGMonsterBooks::updateEntry($player1->getId(), $player2->getId(), $battle->getAreaPartId(), array('loses'	=>	$monster_stats['loses'] + 1,))) {
				trigger_error('Erreur lors de la mise à jour du bestiaire.', E_USER_ERROR);
				return false;
			}
		}
		
		if($MODE == 'pvp') {
			//give xp to player2
			$level = $player2->getLevel();
			$exp = get_pvp_experience($player1, $battle->getPlayer1HP(), $battle->getPlayer1FP(), $player2);
			if( ($exp > 0) and !player_give_exp($player2, $exp)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			$new_level = $player2->getLevel();
			$msg .= colorize_string("{$player2->getName()} gagne $exp XP.<br>", 128, 255, 128);
			if($level < $new_level) {
				$msg .= colorize_string("{$player2->getName()} gagne " . ($new_level - $level) . " niveau.<br>", 128, 255, 128);
			}
			
			//give honor points to player2 and remove points of player1
		
			if(!$player2_dead) {
				if(		!RPGPlayers::setHonorOfPlayer($player2, $player2->getHonor() + PVP_WIN_HONOR_POINTS)
					or 	!RPGPlayers::setHonorOfPlayer($player1, $player1_honor - PVP_LOSE_HONOR_POINTS) 
					or	!RPGPlayersStats::incrementStatByPlayer($player2, 'pvp_total_wins')
					or	!RPGPlayersStats::incrementStatByPlayer($player1, 'pvp_total_loses')) {
					trigger_error('Erreur', E_USER_ERROR); return false;
				}
			} else { //draw battle
				if(	!RPGPlayers::setHonorOfPlayer($player1, $player1->getHonor() + PVP_DRAW_HONOR_POINTS)
				or	!RPGPlayersStats::incrementStatByPlayer($player1, 'pvp_total_draws')) {
					trigger_error('Erreur', E_USER_ERROR); return false;
				}
			}
			
		}
		
	}
	
	$json_array['player1']['dead']		= ( $player1->getPV() === 0 );
	$json_array['player1']['hp']		= $player1->getPV();
	$json_array['player1']['max_hp']	= $player1->getMaxPV();
	$json_array['player1']['fp']		= $player1->getPF();
	$json_array['player1']['max_fp']	= $player1->getMaxPF();
		
	//player2 status
	if($MODE == 'pvp') {
	
		if($player2_dead) {
			$msg .= "{$player2->getName()} succombe à ses blessures.<br>";
			//remove xp
			$exp = get_player_lose_experience($player2);
			if( ($exp > 0) /*and ($player2->getXP() > 0)*/ ) {
				if(!player_give_exp($player2, -1 * $exp)) { trigger_error('Erreur', E_USER_ERROR); return false; }
				$msg .= colorize_string("{$player2->getName()} perd $exp XP.<br>", 128, 255, 128);
			}
			
			//give xp to player1
			$level = $player1->getLevel();
			$exp = get_pvp_experience($player2, $battle->getPlayer2HP(), $battle->getPlayer2FP(), $player1);
			if(($exp > 0) and !player_give_exp($player1, $exp)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			$new_level = $player1->getLevel();
			$msg .= colorize_string("{$player1->getName()} gagne $exp XP.<br>", 128, 255, 128);
			if($level < $new_level) {
				$msg .= colorize_string("{$player1->getName()} gagne " . ($new_level - $level) . " niveau.<br>", 128, 255, 128);
			}
		
			//give honor points to player1 and remove points of player2
			if(!$player1_dead) {
				if(		!RPGPlayers::setHonorOfPlayer($player1, $player1->getHonor() + PVP_WIN_HONOR_POINTS)
					or 	!RPGPlayers::setHonorOfPlayer($player2, $player2_honor - PVP_LOSE_HONOR_POINTS)
					or	!RPGPlayersStats::incrementStatByPlayer($player1, 'pvp_total_wins')
					or	!RPGPlayersStats::incrementStatByPlayer($player2, 'pvp_total_loses') ) {
					trigger_error('Erreur', E_USER_ERROR); return false;
				}
			} else { //draw battle
				if(	!RPGPlayers::setHonorOfPlayer($player2, $player2->getHonor() + PVP_DRAW_HONOR_POINTS)
				or	!RPGPlayersStats::incrementStatByPlayer($player2, 'pvp_total_draws') ) {
					trigger_error('Erreur', E_USER_ERROR); return false;
				}
			}
		
		}
		
		$json_array['player2']['dead']		= ( $player2->getPV() === 0 );
		$json_array['player2']['hp']		= $player2->getPV();
		$json_array['player2']['max_hp']	= $player2->getMaxPV();
		$json_array['player2']['fp']		= $player2->getPF();
		$json_array['player2']['max_fp']	= $player2->getMaxPF();
	}
	else if( ($MODE == 'pve') or ($MODE == 'event') or ($MODE == 'quest') ) {
		
		//dead ?
		if($player2_dead) {
			$msg .= "{$player2->getName()} succombe à ses blessures.<br>";
			
			if($MODE == 'pve') {
				//give xp
				$level = $player1->getLevel();
				$exp = get_monster_experience($player2, $player1);
				if(($exp > 0) and !player_give_exp($player1, $exp)) { trigger_error('Erreur', E_USER_ERROR); return false; }
				$new_level = $player1->getLevel();
				if($exp > 0) $msg .= colorize_string("{$player1->getName()} gagne $exp XP.<br>", 128, 255, 128);
				if($level < $new_level) {
					$msg .= colorize_string("{$player1->getName()} gagne " . ($new_level - $level) . " niveau.<br>", 128, 255, 128);
				}
				//give ralz
				$ralz = get_monster_ralz($player2, $player1);
				
				if(!player_give_ralz($player1, $ralz)) { trigger_error('Erreur', E_USER_ERROR); return false; }
				if($ralz > 0) $msg .= colorize_string("{$player1->getName()} gagne $ralz Ralz.<br>", 128, 255, 128);
			
				//monster drop 
				//$drop = get_monster_item_dropped($player2, $battle);
				$dropped_items = get_monster_drops($player2, $battle);
				if($dropped_items !== false) {
				
					foreach($dropped_items as $drop) {
						$has_space = true;
						
						//player has space in inventory ?
						if($player1->getInventory()->isFull()) {
							if($drop->isOnePerSlot()) $has_space = false;
							else if(!$drop->isOnePerSlot() and !$player1->getInventory()->hasItem($drop)) $has_space = false;
						}
						
						if($has_space and !RPGPlayers::giveItemToPlayer($player1, $drop)) {
							trigger_error("Erreur lors du don de l'objet", E_USER_ERROR);
							return false;
						}
						
						$msg .= colorize_string("{$player2->getName()} laisse tomber {$drop->getName()}.<br>", 128, 255, 128);
						if(!$has_space)
							$msg .= colorize_string("{$player1->getName()} ne peut plus rien porter.<br>", 128, 255, 128);

					}
				}

				//increment pve winned battles
				if(!RPGPlayersStats::incrementStatByPlayer($player1, 'pve_total_wins')) {
					trigger_error("Erreur lors de l'incrément du nombre de combats pve gagnés.", E_USER_ERROR);
					return false;
				}
				
				//update monster book
				$mb = RPGMonsterBooks::getMonsterBook($player1->getId());
				$monster_stats = $mb->getMonsterStats($player2->getId(), $battle->getAreaPartId());
				if(!$monster_stats) {
					trigger_error('Pas de données de monstre trouvées dans le bestiaire.', E_USER_ERROR);
					return false;
				}
				
				if(!RPGMonsterBooks::updateEntry($player1->getId(), $player2->getId(), $battle->getAreaPartId(), array('wins'	=>	$monster_stats['wins'] + 1,))) {
					trigger_error('Erreur lors de la mise à jour du bestiaire.', E_USER_ERROR);
					return false;
				}
			}
		}
		
		$json_array['player2']['dead']		= ( $battle->getMonsterHP() == 0 );
		$json_array['player2']['hp']		= $battle->getMonsterHP();
		$json_array['player2']['max_hp']	= $player2->getMaxPV();
		$json_array['player2']['fp']		= $battle->getMonsterFP();
		$json_array['player2']['max_fp']	= $player2->getMaxPF();
	}

	//honor messages
	if($MODE == 'pvp') {
		//player1
		$new_honor = $player1->getHonor() - $player1_honor;
		if($new_honor < 0) {
			$new_honor *= -1;
			$msg .= colorize_string("{$player1->getName()} perd $new_honor points d'honneur.<br>", 128, 255, 128);
		} else if($new_honor > 0) {
			$msg .= colorize_string("{$player1->getName()} gagne $new_honor points d'honneur.<br>", 128, 255, 128);
		}
		
		//player2
		$new_honor = $player2->getHonor() - $player2_honor;
		if($new_honor < 0) {
			$new_honor *= -1;
			$msg .= colorize_string("{$player2->getName()} perd $new_honor points d'honneur.<br>", 128, 255, 128);
		} else if($new_honor > 0) {
			$msg .= colorize_string("{$player2->getName()} gagne $new_honor points d'honneur.<br>", 128, 255, 128);
		}
	}
	
	
	//end battle ?
	if($player1_dead or $player2_dead or $player1_run or $player2_run) {
	
		if($MODE == 'pve') {
			if(!RPGPVEBattles::deleteBattle($battle->getToken())) { trigger_error('Erreur', E_USER_ERROR); return false; }
		}
		else if($MODE == 'event') {
			//if player escaped, update the flag
			if($player1_run) {
				if(!RPGEventBattles::setPlayerInEvent($battle, false)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			} 
			else if($player1_dead) {
				if(!RPGEventBattles::setPlayerIsDead($battle, true)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			}
			/*else {
				//manage ranking and items here
				//if(!manage_event_ending($battle)) { trigger_error('Erreur', E_USER_ERROR); return false; }
				//if(!RPGEventBattles::manageEventEnding($battle->getToken(), $battle->getForum(), $battle->getTopicId())) { trigger_error('Erreur', E_USER_ERROR); return false; }
				//if(!RPGEventBattles::deleteEvent($battle->getToken())) { trigger_error('Erreur', E_USER_ERROR); return false; }
			}*/
		}
		else if($MODE == 'pvp') {
			if(!RPGPVPBattles::setBattleOverFlag($battle->getToken())) { trigger_error('Erreur', E_USER_ERROR); return false; }
			//if(!RPGPVPBattles::deleteBattle($battle->getToken())) { trigger_error('Erreur', E_USER_ERROR); return false; }
		}
		else if($MODE == 'quest') {
			if($player2_dead) {
				if(!RPGQuests::setBattleIsOver($battle, true)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			}
			else if($player1_dead) {
				if(!RPGQuests::setPlayerIsDead($battle, true)) { trigger_error('Erreur', E_USER_ERROR); return false; }
			}
		}

	}
	
	
	$db->sql_transaction('commit');
	
	//general info
	$json_array['general']['turn']		= $battle->getTurn();
	$json_array['general']['msg_box']	= $msg;
	
	//buffs
	$json_array['player1']['buffs']		= array(
		'buff_atk'		=>	$MANAGER->get_buff_by_stat(1, $player1, $player2, STAT_ATTACK),
		'buff_def'		=>	$MANAGER->get_buff_by_stat(1, $player1, $player2, STAT_DEFENSE),
		'buff_spd'		=>	$MANAGER->get_buff_by_stat(1, $player1, $player2, STAT_SPEED),
		'buff_flux'		=>	$MANAGER->get_buff_by_stat(1, $player1, $player2, STAT_FLUX),
		'buff_res'		=>	$MANAGER->get_buff_by_stat(1, $player1, $player2, STAT_RESISTANCE),
		'buff_crit'		=>	$MANAGER->get_buff_by_stat(1, $player1, $player2, STAT_CRITICAL),
	);
	
	$json_array['player2']['buffs']		= array(
		'buff_atk'		=>	$MANAGER->get_buff_by_stat(2, $player2, $player1, STAT_ATTACK),
		'buff_def'		=>	$MANAGER->get_buff_by_stat(2, $player2, $player1, STAT_DEFENSE),
		'buff_spd'		=>	$MANAGER->get_buff_by_stat(2, $player2, $player1, STAT_SPEED),
		'buff_flux'		=>	$MANAGER->get_buff_by_stat(2, $player2, $player1, STAT_FLUX),
		'buff_res'		=>	$MANAGER->get_buff_by_stat(2, $player2, $player1, STAT_RESISTANCE),
		'buff_crit'		=>	$MANAGER->get_buff_by_stat(2, $player2, $player1, STAT_CRITICAL),
	);
	
	return $json_array;
}

function before_battle(Creature &$player1, Creature &$player2, AbstractBattle &$battle) {

	global $MANAGER, $BATTLE_DATA;
	
	$msg = '';
	$m = '';
	
	$m = $MANAGER->before_battle($player1, $player2, $BATTLE_DATA);
	if($m === false) return false;
	else $msg .= $m;
		
	return $msg;
}

function after_battle(Creature &$player1, Creature &$player2, AbstractBattle &$battle) {
	global $MANAGER, $BATTLE_DATA;
	
	$msg = '';
	$m = '';
	
	$m = $MANAGER->after_battle($player1, $player2, $BATTLE_DATA);
	if($m === false) return false;
	else $msg .= $m;
		
	return $msg;
}

function check_battle_effects($player_nb, Creature &$actor, Creature &$target, AbstractBattle &$battle) {
	global $MODE, $MANAGER;
	
	$msg = '';
	
	$effects = get_battle_effects($player_nb, $battle);
	
	/* REGEN */
	if($effects[EFFECT_REGEN]) {
		/*$base_per = 0.05; //base regen : 5% of max HP
		$flux_points_to_inc = 5; //increase regen each 5 flux points
		$inc_per_point = 0.005; //regen + 0.5% by increase 
		
		$regen = (int) floor($actor->getMaxPV() * ($base_per + $inc_per_point * ($MANAGER->get_stat_with_buff($actor, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / $flux_points_to_inc));
		*/
		
		$base_regen = 10; // 10 PV each turn
		$res = ($MANAGER->get_stat_with_buff($actor, $player_nb, STAT_RESISTANCE) - DEFAULT_RESISTANCE);
		$regen = $base_regen + (int) floor(0.5 * $res);
		
		
		if(!$MANAGER->give_hp($player_nb, $actor, $regen)) return false;
		
		$msg .= "{$actor->getName()} récupère $regen PV.<br>";
	}
	
	/* CURSE */
	if($effects[EFFECT_CURSE]) {
		/*$base_per = 0.05; //base curse : 5% of max HP
		$flux_points_to_inc = 5; //increase curse each 5 flux points
		$inc_per_point = 0.005; //curse + 0.5% by increase 
		
		$curse = (int) floor($target->getMaxPV() * ($base_per + $inc_per_point * ($MANAGER->get_stat_with_buff($actor, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / $flux_points_to_inc));
		*/
		
		$base_curse = 10; // 10 PV each turn
		$flux = ($MANAGER->get_stat_with_buff($actor, $player_nb, STAT_FLUX) - DEFAULT_FLUX);
		$curse = $base_curse + (int) floor(0.5 * $flux);
		if($curse < 0) $curse = 0;
		
		if(!$MANAGER->give_hp($player_nb == 1 ? 2 : 1, $target, -1 * $curse)) return false;
		
		// update battle data
		if($player_nb == 1) {
			set_battle_data('player2_last_damage', $curse);
			inc_battle_data('player1_total_damage_given', $curse);
			inc_battle_data('player2_total_damage_received', $curse);
		} else {
			set_battle_data('player1_last_damage', $curse);
			inc_battle_data('player2_total_damage_given', $curse);
			inc_battle_data('player1_total_damage_received', $curse);
		}
			
		$msg .= "{$target->getName()} perd $curse PV.<br>";
	}
	
	/* EVADE */
	if($effects[EFFECT_EVADE]) {
		$msg .= "{$actor->getName()} est intouchable.<br>";
	}
	
	/* LOSE HP */
	if($effects[EFFECT_LOSE_HP]) {
		
		$hp = $MANAGER->get_hp($actor, $player_nb);
		$lost = (int) floor($actor->getMaxPV() * 0.05);
		if($lost <= 0) $lost = 1; //1 pv minimum
		if($lost > 50) $lost = 50; // 50 pv maximum
		
		if(!$MANAGER->give_hp($player_nb, $actor, -1 * $lost)) return false;
		
		// update battle data
		if($player_nb == 1) {
			set_battle_data('player1_last_damage', $lost);
		} else {
			set_battle_data('player2_last_damage', $lost);
		}
		
		$msg .= "{$actor->getName()} perd $lost PV.<br>";
	}
	
	return $msg;
}

function actor_action($player_nb, &$json_array, &$opponent_json_array, Creature &$actor, $actor_action, Creature &$target, $target_action, AbstractBattle &$battle, $actor_skill = false, $target_skill = false, $actor_item_slot = false) {
	
	global $PLAYER, $_DEFAULT_EFFECTS, $_BATTLE_ANIMS, $_BATTLE_ACTIONS_ANIMS;
	
	$msg = '';
	
	if($player_nb == 1) {
		$source_effect_array = get_actor_effects(1, $battle, $actor, $target, $actor_action, $actor_skill['skill']);
		$target_effect_array = get_actor_effects(2, $battle, $target, $actor, $target_action, $target_skill['skill']);
	}
	else if($player_nb == 2) {
		$source_effect_array = get_actor_effects(2, $battle, $actor, $target, $actor_action, $actor_skill['skill']);
		$target_effect_array = get_actor_effects(1, $battle, $target, $actor, $target_action, $target_skill['skill']);
	}
	
	//can act ?
	if($target_effect_array[EFFECT_PARALYZE]) {
		$msg .= "{$actor->getName()} est paralysé.<br>";
		return $msg;
	}
	
	if($source_effect_array[EFFECT_DOUBLESTRIKE]) {
		$msg .= "{$actor->getName()} effectue une double frappe !<br>";
		$effects = $source_effect_array;
		$effects[EFFECT_DAMAGE_MULTIPLIER] = 1;
		$effects[EFFECT_ATTACK_NUMBER] = 1;
		$m = actor_attack($player_nb, $actor, $target, $battle, $effects, $target_effect_array, false);
		if($m) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
	}
		
	/* attack */
	if($actor_action == 'attack') {
		
		$attack_nb = 1;
		
		/*if($source_effect_array[EFFECT_DOUBLESTRIKE]) {
			$msg .= "{$actor->getName()} effectue une double frappe !<br>";
			$attack_nb = 2;
		}*/
		
		for($i = 0 ; $i < $attack_nb ; $i++) {
			$msg .= "{$actor->getName()} attaque {$target->getName()}.<br>";
			
			$m = actor_attack($player_nb, $actor, $target, $battle, $source_effect_array, $target_effect_array, false);
			if($m) $msg .= $m;
			else { trigger_error('Erreur', E_USER_ERROR); return false; }
		}
		
		action_anim_in_json($opponent_json_array, 'attack');
	}
	
	/* defend */
	else if($actor_action == 'defend') {
		$msg .= "{$actor->getName()} se défend.<br>";
	}
	
	/* skill */
	else if($actor_action == 'skill') {
		if($actor_skill === false) { trigger_error('Erreur', E_USER_ERROR); return false; }
		$msg .= "{$actor->getName()} utilise {$actor_skill['name']} ({$actor_skill['skill']->getName()}).<br>";
		$m = use_skill($json_array, $opponent_json_array, $player_nb, $battle, $actor, $actor_skill['skill'], $target, $target_action, $target_skill['skill']);
		if($m !== false) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
	}
	
	/* item */
	else if($actor_action == 'item') {
		if(!$actor_item_slot) { trigger_error('Erreur', E_USER_ERROR); return false; }
		if(get_class($actor) !== "Player") { trigger_error('Erreur', E_USER_ERROR); return false; }
		
		$item = RPGInventories::getItemByPlayerAndSlot($actor, $actor_item_slot);
		if(!$item) { trigger_error('Erreur', E_USER_ERROR); return false; }
		
		$msg .= "{$actor->getName()} utilise {$item->getName()}.<br>";
		$m = battle_use_item($player_nb, $battle, $actor, $actor_item_slot, $target, $target_action, $target_skill['skill']);
		if($m !== false) $msg .= $m;
		else { trigger_error('Erreur', E_USER_ERROR); return false; }
		
		
		action_anim_in_json($json_array, 'item');
	}
	
	/* run */
	else if($actor_action == 'run') {
		//run success ?
		$actor_speed = $actor->getSpeed();
		$target_speed = $target->getSpeed();
		$success_rate = 50 * ($actor_speed / $target_speed);
		if($success_rate <= 0) $success_rate = 1; //1% minimum
		if($success_rate > 100) $success_rate = 100; //100% maximum
		
		$rate = mt_rand(1, 100);
		if($rate <= $success_rate) { //success
			$json_array['run'] = true;
			$msg .= "{$actor->getName()} fuit le combat.<br>";
			if(!RPGPlayersStats::incrementStatByPlayer($actor, 'pve_total_runs')) {
				trigger_error('Erreur', E_USER_ERROR); return false;
			}
		} else { //failure
			$msg .= "{$actor->getName()} tente de fuir le combat mais échoue.<br>";
		}
	}
	
	/* idle */
	else if($actor_action == 'idle') {
		$msg .= "{$actor->getName()} reste sur place.<br>";
	}
	
	else {
		$msg .= "Action inconnue.<br>";
	}
	
	return $msg;
}

function get_actor_effects($player_nb, AbstractBattle &$battle, Creature &$actor, Creature &$opponent, $actor_action, $actor_skill = false) {
	global $_DEFAULT_EFFECTS, $MODE;
	
	$effects = $_DEFAULT_EFFECTS;
	
	if($actor_action == 'defend') {
		$effects[EFFECT_DAMAGE_DIVIDER] = 2;
	}
	else if($actor_action == 'skill') {
		if(!$actor_skill) return false;
		$effects = get_skill_effects($actor_skill);
	}
	
	$effects = merge_effects_array($effects, get_battle_effects($player_nb, $battle));
	
	return $effects;
}

function get_battle_effects($player_nb, AbstractBattle& $battle) {
	$effects = array();
	$active_skills = array();
	
	if($player_nb === 1) {
		$active_skills = $battle->getPlayer1ActiveSkills();
	}
	else if($player_nb === 2) {
		$active_skills = $battle->getPlayer2ActiveSkills();
	}
	else return $effects;
	
	foreach($active_skills as $type => $activation_array) {
	
		//if effect is not available anymore, ignore it
		if($activation_array['start'] + $activation_array['duration'] < $battle->getTurn()) continue;
		
		switch($type) {
			case SKILL_TYPE_DOUBLESTRIKE:
				$effects[EFFECT_DOUBLESTRIKE] = true;
				break;
			case SKILL_TYPE_REGEN:
				$effects[EFFECT_REGEN] = true;
				break;
			case SKILL_TYPE_CURSE:
				$effects[EFFECT_CURSE] = true;
				break;
			case SKILL_TYPE_ILLUSION:
				$effects[EFFECT_EVADE] = true;
				$effects[EFFECT_LOSE_HP] = true;
				break;
		}
	}
	
	return $effects;
}

function get_skill_effects($skill) {
	global $_DEFAULT_EFFECTS;
	
	$effects = $_DEFAULT_EFFECTS;
	
	switch($skill->getType()) {
		case SKILL_TYPE_POWER:
			$effects[EFFECT_ATTACK_NUMBER] = 3;
			break;
		case SKILL_TYPE_SHIELD:
			$effects[EFFECT_DAMAGE_DIVIDER] = 3;
			break;
		case SKILL_TYPE_ARCANA:
			$effects[EFFECT_ATTACK_NUMBER] = 3;
			break;
		case SKILL_TYPE_BARRIER:
			$effects[EFFECT_MAGIC_DAMAGE_DIVIDER] = 3;
			break;
		case SKILL_TYPE_PARALYZE:
			$effects[EFFECT_PARALYZE] = true;
			break;
		case SKILL_TYPE_COUNTER:
			$effects[EFFECT_REPEL_DAMAGE] = true;
			break;
		case SKILL_TYPE_CURSE:
			break;
		case SKILL_TYPE_DOUBLESTRIKE:
			break;
		case SKILL_TYPE_ABSORB:
			$effects[EFFECT_ABSORB_DAMAGE] = true;
			break;
		case SKILL_TYPE_REGEN:
			break;
		case SKILL_TYPE_CANCEL:
			break;
		case SKILL_TYPE_DISPEL:
			break;
		case SKILL_TYPE_ILLUSION:
			$effects[EFFECT_EVADE] = true;
			$effects[EFFECT_LOSE_HP] = true;
			break;
		default:
			break;
	}
	
	if($skill->getSubSkill() != '') {
		switch($skill->getSubSkill()) {
			case SKILL_TYPE_POWER:
				$effects[EFFECT_DAMAGE_MULTIPLIER] = 3;
				break;
			case SKILL_TYPE_SHIELD:
				$effects[EFFECT_DAMAGE_DIVIDER] = 3;
				break;
			case SKILL_TYPE_ARCANA:
				$effects[EFFECT_MAGIC_DAMAGE_MULTIPLIER] = 3;
				break;
			case SKILL_TYPE_BARRIER:
				$effects[EFFECT_MAGIC_DAMAGE_DIVIDER] = 3;
				break;
			case SKILL_TYPE_PARALYZE:
				$effects[EFFECT_PARALYZE] = true;
				break;
			case SKILL_TYPE_COUNTER:
				$effects[EFFECT_REPEL_DAMAGE] = true;
				break;
			case SKILL_TYPE_CURSE:
				break;
			case SKILL_TYPE_DOUBLESTRIKE:
				break;
			case SKILL_TYPE_ABSORB:
				$effects[EFFECT_ABSORB_DAMAGE] = true;
				break;
			case SKILL_TYPE_REGEN:
				break;
			case SKILL_TYPE_CANCEL:
				break;
			case SKILL_TYPE_DISPEL:
				break;
			case SKILL_TYPE_ILLUSION:
				$effects[EFFECT_EVADE] = true;
				$effects[EFFECT_LOSE_HP] = true;
				break;
			default:
				break;
		}
	}
	
	return $effects;
}

function merge_effects_array($skill_effects_array, $battle_effects_array) {
	$final_array = array();
	
	foreach($skill_effects_array as $effect => $value) {
		if(array_key_exists($effect, $battle_effects_array)) {
			switch($effect) {
				case EFFECT_DAMAGE_MULTIPLIER:
				case EFFECT_MAGIC_DAMAGE_MULTIPLIER:
					$final_array[$effect] = $value * $battle_effects_array[$effect];
					break;
				case EFFECT_DAMAGE_DIVIDER:
				case EFFECT_MAGIC_DAMAGE_DIVIDER:
					$final_array[$effect] = $value / $battle_effects_array[$effect];
					break;
				case EFFECT_ATTACK_NUMBER:
					$final_array[$effect] = $value + $battle_effects_array[$effect];
					break;
				case EFFECT_DAMAGE_PARALYZE:
				case EFFECT_REPEL_DAMAGE:
				case EFFECT_ABSORB_DAMAGE:
				case EFFECT_DOUBLESTRIKE:
				case EFFECT_REGEN:
				case EFFECT_CURSE:
				case EFFECT_EVADE:
				case EFFECT_LOSE_HP:
					if(($value == true) or ($battle_effects_array[$effect] == true))
						$final_array[$effect] = true;
					else
						$final_array[$effect] = false;
					break;
			}
		}
		else {
			$final_array[$effect] = $value;
		}
	}
	
	foreach($battle_effects_array as $effect => $value) {
		if(!array_key_exists($effect, $final_array)) {
			$final_array[$effect] = $value;
		}
	}
	
	return $final_array;
}

function choose_monster_skill(Monster &$monster) {
	//choose a skill
	$skills = $monster->getSkills();
	if(count($skills) == 0) return false; //monster has skills ?
	
	$skills_names = $monster->getSkillsNames();
	$choice = mt_rand(0, count($skills) - 1);
	$skill = $skills[$choice];
	$skill_name = $skills_names[$choice];
	
	return array('skill'	=> $skill, 'name'	=> $skill_name);
}

function actor_attack($player_nb, Creature &$source, Creature &$target, AbstractBattle &$battle, $source_effect_array, $target_effect_array, $is_magic = false) {

	global $MANAGER, $MODE;
	
	$source_orb_effects = $MANAGER->get_player_orbs_effects($player_nb, $source, $target);
	$target_orb_effects = $MANAGER->get_player_orbs_effects($player_nb == 1 ? 2 : 1, $target, $source);
	
	$msg = '';
	$m = '';
	
	$attack_nb = $source_effect_array[EFFECT_ATTACK_NUMBER];
	
	for($i = 0 ; $i < $attack_nb ; $i++)
	{
		if(!$is_magic)
			$damage 	= (int) get_damage($player_nb, $source, $target, $target_effect_array[EFFECT_DAMAGE_DIVIDER], false);
		else
			$damage 	= (int) get_damage($player_nb, $source, $target, $target_effect_array[EFFECT_MAGIC_DAMAGE_DIVIDER], true);
			
		$hit 		= get_accuracy($player_nb, $source, $target, $is_magic);
		$crit 		= get_critical($player_nb, $source, $target);
		
		//hit success ?
		$rate = mt_rand(1, 100);
		if(($rate <= $hit) and !$target_effect_array[EFFECT_EVADE]) { //success
			//critical success ?
			$rate = mt_rand(1,100);
			
			$critical_multiplier = 1;
			if(!$target_orb_effects[ORB_EFFECT_NO_CRITICAL]['value'] and $rate <= $crit) { //success
				$critical_multiplier = 3;
				$msg .= "Coup critique !<br>";
			}
			
			if(!$is_magic)
				$total_damage = $damage * $critical_multiplier * $source_effect_array[EFFECT_DAMAGE_MULTIPLIER];
			else
				$total_damage = $damage * $critical_multiplier * $source_effect_array[EFFECT_MAGIC_DAMAGE_MULTIPLIER];
				
			$hp = 0;
			$to_damage = $target;
			$target_nb = ($player_nb == 1? 2 : 1);
			
			if($total_damage != 0) {
				
				//absorb damage ?
				if($target_effect_array[EFFECT_ABSORB_DAMAGE]) {
					if(!$MANAGER->give_hp($target_nb, $target, $total_damage)) return false;
					$msg .= "{$target->getName()} récupère $total_damage points de vie.<br>";
				}
				
				//repel damage ?
				if($target_effect_array[EFFECT_REPEL_DAMAGE]) {
					$to_damage = $source;
					$target_nb = $player_nb;
					
					if(!$MANAGER->give_hp($target_nb, $to_damage, -1 * $total_damage)) return false;
					$msg .= "{$to_damage->getName()} subit $total_damage dégâts.<br>";
				}
				
				// no absorb, no repel
				if(!$target_effect_array[EFFECT_ABSORB_DAMAGE] and !$target_effect_array[EFFECT_REPEL_DAMAGE]) {
					if(!$MANAGER->give_hp($target_nb, $to_damage, -1 * $total_damage)) return false;
					$msg .= "{$to_damage->getName()} subit $total_damage dégâts.<br>";
				}
				
				//if(!$MANAGER->give_hp($player_nb == 1? 2 : 1, $to_damage, -1 * $total_damage)) return false;
				
				// update battle data
				if($target_nb == 1) {
					set_battle_data('player1_last_damage', $total_damage);
					if(!$target_effect_array[EFFECT_ABSORB_DAMAGE]) {
						inc_battle_data('player2_total_damage_given', $total_damage);
						inc_battle_data('player1_total_damage_received', $total_damage);
					}
					
				} else {
					set_battle_data('player2_last_damage', $total_damage);
					if(!$target_effect_array[EFFECT_ABSORB_DAMAGE]) {
						inc_battle_data('player1_total_damage_given', $total_damage);
						inc_battle_data('player2_total_damage_received', $total_damage);
					}
				}
				
			}
			else {
				$msg .= "{$to_damage->getName()} subit 0 dégâts.<br>";
			}
			/*if($total_damage >= 0) {
				$msg .= "{$to_damage->getName()} subit $total_damage dégâts.<br>";
			}else {
				$total_damage *= -1;
				$msg .= "{$to_damage->getName()} récupère $total_damage points de vie.<br>";
			}*/
		}
		else { //failure
			$msg .= "{$target->getName()} évite l'attaque !<br>";
		}

	}
	
	//player effects
	/*$m = $MANAGER->after_attack_check_player_effects($player_nb, $source, $target, array('damage' => $total_damage,));
	if($m === false) return false;
	$msg .= $m;
	//target effects
	$m = $MANAGER->after_attack_check_player_effects($player_nb == 1 ? 2 : 1, $target, $source, array('damage' => $total_damage,));
	if($m === false) return false;
	$msg .= $m;*/
	
	return $msg;
}

function choose_monster_behavior(Monster $monster) {
	//monster behavior
	$behaviors = $monster->getBehaviors();
	//choose a behavior
	if(count($behaviors) <= 0) { return MONSTER_BEHAVIOR_IDLE; }
	$choice = mt_rand(0, count($behaviors) - 1);
	$behavior = $behaviors[$choice];
	
	return $behavior;
}

function use_skill(&$json_array, &$opponent_json_array, $player_nb, AbstractBattle &$battle, Creature &$launcher, Skill $skill, Creature &$target, $target_action, $target_skill = false) {
	global $_SKILLS_DATA, $MODE, $MANAGER;
	$msg = '';
	
	//skill is valid ?
	if(array_key_exists($skill->getType(), $_SKILLS_DATA)) {
		
		//decrease user FP
		$fp = $MANAGER->get_fp($launcher, $player_nb);
		if($fp < $skill->getPF()) { $msg .= "{$launcher->getName()} n'a pas assez de PF !<br>"; return $msg; }
		if(!$MANAGER->give_fp($player_nb, $launcher, -1 * $skill->getPF())) return false;
		//update battle cooldowns
		if(!$MANAGER->add_skill($player_nb, $skill->getType(), $battle->getTurn())) return false;
	}
	
	$m = apply_skill_effect($json_array, $opponent_json_array, $player_nb, $battle, $launcher, $skill, $skill->getType(), $target, $target_action, $target_skill);
	if($m !== false) $msg .= $m;
	else return false;
	
	if($skill->getSubSkill() != '') {
		$m = apply_skill_effect($json_array, $opponent_json_array, $player_nb, $battle, $launcher, $skill, $skill->getSubSkill(), $target, $target_action, $target_skill);
		if($m !== false) $msg .= $m;
		else return false;
	}
	return $msg;
}

function apply_skill_effect(&$json_array, &$opponent_json_array, $player_nb, AbstractBattle &$battle, Creature &$launcher, Skill &$skill, $skill_effect, Creature &$target, $target_action, $target_skill = false) {
	global $_SKILLS_DATA, $MODE, $MANAGER;
	$msg = '';
	
	switch($skill_effect) {
		/* Puissance */
		case SKILL_TYPE_POWER:
			$msg .= "{$launcher->getName()} attaque avec toute sa puissance !<br>";
			$m = actor_attack($player_nb, $launcher, $target, $battle, get_skill_effects($skill), get_actor_effects($player_nb == 1 ? 2 : 1, $battle, $target, $launcher, $target_action, $target_skill));
			if($m !== false) $msg .= $m;
			else return false;
			
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Bouclier */
		case SKILL_TYPE_SHIELD:
			$msg .= "{$launcher->getName()} bloque l'attaque de toutes ses forces !<br>";
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Arcanes */
		case SKILL_TYPE_ARCANA:
			$msg .= "{$launcher->getName()} attaque avec une magie puissante !<br>";
			$m = actor_attack($player_nb, $launcher, $target, $battle, get_skill_effects($skill), get_actor_effects($player_nb == 1 ? 2 : 1, $battle, $target, $launcher, $target_action, $target_skill), true);
			if($m !== false) $msg .= $m;
			else return false;
			
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Barrière */
		case SKILL_TYPE_BARRIER:
			$msg .= "{$launcher->getName()} s'entoure d'une barrière magique !<br>";
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Immobilisation */
		case SKILL_TYPE_PARALYZE:
			$msg .= "{$launcher->getName()} empêche l'adversaire de bouger !<br>";
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Contre */
		case SKILL_TYPE_COUNTER:
			$msg .= "{$launcher->getName()} retourne l'attaque à son adversaire !<br>";
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Malédiction */
		case SKILL_TYPE_CURSE:
			$msg .= "{$launcher->getName()} affaiblit son adversaire !<br>";
			
			if(!$MANAGER->add_active_skill($player_nb, SKILL_TYPE_CURSE, array('start' => $battle->getTurn(), 'duration' => 4))) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Double Frappe */
		case SKILL_TYPE_DOUBLESTRIKE:
			$m = actor_attack($player_nb, $launcher, $target, $battle, get_skill_effects($skill), get_actor_effects($player_nb == 1 ? 2 : 1, $battle, $target, $launcher, $target_action, $target_skill));
			if($m !== false) $msg .= $m;
			else return false;
			
			$msg .= "{$launcher->getName()} se prépare pour le prochain tour !<br>";
			
			if(!$MANAGER->add_active_skill($player_nb, SKILL_TYPE_DOUBLESTRIKE, array('start' => $battle->getTurn(), 'duration' => 1))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Absorption */
		case SKILL_TYPE_ABSORB:
			$msg .= "{$launcher->getName()} annule les dégâts de son adversaire !<br>";
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Régénération */
		case SKILL_TYPE_REGEN:
			$msg .= "{$launcher->getName()} regagne progressivement des forces !<br>";
			
			if(!$MANAGER->add_active_skill($player_nb, SKILL_TYPE_REGEN, array('start' => $battle->getTurn(), 'duration' => 4))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Annulation */
		case SKILL_TYPE_CANCEL:
			$msg .= "{$launcher->getName()} annule tous les effets progressifs.<br>";
			
			if(!$MANAGER->reset_active_skills(1) or !$MANAGER->reset_active_skills(2)) return false;
			
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Dissipation */
		case SKILL_TYPE_DISPEL:
			$msg .= "{$launcher->getName()} annule les effets progressifs de {$target->getName()}.<br>";
			
			if(!$MANAGER->reset_active_skills($player_nb == 1 ? 2 : 1)) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Illusion */
		case SKILL_TYPE_ILLUSION:
			$msg .= "{$launcher->getName()} crée une illusion !<br>";
			
			if(!$MANAGER->add_active_skill($player_nb, SKILL_TYPE_ILLUSION, array('start' => $battle->getTurn(), 'duration' => 1))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Drain de vie */
		case SKILL_TYPE_LIFEDRAIN:
			/*$target_hp = $target->getMaxPV();
			$drain_per = 0.025 + (0.005 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$drain = (int) floor($target_hp * $drain_per );*/
			
			$base_drain = 10; // drains 10 PV with DEFAULT_FLUX points
			$flux = $MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX;
			$drain = $base_drain + (int) floor(1.5 * $flux);
			if($drain < 0) $drain = 0;
			if($drain > $target->getPV()) $drain = $target->getPV();
			
			if($drain != 0) {
				if(!$MANAGER->give_hp($player_nb, $launcher, $drain)
				or !$MANAGER->give_hp($player_nb == 1 ? 2 : 1, $target, -1 * $drain))
					return false;
			}
			
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			
			$msg .= "{$launcher->getName()} aspire $drain PV de son adversaire !<br>";
			break;
		/* Drain de magie */
		case SKILL_TYPE_MAGICDRAIN:
			/*$target_fp = $target->getMaxPF();
			$drain_per = 0.025 + (0.005 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$drain = (int) floor($target_fp * $drain_per );*/
			
			$base_drain = 10; // drains 10 PF with DEFAULT_FLUX points
			$flux = $MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX;
			$drain = $base_drain + (int) floor(1.25 * $flux);
			if($drain < 0) $drain = 0;
			if($drain > $target->getPF()) $drain = $target->getPF();
			
			if($drain != 0) {
				if(!$MANAGER->give_fp($player_nb, $launcher, $drain)
				or !$MANAGER->give_fp($player_nb == 1 ? 2 : 1, $target, -1 * $drain))
					return false;
			}
			
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			
			$msg .= "{$launcher->getName()} aspire $drain PF de son adversaire !<br>";
			break;
		/* Fureur */
		case SKILL_TYPE_WRATH:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($launcher->getBaseAtk() * $buff_per);
			
			$msg .= "L'attaque de {$launcher->getName()} augmente de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_WRATH, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => BUFF_TYPE_ATTACK, 'value' => $buff,))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Protection */
		case SKILL_TYPE_PROTECTION:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($launcher->getBaseDef() * $buff_per);
			
			$msg .= "La défense de {$launcher->getName()} augmente de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_PROTECTION, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => BUFF_TYPE_DEFENSE, 'value' => $buff,))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Célérité */
		case SKILL_TYPE_GODSPEED:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($launcher->getBaseSpd() * $buff_per);
			
			$msg .= "La vitesse de {$launcher->getName()} augmente de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_GODSPEED, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => BUFF_TYPE_SPEED, 'value' => $buff,))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Concentration */
		case SKILL_TYPE_FOCUS:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($launcher->getBaseFlux() * $buff_per);
			
			$msg .= "Le flux de {$launcher->getName()} augmente de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_FOCUS, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => BUFF_TYPE_FLUX, 'value' => $buff,))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Blindage */
		case SKILL_TYPE_SHIELDING:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($launcher->getBaseRes() * $buff_per);
			
			$msg .= "La résistance de {$launcher->getName()} augmente de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_SHIELDING, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => BUFF_TYPE_RESISTANCE, 'value' => $buff,))) return false;
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			break;
		/* Intimidation */
		case SKILL_TYPE_INTIMIDATION:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($target->getBaseAtk() * $buff_per);
			
			$msg .= "L'attaque de {$target->getName()} baisse de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_INTIMIDATION, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => DEBUFF_TYPE_ATTACK, 'value' => $buff,))) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Brise-Armure */
		case SKILL_TYPE_ARMORBREAK:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($target->getBaseDef() * $buff_per);
			
			$msg .= "La défense de {$target->getName()} baisse de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_ARMORBREAK, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => DEBUFF_TYPE_DEFENSE, 'value' => $buff,))) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Entrave */
		case SKILL_TYPE_STUN:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($target->getBaseSpd() * $buff_per);
			
			$msg .= "La vitesse de {$target->getName()} baisse de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_STUN, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => DEBUFF_TYPE_SPEED, 'value' => $buff,))) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Sceau Magique */
		case SKILL_TYPE_MAGICSEAL:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($target->getBaseFlux() * $buff_per);
			
			$msg .= "Le flux de {$target->getName()} baisse de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_MAGICSEAL, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => DEBUFF_TYPE_FLUX, 'value' => $buff,))) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Fragilité */
		case SKILL_TYPE_FRAGILITY:
			$buff_per = 0.1 + (0.025 * ($MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX) / 5);
			$buff = (int) floor($target->getBaseRes() * $buff_per);
			
			$msg .= "La résistance de {$target->getName()} baisse de $buff !<br>";
			
			if(!$MANAGER->add_buff($player_nb, SKILL_TYPE_FRAGILITY, array('start' => $battle->getTurn(), 'duration' => 2, 'type' => DEBUFF_TYPE_RESISTANCE, 'value' => $buff,))) return false;
			skill_anim_in_json($opponent_json_array, $skill_effect, $skill->getElement());
			break;
		/* Soins */
		case SKILL_TYPE_HEAL:
			$base_heal = 30; // heals 30 PV with DEFAULT_RESISTANCE points
			$res = $MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_RESISTANCE) - DEFAULT_RESISTANCE;
			$heal = $base_heal + (int) floor(3 * $res);
			if($heal < 0) $heal = 0;
			if($heal > $target->getPV()) $heal = $target->getPV();
			
			if($heal != 0) {
				if(!$MANAGER->give_hp($player_nb, $launcher, $heal))
					return false;
			}
			
			skill_anim_in_json($json_array, $skill_effect, $skill->getElement());
			
			$msg .= "{$launcher->getName()} récupère $heal PV !<br>";
			break;
		/* Sang énergisant */
		case SKILL_TYPE_ENERGY_BLOOD:
			$base = 25;
			$flux = $MANAGER->get_stat_with_buff($launcher, $player_nb, STAT_FLUX) - DEFAULT_FLUX;
			$res = $base + (int) ($flux * $base / 15);
			if($res <= 0) $res = 1;
			if($res > $target->getPV()) $res = $target->getPV() - 1;
			
			if($res != 0) {
				if(!$MANAGER->give_fp($player_nb, $launcher, $res) or !$MANAGER->give_hp($player_nb, $launcher, -1 * $res))
					return false;
			}
			
			$msg .= "{$launcher->getName()} sacrifie $res PV et récupère $res PF !<br>";
			break;
		default:
			break;
	}
	
	return $msg;
}

function battle_use_item($player_nb, AbstractBattle &$battle, Creature &$launcher, $item_slot, Creature &$target, $target_action, $target_skill = false) {
	
	global $db;
	
	$msg = '';
	
	if(get_class($launcher) !== "Player") return false;
	
	$item_type = RPGInventories::getTypeOfItemByPlayerAndSlot($launcher->getId(), $item_slot);
	
	switch($item_type) {
		case 'syringe':
			{
				$item = RPGInventories::getItemByPlayerAndSlot($launcher, $item_slot);
				
				$db->sql_transaction('begin');
				
				$pv_update = true;
				$pf_update = true;
				
				// heal pv ?
				if($item->getPV() > 0) {
					if($launcher->getPV() < $launcher->getMaxPV()) {
						$pv_update = player_heal_pv($launcher, $item->getPV());
					}
					
					if($pv_update) $msg .= "{$launcher->getName()} récupère {$item->getPV()} PV.<br>";
				}
				// heal pf ?
				if($item->getPF() > 0) {
					if($launcher->getPF() < $launcher->getMaxPF()) {
						$pf_update = player_heal_pf($launcher, $item->getPF());
					}
					
					if($pf_update) $msg .= "{$launcher->getName()} récupère {$item->getPF()} PF.<br>";
				}
				
				if($pv_update and $pf_update and (drop_item($launcher, $item_slot, 1) === true)) { $db->sql_transaction('commit'); return $msg; }
				else { $db->sql_transaction('cancel'); return false; }
			}
			break;
		case 'cloth':
		case 'leggings':
		case 'glove':
		case 'shoe':
		case 'orb':
		case 'ralz':
		default:
			return false;
	}
}

function get_damage($player_nb, Creature $source, Creature $target, $damage_divider = 1, $is_magic = false) {
	global $MANAGER;
	
	if($damage_divider == 0) $damage_divider = 1;
	
	$attack_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_ATTACK);
	$flux_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_FLUX);
	
	$target_defense_buff = $MANAGER->get_buff_by_stat($player_nb == 1 ? 2 : 1, $target, $source, STAT_DEFENSE);
	$target_res_buff = $MANAGER->get_buff_by_stat($player_nb == 1 ? 2 : 1, $target, $source, STAT_RESISTANCE);
	
	if(!$is_magic)
		$damage = $source->getBattleDamage($attack_buff) - $target->getBattleDefense($target_defense_buff);
	else
		$damage = $source->getBattleMagicDamage($flux_buff) - $target->getBattleMagicDefense($target_res_buff);
		
	if($damage < 0) $damage = 0;
	return (int) ($damage / $damage_divider);
}

function get_accuracy($player_nb, Creature $source, Creature $target, $is_magic = false) {
	global $MANAGER;
	
	$attack_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_ATTACK);
	$speed_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target,STAT_SPEED);
	$flux_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_FLUX);
	
	$target_speed_buff = $MANAGER->get_buff_by_stat($player_nb == 1 ? 2 : 1, $target, $source, STAT_SPEED);
	
	if(!$is_magic)
		$hit = $source->getBattleAccuracy($attack_buff, $speed_buff) - $target->getBattleEvade($target_speed_buff);
	else
		$hit = $source->getBattleMagicAccuracy($flux_buff, $speed_buff) - $target->getBattleEvade($target_speed_buff);
	
	if($hit < 0) $hit = 0;
	if($hit > 100) $hit = 100;
	return (int) floor($hit);
}

function get_critical($player_nb, Creature $source, Creature $target) {
	global $MANAGER;
	
	$attack_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_ATTACK);
	$flux_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_FLUX);
	$spd_buff = $MANAGER->get_buff_by_stat($player_nb, $source, $target, STAT_SPEED);
	
	$target_defense_buff = $MANAGER->get_buff_by_stat($player_nb == 1 ? 2 : 1, $target, $source, STAT_DEFENSE);
	$target_res_buff = $MANAGER->get_buff_by_stat($player_nb == 1 ? 2 : 1, $target, $source, STAT_RESISTANCE);
	
	$crit = (int) floor(($source->getBattleCritical($spd_buff) - $target->getBattleDodge($target_defense_buff, $target_res_buff)) / 2);
	
	//orb bonus
	$crit += $MANAGER->get_orb_bonus_by_stat($player_nb, $source, $target, STAT_CRITICAL);
	
	if($crit < 0) $crit = 0;
	if($crit > 100) $crit = 100;
	return $crit;
}

function get_pvp_experience(Player $target, $target_hp, $target_fp, Player &$player) {
	$player_karma = $player->getKarma();
	if($player_karma < 0 or $player_karma > MAX_KARMA) $player_karma = 0;
	
	$exp = ( ($target_hp + $target_fp ) / 2 + $target->getAttack() * 5 + $target->getDefense() * 5 + $target->getResistance() * 5 + $target->getSpeed() * 5 + $target->getFlux() * 5 ) / 7;
	$exp *= (1 + ($player_karma / 10)); //karma bonus
	
	$clan = $player->getClan();
	if($clan) $exp *= (1 + ($clan->getXPBonus() / 100)); //clan bonus
	
	//$exp *= ($target->getLevel() / $player->getLevel());
	$exp *= (1 + 0.1 * ($target->getLevel() - $player->getLevel()));
	if($exp < 0) $exp = 0;
	
	return (int) floor($exp);
}

function get_monster_experience(Monster $monster, Player &$player) {
	$player_karma = $player->getKarma();
	if($player_karma < 0 or $player_karma > MAX_KARMA) $player_karma = 0;
	
	$exp = $monster->getXP();
	//$exp = ($monster->getMaxPV() / 2 + $monster->getMaxPF() / 2 + $monster->getAttack() * 5 + $monster->getDefense() * 5 + $monster->getResistance() * 5 + $monster->getSpeed() * 5 + $monster->getFlux() * 5) / 7;
	$exp *= (1 + ($player_karma / 10)); //karma bonus
	
	$clan = $player->getClan();
	if($clan) $exp *= (1 + ($clan->getXPBonus() / 100));
	
	//$exp *= ($monster->getLevel() / $player->getLevel());
	$exp *= (1 + 0.1 * ($monster->getLevel() - $player->getLevel()));
	if($exp < 0) $exp = 0;
	
	return (int) floor($exp);
}

function get_monster_ralz(Monster $monster, Player $player) {
	$ralz = $monster->getRalz();
	
	$player_clan = $player->getClan();
	
	if($player_clan) { //ralz clan bonus
		$ralz *= 1 + ($player_clan->getRalzBonus() / 100);
		$ralz = (int) floor($ralz);
	}
	
	return $ralz;
}

function get_player_lose_experience(Player $player) {
	global $MODE;
	
	if($level >= MAX_LEVEL) return 0; //if lvl max, no xp to lose
	
	$exp_to_lvl_up = RPGXP::getXPByLvl($player->getLevel());
	
	if($MODE == 'pve')
		return (int) floor(0.05 * $exp_to_lvl_up);
	else if($MODE == 'pvp')
		return (int) floor(0.02 * $exp_to_lvl_up);
	else
		return 0;
		
}

function get_monster_drops(Monster $monster, PVEBattle& $battle) {
	//get list of droppable items
	$items = $monster->getDropsByAreaPart($battle->getAreaPartId());
	if(count($items) == 0) return false;
	
	$drops_number = 0;
	$max_items = count($items);
	$max_drops = $monster->getDropsNumber();
	
	$dropped_items = array();
	
	foreach($items as $r => $i) {
	
		if( ($max_drops === false) or ($drops_number < $max_drops) ) {
		
			//shuffle array to allow each item with same rate to drop ( => not always the same item in case of only one drop with a specific rate)
			$i = shuffle_assoc($i);
	
			foreach($i as $item_data) {
			
				if( ($max_drops === false) or ($drops_number < $max_drops) ) {
					do {
						$rate = rand_float() * 100;
					} while($rate == 0);
					
					if($rate <= $r) {
						$drops_number += 1;;
						
						$item = get_item($item_data['item_id'], $item_data['item_type']);
						if(!$item) continue;
						
						$dropped_items[] = $item;
					}
				}
				
			}

		}
		
	}
	
	if(count($dropped_items) == 0) return false;
	
	return $dropped_items;
}

function get_monster_item_dropped(Monster $monster, PVEBattle& $battle) {
	//get list of droppable items
	$items = $monster->getDropsByAreaPart($battle->getAreaPartId());
	if(count($items) == 0) return false;
	
	do {
		$rate = rand_float() * 100;
	} while($rate == 0);
	
	$items_list = array();
	foreach($items as $r => $i) {
		if($rate <= $r) { $items_list = $i; break; }
	}
	
	if(count($items_list) == 0) { return false; }
	
	//select item among the list
	$choice = mt_rand(0, count($items_list) - 1);
	$item_data = $items_list[$choice];
	
	switch($item_data['item_type']) {
	
		case 'syringe':
			return RPGSyringes::getSyringe($item_data['item_id']);
		case 'special':
			return RPGSpecials::getSpecial($item_data['item_id']);
		case 'cloth':
			return new SetPart(RPGClothes::getCloth($item_data['item_id']), ARMOR_CLOTH);
		case 'leggings':
			return new SetPart(RPGLeggings::getLegging($item_data['item_id']), ARMOR_LEGGINGS);
		case 'gloves':
			return new SetPart(RPGGloves::getGlove($item_data['item_id']), ARMOR_GLOVES);
		case 'shoes':
			return new SetPart(RPGShoes::getShoe($item_data['item_id']), ARMOR_SHOES);
		case 'orb':
			//echo "item_id : {$item_data['item_id']}";
			return RPGOrbs::getOrb($item_data['item_id']);
		default:
			return false;
	}
}

function can_use_skill($mode, $player_nb, Creature &$launcher, Skill $skill, AbstractBattle &$battle) {
	$can_use = false;
	
	//player 1
	if($player_nb == 1) {
		$skill_last_turn = $battle->getLastTurnOfPlayer1Skill($skill->getType());
		if($skill_last_turn === 0) {
			if( $launcher->getPF() >= $skill->getPF() ) $can_use = true;
		}
		else if(($battle->getTurn() - $skill_last_turn) > $skill->getCooldown()) {
			if( $launcher->getPF() >= $skill->getPF() ) $can_use = true;
		}
	}
	
	else if($player_nb == 2) {
		$skill_last_turn = $battle->getLastTurnOfPlayer2Skill($skill->getType());
		
		//pve & event
		if( ($mode == 'pve') or ($mode == 'event') ) {
		
			if($skill_last_turn === 0) {
				if( $battle->getMonsterFP() >= $skill->getPF() ) $can_use = true;
			}
			else if(($battle->getTurn() - $skill_last_turn) > $skill->getCooldown()) {
				if( $battle->getMonsterFP() >= $skill->getPF() ) $can_use = true;
			}

		}
		//pvp
		else if($mode == 'pvp') {
			
			$skill_last_turn = $battle->getLastTurnOfPlayer2Skill($skill->getType());
			if($skill_last_turn === 0) {
				if( $launcher->getPF() >= $skill->getPF() ) $can_use = true;
			}
			else if(($battle->getTurn() - $skill_last_turn) > $skill->getCooldown()) {
				if( $launcher->getPF() >= $skill->getPF() ) $can_use = true;
			}
		}
		
	}
	
	/*if(get_class($launcher) === "Player") {
		$skill_last_turn = $battle->getLastTurnOfPlayer1Skill($skill->getType());
		if($skill_last_turn === 0) {
			if( $launcher->getPF() >= $skill->getPF() ) $can_use = true;
		}
		else if(($battle->getTurn() - $skill_last_turn) > $skill->getCooldown()) {
			if( $launcher->getPF() >= $skill->getPF() ) $can_use = true;
		}
	}
	else if(get_class($launcher) === "Monster") {
		$skill_last_turn = $battle->getLastTurnOfPlayer2Skill($skill->getType());
		if($skill_last_turn === 0) {
			if( $battle->getMonsterFP() >= $skill->getPF() ) $can_use = true;
		}
		else if(($battle->getTurn() - $skill_last_turn) > $skill->getCooldown()) {
			if( $battle->getMonsterFP() >= $skill->getPF() ) $can_use = true;
		}
	}*/
		
	return $can_use;
}

function action_anim_in_json(&$json_array, $action_name) {
	global $PLAYER, $_BATTLE_ACTIONS_ANIMS, $_BATTLE_ANIMS;
	
	if(!isset($_BATTLE_ACTIONS_ANIMS[$action_name])) return;
	if(isset($json_array['anim_priority']) and $json_array['anim_priority'] > $_BATTLE_ACTIONS_ANIMS[$action_name]['priority']) return;
	
	if($PLAYER->animationsEnabled()) {
		$json_array['anim_path'] = BATTLE_ANIMS_PATH . $_BATTLE_ACTIONS_ANIMS[$action_name]['path'];
		$json_array['anim_time'] = $_BATTLE_ACTIONS_ANIMS[$action_name]['duration'];
		$json_array['anim_width'] = $_BATTLE_ACTIONS_ANIMS[$action_name]['width'];
		$json_array['anim_height'] = $_BATTLE_ACTIONS_ANIMS[$action_name]['height'];
		$json_array['anim_frames'] = $_BATTLE_ACTIONS_ANIMS[$action_name]['frames'];
		$json_array['anim_delay'] = $_BATTLE_ACTIONS_ANIMS[$action_name]['delay'];
		$json_array['anim_priority'] = $_BATTLE_ACTIONS_ANIMS[$action_name]['priority'];
	}
	
	if($PLAYER->soundEnabled() and isset($_BATTLE_ACTIONS_ANIMS[$action_name]['sound'])) {
		$json_array['anim_sound']	= BATTLE_SOUNDS_PATH . $_BATTLE_ACTIONS_ANIMS[$action_name]['sound'];
	}
	
}

function skill_anim_in_json(&$json_array, $skill_effect, $skill_element) {
	global $PLAYER, $_SKILLS_ANIMS, $_SKILLS_DATA, $_BATTLE_ANIMS;
	
	put_skill_data_in_json($json_array, $skill_effect, $skill_element);
	//if($skill->getSubSkill() != '') put_skill_data_in_json($json_array, $skill->getSubSkill(), $skill->getElement());
	
	/*if(!array_key_exists($skill->getType(), $_SKILLS_DATA) or !array_key_exists($skill->getSubSkill(), $_SKILLS_DATA)) return;
	if(isset($json_array['anim_priority']) and $json_array['anim_priority'] > $_SKILLS_DATA[$skill->getType()]['priority']) return;
	
	if($PLAYER->animationsEnabled() and isset($_SKILLS_ANIMS[$skill->getType()][$skill->getElement()])) {
		$json_array['anim_path'] = BATTLE_ANIMS_PATH . $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['path'];
		$json_array['anim_time'] = $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['duration'];
		$json_array['anim_width'] = $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['width'];
		$json_array['anim_height'] = $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['height'];
		$json_array['anim_frames'] = $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['frames'];
		$json_array['anim_delay'] = $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['delay'];
		$json_array['anim_priority'] = $_SKILLS_DATA[$skill->getType()]['priority'];
	}
	
	if($PLAYER->soundEnabled()) {
		if(isset($_SKILLS_DATA[$skill->getType()]['sound'])) {
			$json_array['anim_sound']	= BATTLE_SOUNDS_PATH . $_SKILLS_DATA[$skill->getType()]['sound'];
		}
		
		else if(isset($_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['sound']))
			$json_array['anim_sound']	= BATTLE_SOUNDS_PATH . $_SKILLS_ANIMS[$skill->getType()][$skill->getElement()]['sound'];
	}*/
	
	
}

function put_skill_data_in_json(&$json_array, $skill_effect, $skill_element) {
	global $PLAYER, $_SKILLS_ANIMS, $_SKILLS_DATA, $_BATTLE_ANIMS;
	
	if(!array_key_exists($skill_effect, $_SKILLS_DATA)) return;
	if(isset($json_array['anim_priority']) and $json_array['anim_priority'] > $_SKILLS_DATA[$skill_effect]['priority']) return;
	if(!array_key_exists($skill_element, $_SKILLS_ANIMS[$skill_effect])) return;
	
	if($PLAYER->animationsEnabled()) {
		$json_array['anim_path'] = BATTLE_ANIMS_PATH . $_SKILLS_ANIMS[$skill_effect][$skill_element]['path'];
		$json_array['anim_time'] = $_SKILLS_ANIMS[$skill_effect][$skill_element]['duration'];
		$json_array['anim_width'] = $_SKILLS_ANIMS[$skill_effect][$skill_element]['width'];
		$json_array['anim_height'] = $_SKILLS_ANIMS[$skill_effect][$skill_element]['height'];
		$json_array['anim_frames'] = $_SKILLS_ANIMS[$skill_effect][$skill_element]['frames'];
		$json_array['anim_delay'] = $_SKILLS_ANIMS[$skill_effect][$skill_element]['delay'];
		$json_array['anim_priority'] = $_SKILLS_DATA[$skill_effect]['priority'];
	}
	
	if($PLAYER->soundEnabled()) {
		if(isset($_SKILLS_DATA[$skill_effect]['sound'])) {
			$json_array['anim_sound']	= BATTLE_SOUNDS_PATH . $_SKILLS_DATA[$skill_effect]['sound'];
		}
		
		else if(isset($_SKILLS_ANIMS[$skill_effect][$skill_element]['sound']))
			$json_array['anim_sound']	= BATTLE_SOUNDS_PATH . $_SKILLS_ANIMS[$skill_effect][$skill_element]['sound'];
	}
}


function set_battle_data($field, $value) {
	global $BATTLE_DATA;
	
	$BATTLE_DATA[$field] = $value;
}

function inc_battle_data($field, $value) {
	global $BATTLE_DATA;
	
	if(is_numeric($BATTLE_DATA[$field]))
		$BATTLE_DATA[$field] += $value;
}

/*function manage_event_ending(EventBattle &$battle) {
	//damage given ranking
	$dg_ranking = RPGEventBattles::getDamageGivenRanking($battle->getToken());
	if(!$dg_ranking) { // no ranking
		return false;
	}
	
	//damage received ranking
	$dr_ranking = RPGEventBattles::getDamageReceivedRanking($battle->getToken());
	if(!$dr_ranking) { // no ranking
		return false;
	}
	
	$dg_text = 'Classement en fonction des dégats infligés :' . PHP_EOL;
	foreach($dg_ranking as $rank => $data) {
		$dg_text .= ("Rang $rank : {$data['username']} avec {$data['total_damage_given']}" . PHP_EOL);
	}
	
	$dr_text = 'Classement en fonction des dégats subits :' . PHP_EOL;
	foreach($dr_ranking as $rank => $data) {
		$dr_text .= ("Rang $rank : {$data['username']} avec {$data['total_damage_received']}" . PHP_EOL);
	}
	
	//give event items
	$items_text = RPGEventBattles::giveEventItems((string) $battle->getToken());
	if($items_text === false) return false;
	
	$text = 'Le world boss a été vaincu !' . PHP_EOL . PHP_EOL . $dg_text . PHP_EOL . $dr_text . PHP_EOL . $items_text;
	
	rpg_post("Fin de l'event", $text, 'reply', $battle->getForumId(), $battle->getTopicId());
	
	return true;
}*/
?>