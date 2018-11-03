<?php

include_once(__DIR__ . '/../../common.php');
include_once(__DIR__ . "/../classes/PlayerStats.class.php");
	
class RPGPlayersStats {

	private function __construct() {
	}
	
	public static function getStatsByPlayer($player_id){
		global $db;
		
		$sql = 'SELECT DISTINCT * 
				FROM rpg_players_stats 
				WHERE player_id = ' . $player_id;
		$result = $db->sql_query($sql);
		$info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		if(!$info) {
			if(!RPGPlayersStats::createStats($player_id)) return false;
			return RPGPlayersStats::getStatsByPlayer($player_id);
		}
		
		$s = new PlayerStats($info);
		return $s;
	}
	
	public static function createStats($player_id) {
		global $db;
		
		$insert_data = array(
			'player_id'		=> $player_id,
		);	
		
		$sql = 'INSERT INTO rpg_players_stats ' . $db->sql_build_array('INSERT', $insert_data);
		$db->sql_query($sql);
		
		$insert_success = ($db->sql_affectedrows() > 0);
		
		return $insert_success;
	}
	
	public static function setStatByPlayer(Player &$player, $stat, $value) {
		global $db;
		
		$current_stat = $player->getPlayerStats()->getStat($stat);
		if($current_stat === false) return true;
		
		$update_array = null;
		
		if(is_numeric($current_stat)) {
			if($current_stat < $value) {
			
				$update_array = array(
					$stat	=> $value,
				);
				
			}
		}
		
		if(!$update_array) return true;
		
		$sql = 'UPDATE rpg_players_stats
				SET ' . $db->sql_build_array('UPDATE', $update_array) . '
				WHERE player_id = ' . (int) $db->sql_escape($player->getId());
		$db->sql_query($sql);
		$update_success = ($db->sql_affectedrows() > 0);
		
		$player->updateStatsFromBDD();
		
		return $update_success;
	}
	
	public static function incrementStatByPlayer(Player &$player, $stat, $value = 1) {
		global $db;
		
		$current_stat = $player->getPlayerStats()->getStat($stat);
		if($current_stat === false) return false;
		
		$update_array = null;
		
		if(is_numeric($current_stat)) {
			
			$update_array = array(
				$stat	=> ($current_stat + $value),
			);
				
		}
		
		if(!$update_array) return true;
		
		$sql = 'UPDATE rpg_players_stats
				SET ' . $db->sql_build_array('UPDATE', $update_array) . '
				WHERE player_id = ' . (int) $db->sql_escape($player->getId());
		$db->sql_query($sql);
		$update_success = ($db->sql_affectedrows() > 0);
		
		$player->updateStatsFromBDD();
		
		return $update_success;
	}
	
}

?>