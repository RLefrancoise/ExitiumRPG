<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGPlayersStats.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/status_functions.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/string_functions.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL | PLAYER_INVENTORY | PLAYER_ORBS | PLAYER_EQUIPMENT);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

if ( isset($_GET['mode']) ) {
	$mode = request_var('mode', '');
	
	if($mode === 'rest') {
		//need heal ?
		if( ($player->getPV() == $player->getMaxPV()) and ($player->getPF() == $player->getMaxPF()) ) { echo 'no_heal'; return; }
		
		//check money
		$ralz = $player->getRalz();
		if($ralz - REST_PRICE < 0) { echo 'no_money'; return; }
		
		//update life
		$max_pv = $player->getMaxPV();
		$max_pf = $player->getMaxPF();
		$pv		= $player->getPV();
		$pf		= $player->getPF();
		$rest_pv = ($pv + $max_pv / 2) < $max_pv ? $pv + $max_pv / 2 : $max_pv;
		$rest_pf = ($pf + $max_pf / 2) < $max_pf ? $pf + $max_pf / 2 : $max_pf;
		
		$db->sql_transaction('begin');
		
		if(!RPGPlayersStats::incrementStatByPlayer($player, 'inn_times')) { echo 'error'; return; }
		
		if($player->getPV() < $player->getMaxPV()) {
			if(!RPGPlayers::setPVOfPlayer($player, (int) $rest_pv)) { echo 'error'; return; }
		}
		if($player->getPF() < $player->getMaxPF()) {
			if(!RPGPlayers::setPFOfPlayer($player, (int) $rest_pf)) { echo 'error'; return; }
		}
		if( RPGPlayers::setRalzByPlayer($player, $ralz - REST_PRICE) ) {
			echo 'rest_ok';
		} else {
			echo 'error';
			return;
		}
		
		$db->sql_transaction('commit');
		
	} else if($mode === 'sleep') {
		//need heal ?
		if( ($player->getPV() == $player->getMaxPV()) and ($player->getPF() == $player->getMaxPF()) ) { echo 'no_heal'; return; }
		
		//check money
		$ralz = $player->getRalz();
		if($ralz - SLEEP_PRICE < 0) { echo 'no_money'; return; }
		
		//update life
		$max_pv = $player->getMaxPV();
		$max_pf = $player->getMaxPF();
		$pv		= $player->getPV();
		$pf		= $player->getPF();
		$sleep_pv = ($pv + $max_pv) < $max_pv ? $pv + $max_pv : $max_pv;
		$sleep_pf = ($pf + $max_pf) < $max_pf ? $pf + $max_pf : $max_pf;
		
		$db->sql_transaction('begin');
		
		if(!RPGPlayersStats::incrementStatByPlayer($player, 'inn_times')) { echo 'error'; return; }
		
		if($player->getPV() < $player->getMaxPV()) {
			if(!RPGPlayers::setPVOfPlayer($player, (int) $sleep_pv)) { echo 'error'; return; }
		}
		if($player->getPF() < $player->getMaxPF()) {
			if(!RPGPlayers::setPFOfPlayer($player, (int) $sleep_pf)) { echo 'error'; return; }
		}
		
		if(RPGPlayers::setRalzByPlayer($player, $ralz - SLEEP_PRICE) ) {
			echo 'sleep_ok';
		} else {
			echo 'error';
			return;
		}
		$db->sql_transaction('commit');
	} else {
		echo 'error';
	}
	
} else {
    echo "error";
}
 
?>