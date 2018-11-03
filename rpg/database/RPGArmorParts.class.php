<?php	
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/ArmorPart.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	
	class RPGArmorParts {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getArmorPart($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_armor_parts 
					WHERE id = ' . $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			
			//armor part exists ?
			$sql = 'SELECT COUNT(*) FROM rpg_armor_parts WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			//get armor part
			$req = $bdd->prepare('SELECT * FROM rpg_armor_parts WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$ap = new ArmorPart($info);
			return $ap;
		}
		
		public static function getArmorPartByPlayerAndType($pid, $type){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_armor_parts 
					WHERE player_id = ' . $db->sql_escape($pid) . '
					AND type = \'' . $db->sql_escape($type) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			
			//armor part exists ?
			$sql = 'SELECT COUNT(*) FROM rpg_armor_parts WHERE player_id = ' . intval($pid) . ' AND type = ' . $type;
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			//get armor part
			$req = $bdd->prepare('SELECT * FROM rpg_armor_parts WHERE player_id = ? AND type = ?');
			$req->execute(array($pid, $type));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$ap = new ArmorPart($info);
			return $ap;
		}
		
		
		
		/* SETTERS */
		
		/*
		* Set the name of a player armor_part.
		*
		* $player : the player object.
		* $name : the new name of the part
		* $type : the type of the part (0 : cloth, 1 : leggings, 2 : gloves, 3: shoes)
		*
		* Return true if the name has been changed, false otherwise.
		*/
		public static function setArmorPartNameByPlayerAndType(Player &$player, $name, $type) {
			global $db;
			
			if($type < ARMOR_CLOTH or $type > ARMOR_SHOES) return false;
			
			$update_array = array(
				'name'	=> $name,
			);
			
			$sql = 'UPDATE rpg_armor_parts 
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE player_id = ' . (int) $db->sql_escape($player->getId()) . '
					AND type = \'' . $db->sql_escape($type) . '\'';
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return $success;
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('UPDATE rpg_armor_parts SET name=? WHERE player_id=? AND type=?');
			$req->execute(array($name, $player->getId(), $type));
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return ($req->rowCount() > 0);*/
		}
		
		public static function addArmorPartByPlayer(Player &$player, SetPart $i) {
			global $db;
			
			$insert_data = array(
				'player_id' => (int) $player->getId(),
				'name' 		=> $i->getName(),
				'type' 		=> $i->getType(),
				'part_id' 	=> (int) $i->getId(),
			);
			
			$sql = 'INSERT INTO rpg_armor_parts ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			$player = RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return $success;
			
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare("INSERT INTO rpg_armor_parts VALUES ('', ?, ?, ?, ?)");
			$req->execute(array($player->getId(), $i->getName(), $i->getType(), $i->getId()));
			
			$player = RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return ($req->rowCount() > 0);*/
		}
		
		public static function removeArmorPartByPlayerAndType(Player &$player, $type) {
			global $db;
			
			if($type < ARMOR_CLOTH or $type > ARMOR_SHOES) return false;
			
			$sql = 'DELETE FROM rpg_armor_parts
					WHERE player_id = ' . $db->sql_escape($player->getId()) . '
					AND type = \'' . $db->sql_escape($type) . '\'';
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			$player = RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return $success;
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('DELETE FROM rpg_armor_parts WHERE player_id = ? AND type = ?');
			$req->execute(array($player->getId(), $type));
			
			$player = RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return ($req->rowCount() > 0);*/
		}
	}
	
?>