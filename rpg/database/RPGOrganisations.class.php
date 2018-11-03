<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Organisation.class.php");
	
	class RPGOrganisations {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getOrganisation($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_organisations
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_organisations WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_organisations WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$orga = new Organisation($info);
			return $orga;
		}
	}
?>