<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Weapon.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	
	class RPGWeapons {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getWeapon($id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_weapons WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$w = new Weapon($info);
			return $w;
		}
		
		public static function getWeaponByPlayer($pid){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($pid);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_weapons WHERE player_id = ?');
			$req->execute(array($pid));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$w = new Weapon($info);
			return $w;
		}
		
		/* SETTERS */
		public static function setWeaponByPlayer(Player& $player, $name, $grade) {
		
			if($name == '') return false;
			if(!in_array($grade, array(WEAPON_GRADE_D, WEAPON_GRADE_C, WEAPON_GRADE_B, WEAPON_GRADE_A, WEAPON_GRADE_S, WEAPON_GRADE_SS))) return false;
			
			$bdd = &Database::getBDD();
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($player->getId());
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) {
				$req = $bdd->prepare('INSERT INTO rpg_weapons VALUES (\'\', ?, ?, ?)');
				$req->execute(array($player->getId(), $name, $grade));
			}
			else {
				$req = $bdd->prepare('UPDATE rpg_weapons SET name=?, grade=? WHERE player_id=?');
				$req->execute(array($name, $grade, $player->getId()));
			}
			
			$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
			return ($req->rowCount() > 0);
		}
		
		
		public static function setWeaponNameByPlayer(Player &$player, $name) {
			if($name == '') return false;
			
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($player->getId());
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) {
				return false;
			}
			else {
				$req = $bdd->prepare('UPDATE rpg_weapons SET name=? WHERE player_id=?');
				$req->execute(array($name, $player->getId()));
			}
			
			$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
			return ($req->rowCount() > 0);
		}
		
		
		
		public static function setWeaponGradeByPlayer(Player &$player, $grade) {
			if(!in_array($grade, array(WEAPON_GRADE_D, WEAPON_GRADE_C, WEAPON_GRADE_B, WEAPON_GRADE_A, WEAPON_GRADE_S, WEAPON_GRADE_SS))) return false;
			
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($player->getId());
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) {
				return false;
			}
			else {
				$req = $bdd->prepare('UPDATE rpg_weapons SET grade=? WHERE player_id=?');
				$req->execute(array($grade, $player->getId()));
			}
			
			$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
			return ($req->rowCount() > 0);
		}
	}
?>