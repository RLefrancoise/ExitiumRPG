<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Ralz.class.php");
	
	class RPGRalz {
		private static $theInst;

		private function __construct() {
		}
		
		/*
		* Give the ralz of the player.
		*
		* If no ralz exists in the DB, this function returns null.
		* Else returns a Ralz object.
		*/
		public static function getRalzByPlayer($player_id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_ralz';
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			
			$req = $bdd->prepare('SELECT * FROM rpg_ralz');
			$req->execute();
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$req = $bdd->prepare('SELECT ralz FROM rpg_players WHERE id = ?');
			$req->execute(array($player_id));
			$ralz = $req->fetch();
			$req->closeCursor();
				
			$info['ralz'] = $ralz['ralz'];
			$r = new Ralz($info);
			return $r;
		}
	}
?>