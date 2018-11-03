<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Upgrade.class.php");
	
	class RPGUpgrades {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getUpgrade($id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_upgrades WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_upgrades WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$u = new Upgrade($info);
			return $u;
		}
		
		public static function getUpgradeByGrade($grade){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_upgrades WHERE grade = ' . $grade;
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_upgrades WHERE grade = ?');
			$req->execute(array($grade));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$u = new Upgrade($info);
			return $u;
		}
	}
	
?>