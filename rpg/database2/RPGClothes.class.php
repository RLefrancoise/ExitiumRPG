<?php

	include_once(__DIR__ . "/Database.class.php");
	
	class RPGClothes {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getCloth($id){
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_clothes WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return $info;
		}
	}
	
?>