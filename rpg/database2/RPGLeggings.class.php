<?php

	include_once(__DIR__ . "/Database.class.php");
	
	class RPGLeggings {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getLegging($id){
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_leggings WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return $info;
		}
	}
	
?>