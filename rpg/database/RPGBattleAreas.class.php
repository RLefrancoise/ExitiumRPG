<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGMonsters.class.php");
	include_once(__DIR__ . "/../classes/BattleArea.class.php");
	
	class RPGBattleAreas {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getAreas() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_battle_areas
					ORDER BY level';
			$result = $db->sql_query($sql);
			
			$areas = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$areas[] = new BattleArea($info);
			}
			
			$db->sql_freeresult($result);
			
			return $areas;
		}
		
		public static function getAreaById($id) {
			global $db;
			
			$sql = 'SELECT *
					FROM rpg_battle_areas
					WHERE id = ' . (int) $db->sql_escape($id);
			
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return new BattleArea($info);
		}
		
		public static function getAreaPartsByArea($area_id) {
			global $db;
			
			$sql = 'SELECT *
					FROM rpg_battle_areas_parts
					WHERE area_id = ' . (int) $db->sql_escape($area_id) . '
					ORDER BY min_level';
			$result = $db->sql_query($sql);
			
			$parts = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$parts[] = new BattleAreaPart($info);
			}
			
			$db->sql_freeresult($result);
			
			if(count($parts) == 0) return null;
			
			return $parts;
		}
		
		/*
		* Get monsters of an area part, ordered by encounter rate.
		* Return null if no monster was found.
		*/
		public static function getMonstersByAreaPart($part_id){
			global $db;
			
			$sql = 'SELECT rpg_monsters.id
					FROM rpg_battle_areas_parts, rpg_monsters, rpg_battle_areas_monsters
					WHERE rpg_battle_areas_monsters.area_part_id = ' . (int) $db->sql_escape($part_id) . '
					AND rpg_battle_areas_parts.id = ' . (int) $db->sql_escape($part_id) . '
					AND rpg_monsters.id = rpg_battle_areas_monsters.monster_id
					ORDER BY rpg_battle_areas_monsters.encounter_rate';
			$result = $db->sql_query($sql);
			
			$monsters = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$monsters[] = RPGMonsters::getMonster($info['id']);
			}
			
			$db->sql_freeresult($result);
			
			if(count($monsters) == 0) return null;
			
			return $monsters;
		}
		
		public static function getEncounterRateByMonsterAndAreaPart($monster_id, $part_id) {
			global $db;
			
			$sql = 'SELECT encounter_rate
					FROM rpg_battle_areas_monsters
					WHERE area_part_id = ' . (int) $db->sql_escape($part_id) . '
					AND monster_id = ' . (int) $db->sql_escape($monster_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			
			return $info['encounter_rate'];
		}
	}
?>