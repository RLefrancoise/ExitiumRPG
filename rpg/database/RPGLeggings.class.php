<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGLeggings {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getLegging($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_leggings
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_leggings WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return $info;
		}
		
		public static function getLeggings() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_leggings
					ORDER BY req_lvl';
			$result = $db->sql_query($sql);
			
			$leggings = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$leggings[] = new SetPart($info, ARMOR_LEGGINGS);
			}
			
			$db->sql_freeresult($result);
			
			return $leggings;
		}
		
		public static function createLegging($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_leggings ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateLegging($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_leggings
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteLegging($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_leggings
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
	
?>