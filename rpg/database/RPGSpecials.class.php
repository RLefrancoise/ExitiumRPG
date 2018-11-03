<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Special.class.php");
	
	class RPGSpecials {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getSpecial($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_specials
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$special = new Special($info);
			return $special;
		}
		
		public static function getSpecials() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_specials
					ORDER BY name';
			$result = $db->sql_query($sql);
			
			$specials = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$specials[] = new Special($info);
			}
			
			return $specials;
		}
		
		public static function createSpecial($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_specials ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateSpecial($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_specials
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteSpecial($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_specials
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
?>