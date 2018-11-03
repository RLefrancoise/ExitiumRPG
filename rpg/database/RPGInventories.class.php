<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Inventory.class.php");
	include_once(__DIR__ . "/RPGOrbs.class.php");
	include_once(__DIR__ . "/RPGArmorParts.class.php");
	include_once(__DIR__ . "/RPGWeapons.class.php");
	include_once(__DIR__ . "/RPGRalz.class.php");
	include_once(__DIR__ . "/RPGSyringes.class.php");
	include_once(__DIR__ . "/RPGSpecials.class.php");
	
	class RPGInventories {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getInventoryByPlayer($player_id){
			global $db;
			
			$sql = 'SELECT * 
					FROM rpg_inventories
					WHERE player_id = ' . (int) $db->sql_escape($player_id) . '
					ORDER BY slot';
			$result = $db->sql_query($sql);
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_inventories WHERE player_id = ? ORDER BY slot');
			$req->execute(array($player_id));*/
			
			$items = array();
			$items_number = array();
			
			//while($info = $req->fetch()) {
			while($info = $db->sql_fetchrow($result)) {
				$item_id = $info['item_id'];
				$item_type = $info['item_type'];
				$item_slot = $info['slot'];
				$item_number = $info['number'];
				
				switch($item_type) {
					case 'orb':
						$items[$item_slot - 1] = RPGOrbs::getOrb($item_id);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'syringe':
						$items[$item_slot - 1] = RPGSyringes::getSyringe($item_id);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'cloth':
						$items[$item_slot - 1] = new SetPart(RPGClothes::getCloth($item_id), ARMOR_CLOTH);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'leggings':
						$items[$item_slot - 1] = new SetPart(RPGLeggings::getLegging($item_id), ARMOR_LEGGINGS);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'glove':
						$items[$item_slot - 1] = new SetPart(RPGGloves::getGlove($item_id), ARMOR_GLOVES);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'shoe':
						$items[$item_slot - 1] = new SetPart(RPGShoes::getShoe($item_id), ARMOR_SHOES);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'special':
						$items[$item_slot - 1] = RPGSpecials::getSpecial($item_id);
						$items_number[$item_slot - 1] = $item_number;
						break;
					case 'ralz':
						$items[$item_slot - 1] = RPGRalz::getRalzByPlayer($player_id);
						$items_number[$item_slot - 1] = $item_number;
						break;
					default:
						break;
				}
				
			}
			
			//$req->closeCursor();
			$db->sql_freeresult($result);
			
			$inventory = new Inventory($items, $items_number);
			
			return $inventory;
		}
		
		public static function getQuantityOfItemByPlayer($player_id, $slot) {
			global $db;
			
			$sql = 'SELECT DISTINCT number 
					FROM rpg_inventories
					WHERE player_id = ' . (int) $db->sql_escape($player_id) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT number FROM rpg_inventories WHERE player_id = ? AND slot = ?');
			$req->execute(array($player_id, $slot));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return $info['number'];
		}
		
		public static function getItemByPlayerAndSlot(Player &$player, $slot) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_inventories
					WHERE player_id = ' . (int) $db->sql_escape($player->getId()) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$item_id = $info['item_id'];
			$item_type = $info['item_type'];
			$item_slot = $info['slot'];
			$item_number = $info['number'];
			
			$item = null;
			
			switch($item_type) {
				case 'orb':
					$item = RPGOrbs::getOrb($item_id);
					break;
				case 'syringe':
					$item = RPGSyringes::getSyringe($item_id);
					break;
				case 'cloth':
					$item = new SetPart(RPGClothes::getCloth($item_id), ARMOR_CLOTH);
					break;
				case 'leggings':
					$item = new SetPart(RPGLeggings::getLegging($item_id), ARMOR_LEGGINGS);
					break;
				case 'glove':
					$item = new SetPart(RPGGloves::getGlove($item_id), ARMOR_GLOVES);
					break;
				case 'shoe':
					$item = new SetPart(RPGShoes::getShoe($item_id), ARMOR_SHOES);
					break;
				case 'special':
					$item = RPGSpecials::getSpecial($item_id);
					break;
				case 'ralz':
					$item = RPGRalz::getRalzByPlayer($player->getId());
					break;
				default:
					break;
			}
			
			return $item;
		}
		
		public static function getTypeOfItemByPlayerAndSlot($player_id, $slot) {
			global $db;
			
			$sql = 'SELECT DISTINCT item_type 
					FROM rpg_inventories
					WHERE player_id = ' . (int) $db->sql_escape($player_id) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT item_type FROM rpg_inventories WHERE player_id = ? AND slot = ?');
			$req->execute(array($player_id, $slot));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return $info['item_type'];
		}
		
		public static function removeItemByPlayerAndSlot(Player &$player, $slot) {
			if(intval($slot) < 0 or intval($slot) > INVENTORY_SIZE) return false;
			
			global $db;
			
			$sql = 'DELETE
					FROM rpg_inventories
					WHERE player_id = ' . (int) $db->sql_escape($player->getId()) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
			return $success;
			
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare("DELETE FROM rpg_inventories WHERE player_id = ? AND slot = ?");
			$req->execute(array($player->getId(), $slot));
			
			$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
			return ($req->rowCount() > 0);*/
		}
		
		public static function dropQuantityOfItemByPlayerAndSlot(Player &$player, $slot, $quantity) {
			if(intval($slot) < 1 or intval($slot) > INVENTORY_SIZE) return false;
			if(intval($quantity) < 1) return false;
			
			global $db;
			
			//check the current quantity of item
			$sql = 'SELECT DISTINCT number 
					FROM rpg_inventories
					WHERE player_id = ' . (int) $db->sql_escape($player->getId()) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			//check the current quantity of item
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT number FROM rpg_inventories WHERE player_id = ? AND slot = ?');
			$req->execute(array($player->getId(), $slot));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$current_quantity = intval($info['number']);
			if($current_quantity <= intval($quantity)) {
				return RPGInventories::removeItemByPlayerAndSlot($player, $slot);
			} 
			else {
				$update_array = array(
					'number'	=> (int) ($current_quantity - intval($quantity)),
				);
				
				$sql = 'UPDATE rpg_inventories
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId() . '
						AND slot = ' . (int) $slot;
				$db->sql_query($sql);

				$success = ($db->sql_affectedrows() > 0);
				
				/*$req = $bdd->prepare("UPDATE rpg_inventories SET number = ? WHERE player_id = ? AND slot = ?");
				$req->execute(array($current_quantity - intval($quantity), $player->getId(), $slot));*/
			
				$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
				//return ($req->rowCount() > 0);
				return $success;
			}
			
			return false;
		}
	}
?>