<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Player.class.php");
	include_once(__DIR__ . "/RPGRalz.class.php");
	
	class RPGPlayers {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getPlayerInfo($id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_players WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			
			$req = $bdd->prepare('SELECT * FROM rpg_players WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return $info;
		}
		
		/*
		* Get the next free orb slot.
		*
		* - player : the Player object
		* Returns the first available free orb slot, or false if no slot is available. 
		*/
		public static function getNextFreeOrbSlot(Player &$player) {
			//$orbs = $player->getOrbs();
			
			$i = 1;
			while($i <= 4 and $player->getOrb($i) !== null) {
				$i++;
			}
			
			if($i > 4) return false;
			else return $i;
		}
		
		
		/*
		* Create a new player.
		*
		* user_id : the user_id to be associated with the player
		* gender : the gender of the player (M or F)
		* organisation : the organisation of the player (EMPIRE, REVO, CARTEL)
		*
		* Returns the id of the created player if successful, false otherwise.
		*/
		public static function createPlayer($user_id, $gender, $organisation, $weaponname) {
			if(strtoupper($gender) !== 'M' and strtoupper($gender) !== 'F') return false;
			if(strtoupper($organisation) !== 'EMPIRE' and strtoupper($organisation) !== 'REVO' and strtoupper($organisation) !== 'CARTEL') return false;
			if($weaponname == '') return false;
			
			if(strtoupper($organisation) === 'EMPIRE')
				$orga_id = 1;
			else if(strtoupper($organisation) === 'REVO')
				$orga_id = 2;
			else
				$orga_id = 3;
				
			$bdd = &Database::getBDD();
			//insert player in rpg_players
			$req = $bdd->prepare("INSERT INTO rpg_players VALUES ('', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$req->execute(array(strtoupper($gender) // gender
							, DEFAULT_LEVEL // level
							, MIN_PV // pv
							, MIN_PV // max pv
							, MIN_PF // pf
							, MIN_PF // max pf
							, DEFAULT_XP // xp
							, DEFAULT_KARMA // karma
							, DEFAULT_ATK // attack
							, DEFAULT_DEF // defense
							, DEFAULT_SPD // speed
							, DEFAULT_FLUX // flux
							, DEFAULT_POINTS // points
							, DEFAULT_RALZ // ralz
							, NULL // orb1
							, NULL // orb2
							, NULL // orb3
							, NULL // orb4
							, $orga_id // organisation
							, NULL
						));
						
			$insert_success = ($req->rowCount() > 0);
			
			// insert player to rpg_users_players table
			$req = $bdd->prepare('SELECT id FROM rpg_players ORDER BY id DESC LIMIT 1');
			$req->execute();
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$associate_success = RPGUsersPlayers::associatePlayerToUser($user_id, $info['id']);
			
			// set weapon name of player
			$player = RPGUsersPlayers::getPlayerByUserId($user_id);
			$weapon_success = RPGWeapons::setWeaponByPlayer($player, $weaponname, WEAPON_GRADE_D);
			
			if($insert_success and $associate_success and $weapon_success)
				return $info['id'];
			else
				return false;
		}
		
		public static function deletePlayer($player_id) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('DELETE FROM rpg_players WHERE id=?');
			$req->execute(array($player_id));
			
			return ($req->rowCount() > 0);
		}
		
		// SETTERS
		
		
		
		public static function setClanIdByPlayer(&$player, $id) {
			$newid = 'NULL';
			if($id !== null) { $newid = $id; }
			
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('UPDATE rpg_players SET clan_id=? WHERE id=?');
			$req->execute(array($newid, $player->getId()));
		}
		
		/*
		* Set Ralz of player.
		* - player : the player object
		* - ralz : the new ralz number
		*/
		public static function setRalzByPlayer(Player &$player, $ralz) {
			if(intval($ralz) < 0) $ralz = 0;
			
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('UPDATE rpg_players SET ralz=? WHERE id=?');
			$req->execute(array($ralz, $player->getId()));
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return ($req->rowCount() > 0);
		}
		
		// ORB
		
		/*
		* Set an orb to the specified slot
		*
		* - player : the player object
		* - slot : the slot of the orb (between 1 and 4)
		* - orb_id : the ID of the orb in the DB
		*/
		public static function setOrbByPlayer(Player &$player, $slot, $orb_id) {
			if(intval($slot) < 1 or intval($slot) > 4) return false;
			
			// orb_id is valid ?
			$orb = RPGOrbs::getOrb($orb_id);
			if($orb === null) return false;
			
			$orb_field = 'orb' . intval($slot);
			
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('UPDATE rpg_players SET ' . $orb_field . '=? WHERE id=?');
			$req->execute(array($orb_id, $player->getId()));
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return ($req->rowCount() > 0);
		}
		
		public static function removeOrbByPlayer(Player &$player, $slot) {
			if(intval($slot) < 1 or intval($slot) > 4) return false;
			
			$orb_field = 'orb' . intval($slot);
			
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('UPDATE rpg_players SET ' . $orb_field . '= NULL WHERE id=?');
			$req->execute(array($player->getId()));
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return ($req->rowCount() > 0);
		}
		
		// INVENTORY
		
		/*
		* Give an item to the player
		*/
		public static function giveItemToPlayer(Player &$player, Item $i) {
			if($player->getInventory()->isFull()) return false;
			
			//look for the type of item (set part, orb, syringe)
			$item_type = $player->getInventory()->getTypeOfItem($i);
			
			$slot = -1;
			$number = -1;
			$req_type = '';
			
			// this item is limited to one per slot, so we add it in the first free slot
			if($i->isOnePerSlot()) {
				$slot = $player->getInventory()->getNextFreeSlot();
				$number = 1;
				$req_type = 'insert';
			}
			
			// this item is allowed to be multiple times in the same slot, so we check if at least one examplary exists
			else {
				$item_found = false;
				for($j = 0 ; !$item_found && $j < INVENTORY_SIZE ; $j++) {
					$item2 = $player->getInventory()->getItem($j);
					if($item2 == null) continue;
					if( ($i->getId() == $item2->getId()) and ($item_type == RPGInventories::getTypeOfItemByPlayerAndSlot($player->getId(), $j+1)) ){
						$item_found = true;
					}
				}
				// if an examplary is found, we just add one to its quantity
				if($item_found) {
					$slot = $j;
					$number = RPGInventories::getQuantityOfItemByPlayer($player->getId(), $slot) + 1;
					$req_type = 'update';
				}
				// else we add it in the next available slot
				else {
					$slot = $player->getInventory()->getNextFreeSlot();
					$number = 1;
					$req_type = 'insert';
				}
			}
			
			if($slot == -1 or $number == -1 or $req_type == '') { return; }

			if(strcmp($req_type, 'insert') == 0) {
				$bdd = &Database::getBDD();
				$req = $bdd->prepare("INSERT INTO rpg_inventories VALUES ('', ?, ?, ?, ?, ?)");
				$req->execute(array($player->getId(), $slot, $i->getId(), $item_type, $number));
			}
			else {
				$bdd = &Database::getBDD();
				$req = $bdd->prepare('UPDATE rpg_inventories SET number = ? WHERE player_id = ? AND slot = ?');
				$req->execute(array($number, $player->getId(), $slot));
			}
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			//$player->giveItem($i);
			
			return ($req->rowCount() > 0);
		}
	}
?>