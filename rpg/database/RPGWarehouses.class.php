<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Warehouse.class.php");
	include_once(__DIR__ . "/RPGOrbs.class.php");
	include_once(__DIR__ . "/RPGArmorParts.class.php");
	include_once(__DIR__ . "/RPGWeapons.class.php");
	include_once(__DIR__ . "/RPGRalz.class.php");
	include_once(__DIR__ . "/RPGSyringes.class.php");
	include_once(__DIR__ . "/RPGSpecials.class.php");
	
	class RPGWarehouses {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getWarehouseByPlayer($player_id){
			global $db;
			
			$sql = 'SELECT * 
					FROM rpg_warehouse
					WHERE player_id = ' . (int) $db->sql_escape($player_id) . '
					ORDER BY slot';
			$result = $db->sql_query($sql);
			
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
			
			$w = new Warehouse($items, $items_number);
			
			return $w;
		}
		
		public static function getQuantityOfItemByPlayer($player_id, $slot) {
			global $db;
			
			$sql = 'SELECT DISTINCT number 
					FROM rpg_warehouse
					WHERE player_id = ' . (int) $db->sql_escape($player_id) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			return $info['number'];
		}
		
		public static function getItemByPlayerAndSlot(Player &$player, $slot) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_warehouse
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
					FROM rpg_warehouse
					WHERE player_id = ' . (int) $db->sql_escape($player_id) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			return $info['item_type'];
		}
		
		public static function removeItemByPlayerAndSlot(Player &$player, $slot) {
			if(intval($slot) < 0 or intval($slot) > WAREHOUSE_SIZE) return false;
			
			global $db;
			
			$sql = 'DELETE
					FROM rpg_warehouse
					WHERE player_id = ' . (int) $db->sql_escape($player->getId()) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());
			
			return $success;
		}
		
		public static function dropQuantityOfItemByPlayerAndSlot(Player &$player, $slot, $quantity) {
			if(intval($slot) < 1 or intval($slot) > WAREHOUSE_SIZE) return false;
			if(intval($quantity) < 1) return false;
			
			global $db;
			
			//check the current quantity of item
			$sql = 'SELECT DISTINCT number 
					FROM rpg_warehouse
					WHERE player_id = ' . (int) $db->sql_escape($player->getId()) . '
					AND slot = ' . (int) $db->sql_escape($slot);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			
			//check the current quantity of item
			$current_quantity = intval($info['number']);
			if($current_quantity <= intval($quantity)) {
				return RPGWarehouses::removeItemByPlayerAndSlot($player, $slot);
			} 
			else {
				$update_array = array(
					'number'	=> (int) ($current_quantity - intval($quantity)),
				);
				
				$sql = 'UPDATE rpg_warehouse
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId() . '
						AND slot = ' . (int) $slot;
				$db->sql_query($sql);

				$success = ($db->sql_affectedrows() > 0);
			
				$player = RPGUsersPlayers::getPlayerByPlayerId($player->getId());

				return $success;
			}
			
			return false;
		}
		
		/*
		* Store a player's item
		*/
		public static function storeItemOfPlayer(Player &$player, Item $i, $quantity = 1) {
			global $db;
			
			//look for the type of item (set part, orb, syringe)
			$item_type = $player->getWarehouse()->getTypeOfItem($i);
			if($item_type == '') return false;
			
			$slot = -1;
			$number = -1;
			$req_type = '';
			
			// this item is limited to one per slot, so we add it in the first free slot
			if($i->isOnePerSlot()) {
				if($player->getWarehouse()->isFull()) return false;
				
				$slot = $player->getWarehouse()->getNextFreeSlot();
				$number = 1;
				$req_type = 'insert';
			}
			
			// this item is allowed to be multiple times in the same slot, so we check if at least one examplary exists
			else {
				$item_found = false;
				for($j = 0 ; !$item_found && $j < WAREHOUSE_SIZE ; $j++) {
					$item2 = $player->getWarehouse()->getItem($j);
					if($item2 == null) continue;
					if( ($i->getId() == $item2->getId()) and ($item_type == RPGWarehouses::getTypeOfItemByPlayerAndSlot($player->getId(), $j+1)) ){
						$item_found = true;
					}
				}
				// if an examplary is found, we just add one to its quantity
				if($item_found) {
					$slot = $j;
					//$number = RPGWarehouses::getQuantityOfItemByPlayer($player->getId(), $slot) + 1;
					$number = RPGWarehouses::getQuantityOfItemByPlayer($player->getId(), $slot) + $quantity;
					$req_type = 'update';
				}
				// else we add it in the next available slot
				else {
					if($player->getWarehouse()->isFull()) return false;
					
					$slot = $player->getWarehouse()->getNextFreeSlot();
					//$number = 1;
					$number = $quantity;
					$req_type = 'insert';
				}
			}
			
			if($slot == -1 or $number == -1 or $req_type == '') { return false; }

			if(strcmp($req_type, 'insert') == 0) {
				$insert_array = array(
					'player_id'	=> (int) $player->getId(),
					'slot'		=> (int) $slot,
					'item_id'	=> (int) $i->getId(),
					'item_type'	=> $item_type,
					'number'	=> (int) $number,
				);
				$sql = 'INSERT INTO rpg_warehouse ' . $db->sql_build_array('INSERT', $insert_array);
				$db->sql_query($sql);

				$request_success = ($db->sql_affectedrows() > 0);
				
				//if($quantity > 1) return ($request_success && RPGWarehouses::storeItemOfPlayer($player, $item, $quantity - 1));
			}
			else {
				$update_array = array(
					'number' => (int) $number,
				);
				
				$sql = 'UPDATE rpg_warehouse
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId() . '
						AND slot = ' . (int) $slot;
				$db->sql_query($sql);
				
				$request_success = ($db->sql_affectedrows() > 0);
			}
			
			$player->updateWarehouse();
			
			return $request_success;
		}
		
		public function retrieveRalzOfPlayer(Player &$player, $ralz) {
			global $db;
			
			$r = RPGWarehouses::getRalzOfPlayer($player);
			if($r == 0) return true;
			
			if($ralz > $r) $ralz = $r;
			
			$data = array(
				'number'	=>	$r - $ralz,
			);
			
			$sql = 'UPDATE rpg_warehouse
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE player_id = ' . $player->getId() . '
					AND item_type = \'ralz\'
					AND slot = 0';
			$db->sql_query($sql);
				
			$request_success = ($db->sql_affectedrows() > 0);
			
			$player->updateWarehouse();
			
			return $request_success;
		}
		
		public function storeRalzOfPlayer(Player &$player, $ralz) {
			global $db;
			
			$r = RPGRalz::getRalzByPlayer($player);
			if(!$r) return false;
			
			$sql = 'SELECT number
					FROM rpg_warehouse
					WHERE player_id = ' . $player->getId() . '
					AND item_type = \'ralz\'
					AND slot = 0';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) {
				$insert_array = array(
					'player_id'	=> (int) $player->getId(),
					'slot'		=> 0,
					'item_id'	=> (int) $r->getId(),
					'item_type'	=> 'ralz',
					'number'	=> $ralz,
				);
				
				$sql = 'INSERT INTO rpg_warehouse ' . $db->sql_build_array('INSERT', $insert_array);
				$db->sql_query($sql);

				$request_success = ($db->sql_affectedrows() > 0);
			} else {
				$update_array = array(
					'number' => (int) $info['number'] + $ralz,
				);
				
				$sql = 'UPDATE rpg_warehouse
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId() . '
						AND item_type = \'ralz\'
						AND slot = 0';
				$db->sql_query($sql);
				
				$request_success = ($db->sql_affectedrows() > 0);
			}
			
			$player->updateWarehouse();
			
			return $request_success;
		}
		
		public function getRalzOfPlayer(Player &$player) {
			global $db;
			
			$sql = 'SELECT number
					FROM rpg_warehouse
					WHERE player_id = ' . (int) $player->getId() . '
					AND item_type = \'ralz\'
					AND slot = 0';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return 0;
			
			return $info['number'];
		}
	}
?>