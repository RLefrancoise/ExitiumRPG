<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Set.class.php");
	
	class RPGSets {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getSet($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_sets 
					WHERE id = ' . $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$s = new Set($info);
			return $s;
		}
		
		public static function getSets() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_sets
					ORDER BY id';
			$result = $db->sql_query($sql);
			
			$sets = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$sets[] = new Set($info);
			}
			
			$db->sql_freeresult($result);
			
			return $sets;
		}
		
		public static function getSetByParts($cloth_id, $leggings_id, $gloves_id, $shoes_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_sets 
					WHERE cloth_id = ' . $db->sql_escape($cloth_id) . '
					AND leggings_id = ' . $db->sql_escape($leggings_id) . '
					AND gloves_id = ' . $db->sql_escape($gloves_id) . '
					AND shoes_id = ' . $db->sql_escape($shoes_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$s = new Set($info);
			return $s;
		}
		
		public static function createSet($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_sets ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateSet($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_sets
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteSet($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_sets
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
	
?>