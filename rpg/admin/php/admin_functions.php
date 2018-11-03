<?php

require_once(__DIR__ . '/../../../common.php');
require_once(__DIR__ . '/../../database/RPGUsersPlayers.class.php');
require_once(__DIR__ . '/../../database/RPGPlayers.class.php');
require_once(__DIR__ . '/../../database/RPGWeapons.class.php');

function reset_player($player_id) {
	global $db;
	
	$player = RPGUsersPlayers::getPlayerByPlayerId($player_id);
	if(!$player) return false;
	
	$db->sql_transaction('begin');
	
	//reset rpg_players
	$data = array(
		'level'				=>	DEFAULT_LEVEL,
		'pv'				=>	MIN_PV,
		'pf'				=>	MIN_PF,
		'xp'				=>	DEFAULT_XP,
		'karma'				=>	DEFAULT_KARMA,
		'energy'			=>	MAX_ENERGY,
		'total_battles'		=>	0,
		'honor'				=>	0,
		'atk_points'		=>	0,
		'def_points'		=>	0,
		'spd_points'		=>	0,
		'flux_points'		=>	0,
		'res_points'		=>	0,
		'ralz'				=>	DEFAULT_RALZ,
		'orb1'				=>	null,
		'orb2'				=>	null,
		'orb3'				=>	null,
		'orb4'				=>	null,
		'skill_1'			=>	'',
		'skill_2'			=>	'',
		'skill_3'			=>	'',
		'skill_4'			=>	'',
		'skill_1_name'		=>	'',
		'skill_2_name'		=>	'',
		'skill_3_name'		=>	'',
		'skill_4_name'		=>	'',
		'skill_1_element'	=>	ELEMENT_NONE,
		'skill_2_element'	=>	ELEMENT_NONE,
		'skill_3_element'	=>	ELEMENT_NONE,
		'skill_4_element'	=>	ELEMENT_NONE,
		'skill_1_subskill'	=>	null,
		'skill_2_subskill'	=>	null,
		'skill_3_subskill'	=>	null,
		'skill_4_subskill'	=>	null,
		'salary_multiplier'	=>	1,
		'enable_salary_level_multiplier'	=>	true,
	);
	
	$sql = 'UPDATE rpg_players
			SET ' . $db->sql_build_array('UPDATE', $data) . '
			WHERE id = ' . $player->getId();
	$db->sql_query($sql);
	
	/*if($db->sql_affectedrows() == 0) {
		$db->sql_transaction('cancel');
		return false;
	}*/
	
	// reset armor parts
	$sql = 'SELECT *
			FROM rpg_armor_parts
			WHERE player_id = ' . $player_id;
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if($info) {
		$sql = 'DELETE FROM rpg_armor_parts
				WHERE player_id = ' . $player_id;
		$db->sql_query($sql);
		
		if($db->sql_affectedrows() == 0) {
			$db->sql_transaction('cancel');
			return false;
		}
	}
	
	
	// reset inventories
	$sql = 'SELECT *
			FROM rpg_inventories
			WHERE player_id = ' . $player_id . '
			AND item_type != \'ralz\'';
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if($info) {
		$sql = 'DELETE FROM rpg_inventories
				WHERE player_id = ' . $player_id . '
				AND item_type != \'ralz\'';
		$db->sql_query($sql);
		
		if($db->sql_affectedrows() == 0) {
			$db->sql_transaction('cancel');
			return false;
		}
	}
	
	if(!RPGPlayers::giveDefaultItems($player)) {
		$db->sql_transaction('cancel');
		return false;
	}
	
	// reset player stats
	
	$sql = 'SELECT *
			FROM rpg_players_stats
			WHERE player_id = ' . $player_id;
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
			
	if($info) {
		$sql = 'DELETE FROM rpg_players_stats
				WHERE player_id = ' . $player_id;
		$db->sql_query($sql);
		
		if($db->sql_affectedrows() == 0) {
			$db->sql_transaction('cancel');
			return false;
		}
	}
	
	// reset warehouse
	
	$sql = 'SELECT *
			FROM rpg_warehouse
			WHERE player_id = ' . $player_id;
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if($info) {
		$sql = 'DELETE FROM rpg_warehouse
				WHERE player_id = ' . $player_id;
		$db->sql_query($sql);
		
		if($db->sql_affectedrows() == 0) {
			$db->sql_transaction('cancel');
			return false;
		}
	}
	
	// reset weapon
	$sql = 'SELECT grade
			FROM rpg_weapons
			WHERE player_id = ' . $player_id;
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	if($info['grade'] != 'D') {
		if(!RPGWeapons::setWeaponGradeByPlayer($player, WEAPON_GRADE_D)) {
			$db->sql_transaction('cancel');
			return false;
		}
	}
	
	$db->sql_transaction('commit');
	
	return true;
}

?>