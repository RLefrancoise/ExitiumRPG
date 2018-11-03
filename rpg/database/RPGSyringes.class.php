<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Syringe.class.php");
	
	class RPGSyringes {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getSyringe($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_syringes
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$s = new Syringe($info);
			return $s;
		}
		
		public static function getSyringes() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_syringes
					ORDER BY name';
			$result = $db->sql_query($sql);
			
			$syringes = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$syringes[] = new Syringe($info);
			}
			
			$db->sql_freeresult($result);
			
			return $syringes;
		}
		
		public static function createSyringe($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_syringes ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateSyringe($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_syringes
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteSyringe($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_syringes
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
?>