<?php

	include_once(__DIR__ . "/Database.class.php");
	
	class RPGGloves {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getGlove($id){
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_gloves WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return $info;
		}
	}
	
?>