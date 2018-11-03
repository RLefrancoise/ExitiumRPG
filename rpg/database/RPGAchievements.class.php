<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Achievement.class.php");
	
	class RPGAchievements {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getCategory($id) {
			global $db;
			
			$sql = 'SELECT *
					FROM rpg_achievements_categories
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$c = new AchievementCategory($info);
			return $c;
		}
		
		public static function getAchievement($id){
			global $db;
			
			$sql = 'SELECT * 
					FROM rpg_achievements
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$a = new Achievement($info);
			return $a;
		}
		
		public static function getAchievements() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_achievements';
			$result = $db->sql_query($sql);
			
			$achievements = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$achievements[] = new Achievement($info);
			}
			
			$db->sql_freeresult($result);
			
			return $achievements;
		}
		
		public static function createAchievement($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_achievements ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateAchievement($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_achievements
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteAchievement($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_achievements
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		
		
		public static function isUnlocked($achievement_id, $player_id) {
			global $db;
			
			$sql = 'SELECT unlocked
					FROM rpg_players_achievements
					WHERE achievement_id = ' . (int) $db->sql_escape($achievement_id) . '
					AND player_id = ' . (int) $db->sql_escape($player_id) . '
					AND unlocked = 1';
			$result = $db->sql_query($sql);
			
			$info = $db->sql_fetchrow($result);
			
			if(!$info) return false;
			else return true;
		}
		
		public static function unlockAchievement($achievement_id, $player_id) {
			global $db;
			
			$sql = 'INSERT INTO rpg_players_achievements ' . $db->sql_build_array('INSERT', array(
				'achievement_id'	=>	$achievement_id,
				'player_id'			=>	$player_id,
				'unlocked'			=>	true,
			));
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
?>