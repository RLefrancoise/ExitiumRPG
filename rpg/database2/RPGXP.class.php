<?php
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGXP {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getXPByLvl($level){
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT xp FROM rpg_xp WHERE level = ?');
			$req->execute(array($level));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return (int) $info['xp'];
		}
	}
?>