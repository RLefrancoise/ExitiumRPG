<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Monster.class.php");
	include_once(__DIR__ . "/../classes/Skill.class.php");
	include_once(__DIR__ . '/../../common.php');
	
	class RPGMonsters {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getMonster($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_monsters 
					WHERE id = ' . $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$m = new Monster($info);
			return $m;
		}
		
		public static function getMonsters() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_monsters
					ORDER BY name';
			$result = $db->sql_query($sql);
			
			$monsters = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$monsters[] = new Monster($info);
			}
			
			$db->sql_freeresult($result);
			
			return $monsters;
		}
		
		public static function getSkillsByMonster($monster_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT skill_type, skill_element
					FROM rpg_monsters_skills
					WHERE monster_id = ' . (int) $db->sql_escape($monster_id);
			$result = $db->sql_query($sql);
			
			$skills = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$skills[] = Skill::getSkillByType($info['skill_type'], $info['skill_element'], '');
			}
			
			$db->sql_freeresult($result);
			
			return $skills;
		}
		
		public static function getNamesOfSkillsByMonster($monster_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT skill_name 
					FROM rpg_monsters_skills 
					WHERE monster_id = ' . $db->sql_escape($monster_id);
			$result = $db->sql_query($sql);
			
			$names = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$names[] = $info['skill_name'];
			}
			
			$db->sql_freeresult($result);
			
			return $names;
		}
		
		public static function getDropsByMonster($monster_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT area_part_id, item_type, item_id, rate
					FROM rpg_monsters_drops
					WHERE monster_id = ' . (int) $db->sql_escape($monster_id) . '
					ORDER BY rate';
			$result = $db->sql_query($sql);
			
			$drops = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$area_id = $info['area_part_id'];
				unset($info['area_part_id']);
				if(!isset($drops[$area_id])) $drops[$area_id] = array();
				
				$rate = $info['rate'];
				unset($info['rate']);
				if(!isset($drops[$area_id][$rate])) $drops[$area_id][$rate] = array();
				
				$drops[$area_id][$rate][] = $info;
				
				ksort($drops[$area_id][$rate], SORT_NUMERIC);
			}
			
			$db->sql_freeresult($result);
			
			return $drops;
		}
	}
	
?>