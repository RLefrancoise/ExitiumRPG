<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Weapon.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	
	class RPGWeapons {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getWeapon($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_weapons
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_weapons WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$w = new Weapon($info);
			return $w;
		}
		
		public static function getWeaponByPlayer($pid){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_weapons
					WHERE player_id = ' . (int) $db->sql_escape($pid);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($pid);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_weapons WHERE player_id = ?');
			$req->execute(array($pid));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$w = new Weapon($info);
			return $w;
		}
		
		/* SETTERS */
		public static function setWeaponByPlayer(Player& $player, $name, $grade) {
			global $db;
			
			if($name == '') return false;
			if(!in_array($grade, array(WEAPON_GRADE_D, WEAPON_GRADE_C, WEAPON_GRADE_B, WEAPON_GRADE_A, WEAPON_GRADE_S, WEAPON_GRADE_SS))) return false;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_weapons
					WHERE player_id = ' . (int) $db->sql_escape($player->getId());
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			/*$bdd = &Database::getBDD();
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($player->getId());
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) {*/
			if(!$info) {
				$insert_array = array(
					'player_id'	=> (int) $player->getId(),
					'name'		=> $name,
					'grade'		=> $grade,
				);
				$sql = 'INSERT INTO rpg_weapons ' . $db->sql_build_array('INSERT', $insert_array);
				$db->sql_query($sql);
				
				$request_success = ($db->sql_affectedrows() > 0);
				
				/*$req = $bdd->prepare('INSERT INTO rpg_weapons VALUES (\'\', ?, ?, ?)');
				$req->execute(array($player->getId(), $name, $grade));*/
			}
			else {
				$update_array = array(
					'name' 	=> $name,
					'grade'	=> $grade,
				);
				
				$sql = 'UPDATE rpg_weapons
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId();
				$db->sql_query($sql);
				
				$request_success = ($db->sql_affectedrows() > 0);
				
				/*$req = $bdd->prepare('UPDATE rpg_weapons SET name=?, grade=? WHERE player_id=?');
				$req->execute(array($name, $grade, $player->getId()));*/
			}
			
			//$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			$player->updateWeaponFromBDD();
			
			return $request_success;
			//return ($req->rowCount() > 0);
		}
		
		
		public static function setWeaponNameByPlayer(Player &$player, $name) {
			global $db;
			
			if($name == '') return false;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_weapons
					WHERE player_id = ' . (int) $db->sql_escape($player->getId());
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($player->getId());
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) {*/
			if(!$info) {
				return false;
			}
			else {
				$update_array = array(
					'name' 	=> $name,
				);
				
				$sql = 'UPDATE rpg_weapons
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId();
				$db->sql_query($sql);
				
				$success = ($db->sql_affectedrows() > 0);
				
				/*$req = $bdd->prepare('UPDATE rpg_weapons SET name=? WHERE player_id=?');
				$req->execute(array($name, $player->getId()));*/
			}
			
			//$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			$player->updateWeaponFromBDD();
			
			return $success;
			//return ($req->rowCount() > 0);
		}
		
		
		
		public static function setWeaponGradeByPlayer(Player &$player, $grade) {
			global $db;
			
			if(!in_array($grade, array(WEAPON_GRADE_D, WEAPON_GRADE_C, WEAPON_GRADE_B, WEAPON_GRADE_A, WEAPON_GRADE_S, WEAPON_GRADE_SS))) return false;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_weapons
					WHERE player_id = ' . (int) $db->sql_escape($player->getId());
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_weapons WHERE player_id = ' . intval($player->getId());
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) {*/
			if(!$info) {
				return false;
			}
			else {
				$update_array = array(
					'grade' 	=> $grade,
				);
				
				$sql = 'UPDATE rpg_weapons
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId();
				$db->sql_query($sql);
				
				$success = ($db->sql_affectedrows() > 0);
				
				/*$req = $bdd->prepare('UPDATE rpg_weapons SET grade=? WHERE player_id=?');
				$req->execute(array($grade, $player->getId()));*/
			}
			
			//$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			$player->updateWeaponFromBDD();
			
			return $success;
			//return ($req->rowCount() > 0);
		}
	}
?>