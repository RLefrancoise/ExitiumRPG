<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Set.class.php");
	
	class RPGSets {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getSet($id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_sets WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_sets WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$s = new Set($info);
			return $s;
		}
		
		public static function getSetByParts($cloth_id, $leggings_id, $gloves_id, $shoes_id) {
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_sets WHERE cloth_id = ' . intval($cloth_id) . ' AND leggings_id = ' . intval($leggings_id) . ' AND gloves_id = ' . intval($gloves_id) . ' AND shoes_id = ' . intval($shoes_id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			
			$req = $bdd->prepare('SELECT * FROM rpg_sets WHERE cloth_id = ? AND leggings_id = ? AND gloves_id = ? AND shoes_id = ?');
			$req->execute(array($cloth_id, $leggings_id, $gloves_id, $shoes_id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$s = new Set($info);
			return $s;
		}
	}
	
?>