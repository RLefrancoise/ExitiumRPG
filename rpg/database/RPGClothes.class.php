<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGClothes {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getCloth($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_clothes
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_clothes WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return $info;
		}
		
		public static function getClothes() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_clothes
					ORDER BY req_lvl';
			$result = $db->sql_query($sql);
			
			$clothes = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$clothes[] = new SetPart($info, ARMOR_CLOTH);
			}
			
			$db->sql_freeresult($result);
			
			return $clothes;
		}
		
		public static function createCloth($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_clothes ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateCloth($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_clothes
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteCloth($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_clothes
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
	
?>