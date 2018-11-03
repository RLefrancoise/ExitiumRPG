<?php
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGShoes {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getShoe($id){
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_shoes WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return $info;
		}
	}
	
?>