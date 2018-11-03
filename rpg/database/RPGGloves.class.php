<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGGloves {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getGlove($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_gloves
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_gloves WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return $info;
		}
		
		public static function getGloves() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_gloves
					ORDER BY req_lvl';
			$result = $db->sql_query($sql);
			
			$gloves = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$gloves[] = new SetPart($info, ARMOR_GLOVES);
			}
			
			$db->sql_freeresult($result);
			
			return $gloves;
		}
		
		public static function createGlove($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_gloves ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateGlove($id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_gloves
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteGlove($id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_gloves
					WHERE id = ' . (int) $id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
	
?>