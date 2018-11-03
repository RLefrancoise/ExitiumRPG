<?php

include_once(__DIR__ . '/../database/RPGPlayers.class.php');
include_once(__DIR__ . '/../database/RPGPlayersStats.class.php');
include_once(__DIR__ . '/../database/RPGSets.class.php');
include_once(__DIR__ . '/../database/RPGXP.class.php');

function player_heal_pv(Player &$player, $pv) {
	$new_pv = $player->getPV() + $pv;
	if($new_pv > $player->getMaxPV()) $new_pv = $player->getMaxPV();
	if($new_pv == $player->getPV()) return true;
	
	$pv_update = RPGPlayers::setPVOfPlayer($player, $new_pv);
	
	return $pv_update;
}

function player_heal_pf(Player &$player, $pf) {
	$new_pf = $player->getPF() + $pf;
	if($new_pf > $player->getMaxPF()) $new_pf = $player->getMaxPF();
	if($new_pf == $player->getPF()) return true;
	
	$pf_update = RPGPlayers::setPFOfPlayer($player, $new_pf);
	
	return $pf_update;
}

function player_give_ralz(Player &$player, $ralz) {
	if($ralz == 0) return true;
	
	$success = false;
	
	if($player->getRalz() + $ralz > 0) {
		if(RPGPlayers::setRalzByPlayer($player, $player->getRalz() + $ralz))
			$success = true;
		else
			$success = false;
	} else {
		if($player->getRalz() > 0) {
			if(RPGPlayers::setRalzByPlayer($player, 0))
				$success = true;
			else
				$success = false;
		} else {
			$success = true;
		}
	}
	
	if($success) {
		if(!RPGPlayersStats::setStatByPlayer($player, 'max_ralz_own', $player->getRalz())) $success = false;
	}
	
	return $success;
}

function player_give_exp(Player &$player, $exp) {
	if($exp == 0) return true;
	
	$level = $player->getLevel();
	if($level >= MAX_LEVEL) return true; //if lvl max, no more xp to give
	
	$exp_to_lvl_up = RPGXP::getXPByLvl($level);
	$player_xp = $player->getXP();
	if($player_xp === $exp) return true; //if xp is the same, no need to update
	
	//$new_xp = ($player_xp + $exp > 0) ? $player_xp + $exp : 0;
	$new_xp = $player_xp + $exp;
	if( ($player_xp == 0) and ($new_xp == 0) ) return true;
	
	if($new_xp < $exp_to_lvl_up) { //no level up
		if(!RPGPlayers::setXPOfPlayer($player, $new_xp)) return false;
	} else {
		if(!RPGPlayers::setLevelOfPlayer($player, $level + 1)) return false; //up level
		if(!RPGPlayers::setXPOfPlayer($player, $new_xp - $exp_to_lvl_up)) return false; //give exp
		if( ($player->getXP() > RPGXP::getXPByLvl($player->getLevel())) and !player_give_exp($player, 0)) return false;
	}
	
	return true;
}

?>