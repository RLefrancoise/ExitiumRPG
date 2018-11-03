<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGShoes {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getShoe($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_shoes
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_shoes WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return $info;
		}
		
		public static function getShoes() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_shoes
					ORDER BY req_lvl';
			$result = $db->sql_query($sql);
			
			$shoes = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$shoes[] = new SetPart($info, ARMOR_SHOES);
			}
			
			$db->sql_freeresult($result);
			
			return $shoes;
		}
		
		public static function createShoe($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_shoes ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateShoe($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_shoes
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteShoe($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_shoes
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
	
?>