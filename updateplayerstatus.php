<?php

//header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGArmorParts.class.php');
include_once('./rpg/database/RPGWeapons.class.php');
include_once('./rpg/database/RPGInventories.class.php');
include_once('./rpg/database/RPGPlayersStats.class.php');
include_once('./rpg/database/RPGWarehouses.class.php');
include_once('./rpg/database/RPGClans.class.php');
include_once('./rpg/php/status_functions.php');
include_once('./rpg/php/player_functions.php');
include_once('./rpg/php/string_functions.php');
include_once('./rpg/classes/rpgconfig.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo 'not_connected';
	return;
}
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

$mode = request_var('mode', '');

if($mode == '') { echo 'error'; return; }

switch($mode) {

	/* Equipment Remove : Unequip an armor part and put it in the inventory */
	case 'equipment_remove':
		{
			if(!isset($_GET['type'])) { echo 'error'; return; }
			else {
			
				$type = htmlspecialchars($_GET['type']);
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				echo remove_armor_part($player, $type);
			}
		}
		break;
		
	/* Equipment Rename : modify the name of a player armor part */
	case 'equipment_rename':
		{
			if( !isset($_POST['type']) or !isset($_POST['v']) ) { echo 'error'; return; } // parameter is missing ?
			else {
			
				//$type = htmlspecialchars(($_POST['type'])); // the type of the armor part
				$type = request_var('type', '');
				//$v = htmlspecialchars(($_POST['v'])); // the name of the armor part
				$v = request_var('v', '', true);
			
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				if($type === 'weapon') {
					if(($player->getWeapon()->getName() == $v) or RPGWeapons::setWeaponNameByPlayer($player, $v))
						{ echo 'equipment_rename_ok'; return; }
					else
						{ echo 'error'; return; }
				}
				else if($type === 'cloth') {
					if(RPGPlayers::setArmorPartNameByPlayerAndType($player, $v, ARMOR_CLOTH))
						{ echo 'equipment_rename_ok'; return; }
					else
						{ echo 'error'; return; }
				}
				else if($type === 'leggings') {
					if(RPGPlayers::setArmorPartNameByPlayerAndType($player, $v, ARMOR_LEGGINGS))
						{ echo 'equipment_rename_ok'; return; }
					else
						{ echo 'error'; return; }
				}
				else if($type === 'glove') {
					if(RPGPlayers::setArmorPartNameByPlayerAndType($player, $v, ARMOR_GLOVES))
						{ echo 'equipment_rename_ok'; return; }
					else
						{ echo 'error'; return; }
				}
				else if($type === 'shoe') {
					if(RPGPlayers::setArmorPartNameByPlayerAndType($player, $v, ARMOR_SHOES))
						{ echo 'equipment_rename_ok'; return; }
					else
						{ echo 'error'; return; }
				}
				else
					{ echo 'error'; return; }
				
			}
		}
		break;
	
	/* Orb Remove : Unequip an orb and put it in the inventory */
	case 'orb_remove':
		{
			if(!isset($_GET['slot'])) echo 'error';
			else {
			
				$slot = htmlspecialchars($_GET['slot']);
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				echo remove_orb($player, $slot);
			}
		}
		break;
		
	case 'use_item':
		{
			if(!isset($_GET['slot'])) echo 'error'; // parameter is missing ?
			else {
				$slot = request_var('slot', -1);
				if($slot === -1) { echo 'error'; return; }
				
				$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				$res = use_item($player, $slot);
				if($res === false) { echo 'error'; return; }
				else echo $res;
			}
		}
		break;
		
	/* Equip Item : equip an item and unequip the previous one if any */
	case 'equip_item':
		{
			if(!isset($_GET['slot'])) echo 'error'; // parameter is missing ?
			else {
				$slot = urldecode(htmlspecialchars($_GET['slot'])); // the slot of the item
				
				$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				$item_type = RPGInventories::getTypeOfItemByPlayerAndSlot($player->getId(), $slot);
				
				//equip the new item if the player is allowed to
				$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
				
				// if item is a set part
				if(get_class($item) === 'SetPart') {
					//no required level for orbs, but there is for setparts
					if($player->getLevel() < $item->getRequiredLevel())
						echo 'equip_item_level_too_low';
					else {
							//unequip the previous item
							switch($item_type) {
								case 'cloth':
									//if a previous item was equipped, we remove it first
									if($player->getEquipment(ARMOR_CLOTH) !== null) {
										$res = remove_armor_part($player, $item_type);
										if($res !== 'equipment_remove_ok') { echo $res; return; }
									}
									break;
								case 'leggings':
									//if a previous item was equipped, we remove it first
									if($player->getEquipment(ARMOR_LEGGINGS) !== null) {
										$res = remove_armor_part($player, $item_type);
										if($res !== 'equipment_remove_ok') { echo $res; return; }
									}
									break;
								case 'glove':
									//if a previous item was equipped, we remove it first
									if($player->getEquipment(ARMOR_GLOVES) !== null) {
										$res = remove_armor_part($player, $item_type);
										if($res !== 'equipment_remove_ok') { echo $res; return; }
									}
									break;
								case 'shoe':
									//if a previous item was equipped, we remove it first
									if($player->getEquipment(ARMOR_SHOES) !== null) {
										$res = remove_armor_part($player, $item_type);
										if($res !== 'equipment_remove_ok') { echo $res; return; }
									}
									break;
									
								case 'orb':
									break;;
								default:
									echo 'error';
									break;
							}

						
						
						$db->sql_transaction('begin');
						$add_success = RPGArmorParts::addArmorPartByPlayer($player, $item);
						$remove_success = RPGInventories::removeItemByPlayerAndSlot($player, $slot);
						$db->sql_transaction('commit');
						
						update_player_state($player); // update pv and pf
						
						if($add_success and $remove_success) echo 'equip_ok';
						else echo 'error';
					}
					
				// if item is an orb
				} else if(get_class($item) === 'Orb') {
					$orb_slot = RPGPlayers::getNextFreeOrbSlot($player);
					
					// if no orb slot available, return error
					if($orb_slot === false)
						echo 'equip_item_no_orb_slot';
					// if size of orb is bigger than free slots
					else if( $player->getOrbsSize() + $item->getSize() > 4)
						echo 'equip_item_no_orb_slot';
					//if player already equipped this orb, can't equip the same one
					else if($player->hasEquippedOrb($item))
						echo 'equip_already_has_orb';
					else {
						$db->sql_transaction('begin');
						$equip_success = RPGPlayers::setOrbByPlayer($player, $orb_slot, $item->getId());
						 // drop one exemplary of the orb, remove if only one
						$remove_success = RPGInventories::dropQuantityOfItemByPlayerAndSlot($player, intval($slot), 1);
						$db->sql_transaction('commit');
						
						update_player_state($player); // update pv and pf
						
						if($equip_success and $remove_success) echo 'equip_ok';
						else echo 'error';
					}
					
				// if item is neither an orb or a set part, this item can' be equipped, return error
				} else {
					echo 'error';
				}
			}
		}
		break;
		
	/* Drop Item : drop some quantity of an item */
	case 'drop_item':
		{
			if( !isset($_GET['slot']) ) echo 'error'; // parameter is missing ?
			else {
				
				$slot = urldecode(htmlspecialchars($_GET['slot'])); // the slot of the item
				$v = htmlspecialchars($_GET['v']);
				if($v < 1) { echo 'error'; return; }
				
				$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				if(drop_item($player, $slot, $v))
					echo 'drop_item_ok';
				else
					echo 'error';
			}
		}
		break;
		
	/* Sell Item : sell item according to inventory slot */
	case 'sell_item':
		{
			if( !isset($_GET['slot']) or !isset($_GET['q']) ) echo 'error'; // parameter is missing ?
			else {
				$slot = htmlspecialchars($_GET['slot']); // the slot of the item
				$q = htmlspecialchars($_GET['q']);
				if($q < 1) { echo 'error'; return; }
				
				$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
				
				if($item === null) echo 'error';
				else {
					//get total sell price
					$item_quantity = RPGInventories::getQuantityOfItemByPlayer($player->getId(), $slot);
					if($item_quantity <= $q)
						$price = ($item->getPrice() / 2) * $item_quantity;
					else
						$price = ($item->getPrice() / 2) * $q;

					if($price == 0) { echo 'sell_ok'; return; }
					
					$db->sql_transaction('begin');
					//add sell price (price / 2) of item * quantity to player's ralz
					$add_success = RPGPlayers::setRalzByPlayer($player, $player->getRalz() + $price);
					//remove item from inventory
					$remove_success = drop_item($player, $slot, $q);
					$db->sql_transaction('commit');
					
					if( $add_success and $remove_success ) echo 'sell_ok';
					else echo 'error';
				}
			}
		}
		break;
		
	/* Store Item */
	case 'store_item':
		{
			$slot = request_var('slot', -1);
			if($slot == -1) { echo 'error'; return; }
			$quantity = request_var('q', -1);
			if($quantity == -1) { echo 'error'; return; }
			
			$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			
			//check quantity
			$q = RPGInventories::getQuantityOfItemByPlayer($player->getId(), $slot);
			if($quantity > $q) $quantity = $q; 
			
			$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
			if(get_class($item) === 'Ralz') { echo 'store_ralz'; return; } // can't store ralz
				
			$db->sql_transaction('begin');
			
			if(!RPGWarehouses::storeItemOfPlayer($player, $item, $quantity)) { $db->sql_transaction('cancel'); echo 'warehouse_error'; return; }
			if(!RPGInventories::dropQuantityOfItemByPlayerAndSlot($player, $slot, $quantity)) { $db->sql_transaction('cancel'); echo 'error'; return; }
			
			//update player stats
			if(!RPGPlayersStats::setStatByPlayer($player, 'warehouse_max_slots', $player->getWarehouse()->getNumberOfItems())) { echo 'error'; return; }
			
			$db->sql_transaction('commit');
			
			echo 'store_ok';
		}
		
	/* Give Stat Point */
	case 'give_stat_point':
		{
			if(!isset($_GET['stat'])) echo 'error';
			else {
				$stat = htmlspecialchars($_GET['stat']);
				
				if($stat !== STAT_ATTACK and $stat !== STAT_DEFENSE and $stat !== STAT_RESISTANCE and $stat !== STAT_SPEED and $stat !== STAT_FLUX) { echo 'error'; return; }
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				$player->updatePoints();
				
				if($player->getPoints() <= 0) { echo 'no_more_points'; return;}
				
				switch($stat) {
					case STAT_ATTACK:
						if($player->getBaseAtk() >= STAT_MAX_CAPACITY) { echo 'max_capacity'; return; }
						break;
					case STAT_DEFENSE:
						if($player->getBaseDef() >= STAT_MAX_CAPACITY) { echo 'max_capacity'; return; }
						break;
					case STAT_RESISTANCE:
						if($player->getBaseRes() >= STAT_MAX_CAPACITY) { echo 'max_capacity'; return; }
						break;
					case STAT_SPEED:
						if($player->getBaseSpd() >= STAT_MAX_CAPACITY) { echo 'max_capacity'; return; }
						break;
					case STAT_FLUX:
						if($player->getBaseFlux() >= STAT_MAX_CAPACITY) { echo 'max_capacity'; return; }
						break;
				}
				
				$current_points = $player->getPointsOfStat($stat);
				if(RPGPlayers::setPointsByPlayerAndStat($player, $stat, $current_points + 1)) { echo 'give_ok'; }
				else echo 'error';
			}
		}
		break;
		
	/* Get skill actions */
	case 'skill_actions':
		{
			global $_SKILLS_ANIMS;
			
			$slot = request_var('slot', -1);
			if($slot == -1) { echo 'error'; return; }
			
			$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			$skill = $player->getSkill($slot);
			
			$html = '<ul>';
			$html .= '<li><a href="javascript:rename_skill('.$slot.')">Renommer</a></li>';
			if(!array_key_exists(ELEMENT_NONE, $_SKILLS_ANIMS[$skill->getType()])
			or (array_key_exists(ELEMENT_NONE, $_SKILLS_ANIMS[$skill->getType()]) and ($skill->getSubSkill() != '') and !array_key_exists(ELEMENT_NONE, $_SKILLS_ANIMS[$skill->getSubSkill()])))
				$html .= '<li class="link" onclick="javascript:get_skill_element_menu('.$slot.', event)">Changer l\'élément</li>';
			
			$can_fuse = false;
			for($i = 1 ; !$can_fuse and ($i <= 4) ; $i++) {
				$skill = $player->getSkill($i);
				if($skill == null) continue;
				if($skill->getSubSkill() == '') $can_fuse = true;
			}
			if($can_fuse)
				$html .= '<li><a href="javascript:open_fusion_menu()">Fusionner des skills</a></li>';
			$html .= '<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li></ul>';
			
			echo $html;
		}
		break;

	case 'skill_element_menu':
		{
			$slot = request_var('slot', -1);
			if($slot == -1) { echo 'error'; return; }
			
			$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			$skill = $player->getSkill($slot);
			
			global $_SKILLS_ANIMS, $_ELEMENTS_STRINGS;
			
			$html = '<ul>';
			
			foreach($_ELEMENTS_STRINGS as $element => $str) {
				//if($element != ELEMENT_NONE) {
					//if(array_key_exists($element, $_SKILLS_ANIMS[$skill->getType()])) {
						$html .= "<li><a href=\"javascript:change_skill_element({$slot}, '$element')\">$str</a></li>";
					//} else if(array_key_exists($element, $_SKILLS_ANIMS[$skill->getSubSkill()])) {
					//	$html .= "<li><a href=\"javascript:change_skill_element({$slot}, '$element')\">$str</a></li>";
					//}
				//}
			}
			
			$html .= '<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li></ul>';
			
			echo $html;
		}
		break;
		
	/* Rename skill */
	case 'skill_rename':
		{
			if(!isset($_GET['name']) or !isset($_GET['nb'])) echo 'error';
			else {
				//$name = htmlspecialchars(urldecode($_GET['name']));
				$name = request_var('name', '', true);
				$nb = (int) htmlspecialchars($_GET['nb']);
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				if(RPGPlayers::setSkillNameByPlayer($player, $name, $nb)) echo 'skill_rename_ok';
				else echo 'error';
			}
		}
		break;
		
	/* Change skill element */
	case 'skill_change_element':
		{
			$slot = request_var('slot', -1);
			if($slot == -1) { echo 'error'; return; }
			if($slot < 1 or $slot > 4) { echo 'error'; return; }
			
			$element = request_var('element', '');
			if($element == '') { echo 'error'; return; }
			
			$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			if($element == $player->getSkill($slot)->getElement()) { echo 'skill_change_element_ok'; return; }
			
			if(RPGPlayers::setSkillElementByPlayer($player, $slot, $element)) echo 'skill_change_element_ok';
			else echo 'error';
		}
		break;
		
	/* Learn skill */
	case 'learn_skill':
		{
			if(!isset($_GET['slot']) or !isset($_GET['t'])) echo 'error';
			else {
				$type = htmlspecialchars(urldecode($_GET['t']));
				$slot = (int) htmlspecialchars($_GET['slot']);
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				if($player->getSkill($slot) != null) { echo 'slot_already_used'; }
				
				//check if type is valid
				$skill_is_valid = false;
				global $_SKILLS_DATA;
				foreach($_SKILLS_DATA as $key => $value) {
					if($key === $type) { $skill_is_valid = true; break; }
				}
				
				if(!$skill_is_valid) { echo 'invalid_skill'; return; }
				
				//check if choosen skill is already learnt
				$already_learnt = false;
				for($i = 1 ; (!$already_learnt) and ($i <= 4) ; $i++) {
					$skill = $player->getSkill($i);
					if($skill == null) continue;
					if($skill->getType() == $type) $already_learnt = true;
					if($skill->getSubSkill() == $type) $already_learnt = true;
				}
				
				if($already_learnt) { echo 'already_learnt'; return; }
				
				if(RPGPlayers::setSkillByPlayer($player, $slot, $type)) echo 'learn_skill_ok';
				else echo 'error';
			}
		}
		break;
		
	/* Set gender */
	case 'set_gender':
		{
			if(!isset($_GET['g'])) echo 'error';
			else {
				$gender = request_var('g', '');
				if($gender === '') { echo 'error'; return; }
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				if(RPGPlayers::setGenderByPlayer($player, $gender)) echo 'set_gender_ok';
				else echo 'error';
			}
		}
		break;
		
	/* Give Ralz */
	case 'give_ralz':
		{
			if(!isset($_GET['r']) or !isset($_GET['p'])) echo 'error';
			else {
				$ralz = request_var('r', 0);
				if($ralz <= 0) { echo 'error'; return; }
				$player_name = request_var('p', '');
				if($player_name === '') { echo 'error'; return; }
				if($player_name === $user->data['username']) { echo 'give_to_self'; return; }
				
				$player = RPGUsersPlayers::getPlayerByUserName($player_name);
				//check player
				if(!$player) { echo 'no_player'; return; }
				
				$user_player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				if(!$user_player) { echo 'error'; return; }
				
				$db->sql_transaction('begin');
				
				//give ralz
				$give_success = player_give_ralz($player, (int) $ralz);
				//decrease ralz from sender
				$decrease_success = player_give_ralz($user_player, -1 * $ralz);
				
				if(!RPGPlayersStats::setStatByPlayer($user_player, 'max_ralz_send', $ralz)) { echo 'error'; return; }
				
				$db->sql_transaction('commit');
				
				// write private message and send it to the player
				$poll = $uid = $bitfield = $options = ''; 
				
				$text = 'Le membre ' . $user->data['username'] . ' vous a envoyé ' . $ralz . ' Ralz.';
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Envoi de Ralz');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$pm_data = array(
					'from_user_id'            	=> $user->data['user_id'],
					'icon_id'               	=> 0,
					'from_user_ip'             	=> $user->data['user_ip'],
					'from_username'            	=> $user->data['username'],
					'enable_sig'             	=> false,
					'enable_bbcode'           	=> true,
					'enable_smilies'          	=> true,
					'enable_urls'             	=> true,
					'bbcode_bitfield'         	=> $bitfield,
					'bbcode_uid'             	=> $uid,
					'message'                	=> $text,
					'message_attachment'    	=> 0,
					'address_list'        		=> array('u' => array($player->getUserId() => 'to')),
				);
				
				if($give_success and $decrease_success and submit_pm('post', $subject, $pm_data, false)) echo 'give_ralz_ok';
				else echo 'error';
			}
		}
		break;
		
	/* Give Item */
	case 'give_item':
		{
			if(!isset($_GET['s']) or !isset($_GET['p']) or !isset($_GET['q'])) echo 'error';
			else {
				//slot
				$slot = request_var('s', 0);
				if($slot < 1 or $slot > 16) { echo 'error'; return; }
				// player name
				$player_name = request_var('p', '');
				if($player_name === '') { echo 'error'; return; }
				if($player_name === $user->data['username']) { echo 'give_to_self'; return; }
				//quantity
				$q = request_var('q', 0);
				if($q == 0) { echo 'error'; return; }
				
				//get player of current user
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				//get item to give
				$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
				if($item === null) { echo 'error'; return; }
				if($item->isOnePerSlot()) $q = 1;
				
				// getter of the item
				$getter = RPGUsersPlayers::getPlayerByUserName($player_name);
				//check getter
				if(!$getter) { echo 'no_player'; return; }
				
				$db->sql_transaction('begin');
				
				//give item
				for($i = 0; $i < $q ; $i++) {
					$give_success = RPGPlayers::giveItemToPlayer($getter, $item);
					if(!$give_success) { echo 'give_error'; return; }
				}
				
				//drop item from current player
				$drop_success = RPGInventories::dropQuantityOfItemByPlayerAndSlot($player, $slot, $q);
				if(!$drop_success) { echo 'error'; return; }
				
				$db->sql_transaction('commit');
				
				// write private message and send it to the player
				$poll = $uid = $bitfield = $options = ''; 
				
				$text = "Le membre {$user->data['username']} vous a envoyé $q objets \"{$item->getName()}\".";
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Envoi d\'un objet');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$pm_data = array(
					'from_user_id'            	=> $user->data['user_id'],
					'icon_id'               	=> 0,
					'from_user_ip'             	=> $user->data['user_ip'],
					'from_username'            	=> $user->data['username'],
					'enable_sig'             	=> false,
					'enable_bbcode'           	=> true,
					'enable_smilies'          	=> true,
					'enable_urls'             	=> true,
					'bbcode_bitfield'         	=> $bitfield,
					'bbcode_uid'             	=> $uid,
					'message'                	=> $text,
					'message_attachment'    	=> 0,
					'address_list'        		=> array('u' => array($getter->getUserId() => 'to')),
				);
				
				if($give_success and $drop_success and submit_pm('post', $subject, $pm_data, false)) echo 'give_item_ok';
				else echo 'error';
			}
		}
		break;
		
	/* Set Option */
	case 'set_option':
		{
			if(!isset($_GET['o']) or !isset($_GET['s'])) echo 'error';
			else {
				$option = request_var('o', '');
				if($option === '') { echo 'error'; return; }
				$state = request_var('s', '');
				if($state !== 'on' and $state !== 'off') { echo 'error'; return; }
				if($state === 'on') $state = true;
				else $state = false;
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				$set_success = false;
				
				switch($option) {
					case 'sound':
						$set_success = RPGPlayers::setSoundOptionByPlayer($player, $state);
						break;
					case 'animations':
						$set_success = RPGPlayers::setAnimationsOptionByPlayer($player, $state);
						break;
					case 'alpha':
						$set_success = RPGPlayers::setAlphaOptionByPlayer($player, $state);
						break;
					case 'hd':
						$set_success = RPGPlayers::setHDOptionByPlayer($player, $state);
						break;
					default:
						break;
				}
				
				if($set_success) echo 'set_option_ok';
				else echo 'error';
			}
		}
		break;
		
	/* Set Introduction Link */
	case 'set_intro_link':
		{
			if(!isset($_GET['url'])) echo 'error';
			else {
				//$name = htmlspecialchars(urldecode($_GET['name']));
				$url = request_var('url', '', true);
				if($url == '') { echo 'error'; return; }
				
				$found = strpos($url, SITE_URL);
				if($found === false or ($found !== false and $found != 0)) {
					echo 'invalid_url';
					return;
				}
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				if(RPGPlayers::setIntroductionLinkByPlayer($player, $url)) echo 'set_introduction_link_ok';
				else echo 'error';
			}
		}
		break;
		
	case 'get_intro_link_menu':
		{
			$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
			$link = $player->getIntroductionLink();
			
			$html = '<ul><li><a href="javascript:set_introduction_link()">Modifier</a></li>';
			
			if($link != '') $html .= '<li><a href="javascript:go(\'' . $player->getIntroductionLink() . '\')">Aller</a></li>';
				
			$html .= '<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li></ul>';
					
			echo $html;
		}
		break;
		
	case 'get_fusion_skills':
		{
			if(!isset($_GET['m'])) echo 'error';
			else {
				$material_nb = request_var('m', -1);
				if($material_nb == -1) { echo 'error'; return; }
				
				$html = '';
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				$fuse_nb = 0;
				$can_fuse = false;
				//look if player has at least two skills to fuse
				for($i = 1 ; !$can_fuse and ($i <= 4) ; $i++) {
					$skill = $player->getSkill($i);
					if($skill == null) { $can_fuse = true; continue; }
					if($skill->getSubSkill() == '') $fuse_nb++;
				}
				
				if($fuse_nb >= 2) $can_fuse = true;
				
				if($material_nb == 1 or ($material_nb == 2 and $can_fuse)) {
					for($i = 1 ; $i <= 4 ; $i++) {
						$skill = $player->getSkill($i);
						if($skill == null) continue;
						if($skill->getSubSkill() != '') continue;
						
						$html .= '<span class="fusion_skill_link" onclick="javascript:select_fusion_skill('.$material_nb.','.$i.', this)">' . $skill->getName() . '</span>' . PHP_EOL;
					}
				}
				else {
					global $_SKILLS_DATA;
					
					$available = array();
					
					//get skills available for fusion
					foreach($_SKILLS_DATA as $type => $data) {
						//check if skill is already learnt
						$already_learnt = false;
						for($i = 1 ; (!$already_learnt) and ($i <= 4) ; $i++) {
							$skill = $player->getSkill($i);
							if($skill == null) continue;
							if($skill->getType() == $type) $already_learnt = true;
							if($skill->getSubSkill() == $type) $already_learnt = true;
						}
						
						if($already_learnt) continue;
						
						$available[$type] = $data;
					}
					
					$i = -1;
					foreach($available as $type => $data) {
						$html .= '<span class="fusion_skill_link" onclick="javascript:select_fusion_skill('.$material_nb.','.$i.', this)">' . $data['name'] . '</span>' . PHP_EOL;
						$i--;
					}
				}
				
				echo $html;

			}
		}
		break;
		
	case 'get_fusion_result':
		{
			if(!isset($_GET['s1']) or !isset($_GET['s2'])) echo 'error';
			else {
				$skill1 = request_var('s1', 0);
				if($skill1 == 0) { echo 'error'; return; }
				$skill2 = request_var('s2', 0);
				if($skill2 == 0) { echo 'error'; return; }
				
				if($skill1 == $skill2) { echo 'same_skills'; return; }
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				$s1 = $player->getSkill($skill1);
				if($skill2 > 0)
					$s2 = $player->getSkill($skill2);
				else {
					global $_SKILLS_DATA;
					
					$available = array();
					
					//get skills available for fusion
					foreach($_SKILLS_DATA as $type => $data) {
						//check if skill is already learnt
						$already_learnt = false;
						for($i = 1 ; (!$already_learnt) and ($i <= 4) ; $i++) {
							$skill = $player->getSkill($i);
							if($skill == null) continue;
							if($skill->getType() == $type) $already_learnt = true;
							if($skill->getSubSkill() == $type) $already_learnt = true;
						}
						
						if($already_learnt) continue;
						
						$available[$type] = $data;
					}
					
					$i = -1;
					$skill_type = '';
					foreach($available as $type => $value) {
						if($i == $skill2) { $skill_type = $type; break; }
						$i--;
					}
					
					$s2 = Skill::getSkillByType($skill_type, ELEMENT_NONE, '');
				}
				
				if($s1 == null or $s2 == null) { echo 'error'; return; }
				
				//fusion is allowed ?
				global $_FORBIDDEN_FUSIONS;
				if(($_FORBIDDEN_FUSIONS[$s1->getType()] != null) and in_array($s2->getType(), $_FORBIDDEN_FUSIONS[$s1->getType()])) {
					$html .= "Fusion interdite.";
				}
				else {
					$html = '<strong>Lance dans le même tour :</strong><br>';
					
					$html .= "{$s1->getName()} + {$s2->getName()}<br><br>";
					
					$pf = (int) floor(($s1->getPF() + $s2->getPF()) * 0.75);
					if($pf < $s1->getPF() or $pf < $s2->getPF()) $pf = ($s1->getPF() > $s2->getPF() ? $s1->getPF() : $s2->getPF());
					$cd = ($s1->getCooldown() > $s2->getCooldown()) ? $s1->getCooldown() * 1.5 : $s2->getCooldown() * 1.5;
					$cd = (int) floor($cd);
					$xp = $pf * 100;
					
					$html .= "<strong>PF :</strong> $pf<br>";
					$html .= "<strong>CD :</strong> $cd tours<br>";
					$html .= "<strong>Coût :</strong> $xp XP";
				
				}
				echo $html;
			}
		}
		break;
		
	case 'fuse_skills':
		{
			if(!isset($_GET['s1']) or !isset($_GET['s2'])) echo 'error';
			else {
				$skill1 = request_var('s1', 0);
				if($skill1 == 0) { echo 'error'; return; }
				$skill2 = request_var('s2', 0);
				if($skill2 == 0) { echo 'error'; return; }
				
				if($skill1 == $skill2) { echo 'same_skills'; return; }
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				$s1 = $player->getSkill($skill1);
				if($skill2 > 0)
					$s2 = $player->getSkill($skill2);
				else {
					global $_SKILLS_DATA;
					
					$available = array();
					
					//get skills available for fusion
					foreach($_SKILLS_DATA as $type => $data) {
						//check if skill is already learnt
						$already_learnt = false;
						for($i = 1 ; (!$already_learnt) and ($i <= 4) ; $i++) {
							$skill = $player->getSkill($i);
							if($skill == null) continue;
							if($skill->getType() == $type) $already_learnt = true;
							if($skill->getSubSkill() == $type) $already_learnt = true;
						}
						
						if($already_learnt) continue;
						
						$available[$type] = $data;
					}
					
					$i = -1;
					$skill_type = '';
					foreach($available as $type => $value) {
						if($i == $skill2) { $skill_type = $type; break; }
						$i--;
					}
					
					$s2 = Skill::getSkillByType($skill_type, ELEMENT_NONE, '');
				}
				if($s1 == null or $s2 == null) { echo 'error'; return; }
				if($s1->getSubSkill() != '') { echo 'error'; return; }
				
				//fusion is allowed ?
				global $_FORBIDDEN_FUSIONS;
				if(($_FORBIDDEN_FUSIONS[$s1->getType()] != null) and in_array($s2->getType(), $_FORBIDDEN_FUSIONS[$s1->getType()])) {
					echo 'forbidden_fusion';
					return;
				}
				
				$db->sql_transaction('begin');
				
				if($skill2 > 0) {
					if(RPGPlayers::setSubSkillByPlayer($player, $skill1, $s2->getType()) and RPGPlayers::removeSkillByPlayer($player, $skill2) and player_give_exp($player, -1 * $player->getSkill($skill1)->getPF() * 100)) {
						echo 'fuse_ok';
						$db->sql_transaction('commit');
					} else {
						echo 'error';
					}
				} else {
					if(RPGPlayers::setSubSkillByPlayer($player, $skill1, $s2->getType()) and player_give_exp($player, -1 * $player->getSkill($skill1)->getPF() * 100)) {
						echo 'fuse_ok';
						$db->sql_transaction('commit');
					} else {
						echo 'error';
					}
				}
				
			}
		}
		break;
		
	/* Give PI */
	case 'give_pi':
		{
			if(!isset($_GET['q'])) echo 'error';
			else {
				$q = request_var('q', 0);
				if($q == 0) { echo 'error'; return; }
				if($q < 0) { echo 'error'; return; }
				
				$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
				
				if($q > $player->getRalz()) $q = $player->getRalz();
				
				$clan = $player->getClan();
				if(!$clan) { echo 'no_clan'; return; }
				
				
				
				$db->sql_transaction('begin');
				
				$pi = $clan->getPI() + $q;
				
				if(!RPGClans::updatePI($clan, $pi)) {
					echo 'error2';
					$db->sql_transaction('cancel');
					return;
				}
				if(!player_give_ralz($player, -1 * $q)) {
					echo 'error3';
					$db->sql_transaction('cancel');
					return;
				}
				
				$db->sql_transaction('commit');
				echo 'pi_ok';
			}
		}
		break;
		
	default:
		echo 'error';
		break;
}

?>