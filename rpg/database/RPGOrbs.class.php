<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Orb.class.php");
	
	class RPGOrbs {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getOrb($id){
			global $db;
			
			$sql = 'SELECT * 
					FROM rpg_orbs
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$orb = new Orb($info);
			return $orb;
		}
		
		public static function getOrbs() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_orbs
					ORDER BY name';
			$result = $db->sql_query($sql);
			
			$orbs = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$orbs[] = new Orb($info);
			}
			
			$db->sql_freeresult($result);
			
			return $orbs;
		}
		
		public static function createOrb($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_orbs ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateOrb($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_orbs
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteOrb($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_orbs
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
?>