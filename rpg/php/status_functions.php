<?php
include_once(__DIR__ . '/../../common.php');

include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGPlayers.class.php');
include_once('./rpg/database/RPGArmorParts.class.php');
include_once('./rpg/php/player_functions.php');
include_once('./rpg/classes/rpgconfig.php');

function get_equipment_inventory_html($slot) {
	$html = '
	<ul>
		<li><a href="javascript:equip_item('.$slot.')">Equiper</a></li>
		<li><a href="javascript:request_sell_price('.$slot.', false)">Vendre</a></li>
		<li><a href="javascript:drop_item('.$slot.', false)">Jeter</a></li>
		<li><a href="javascript:give_item('.$slot.', false)">Envoyer à un joueur</a></li>
		<li><a href="javascript:store_item('.$slot.', false)">Stocker dans le casier</a></li>
		<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>
	</ul>
	';
	
	return $html;
}

function get_orb_inventory_html($slot) {
	$html = '
	<ul>
		<li><a href="javascript:equip_item('.$slot.')">Equiper</a></li>
		<li><a href="javascript:request_sell_price('.$slot.', true)">Vendre</a></li>
		<li><a href="javascript:drop_item('.$slot.', true)">Jeter</a></li>
		<li><a href="javascript:give_item('.$slot.', true)">Envoyer à un joueur</a></li>
		<li><a href="javascript:store_item('.$slot.', true)">Stocker dans le casier</a></li>
		<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>
	</ul>
	';
	
	return $html;
}

function get_syringe_inventory_html($slot) {
	$html = '
	<ul>
		<li><a href="javascript:use_item('.$slot.')">Utiliser</a></li>
		<li><a href="javascript:request_sell_price('.$slot.', true)">Vendre</a></li>
		<li><a href="javascript:drop_item('.$slot.', true)">Jeter</a></li>
		<li><a href="javascript:give_item('.$slot.', true)">Envoyer à un joueur</a></li>
		<li><a href="javascript:store_item('.$slot.', true)">Stocker dans le casier</a></li>
		<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>
	</ul>
	';
	
	return $html;
}

function get_special_inventory_html($slot) {
	$html = '
	<ul>
		<li><a href="javascript:use_item('.$slot.')">Utiliser</a></li>
		<li><a href="javascript:request_sell_price('.$slot.', true)">Vendre</a></li>
		<li><a href="javascript:drop_item('.$slot.', true)">Jeter</a></li>
		<li><a href="javascript:give_item('.$slot.', true)">Envoyer à un joueur</a></li>
		<li><a href="javascript:store_item('.$slot.', true)">Stocker dans le casier</a></li>
		<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>
	</ul>
	';
	
	return $html;
}

function get_ralz_inventory_html($slot) {
	$html = '
	<ul>
		<li><a href="javascript:drop_item('.$slot.', true)">Jeter</a></li>
		<li><a href="javascript:give_ralz()">Envoyer à un joueur</a></li>
		<li><a href="javascript:give_pi()">Envoyer au clan</a></li>
		<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>
	</ul>
	';
	
	return $html;
}

function get_skill_menu_html($slot) {
	global $_SKILLS_DATA;
	$html = '<ul>';
	foreach($_SKILLS_DATA as $skill => $data) {
		$html .= '<li><a href="javascript:learn_skill('.$slot.',\''.$skill.'\')">'.$data['name'].' : '.$data['desc'].'</a></li>';
	}
	$html .= '<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li></ul>';
	
	return $html;
}




function remove_armor_part(Player &$player, $type) {
	global $db;
	
	//look if player has enough space in inventory
	if($player->getInventory()->isFull()) { return 'equipment_remove_no_space'; }
	
	//get the armor part
	$part = null;
	switch($type) {
		case 'cloth':
			{
				$part = $player->getEquipment(ARMOR_CLOTH);
				$item = new SetPart(RPGClothes::getCloth($part->getPartId()), ARMOR_CLOTH);
				
				$db->sql_transaction('begin');
				$remove_success = RPGArmorParts::removeArmorPartByPlayerAndType($player, ARMOR_CLOTH);
				$add_success = RPGPlayers::giveItemToPlayer($player, $item);
				$db->sql_transaction('commit');
				
				update_player_state($player); // update pv and pf
				
				if($remove_success and $add_success) return 'equipment_remove_ok';
				else return 'error';
			}
			break;
		case 'leggings':
			{
				$part = $player->getEquipment(ARMOR_LEGGINGS);
				$item = new SetPart(RPGLeggings::getLegging($part->getPartId()), ARMOR_LEGGINGS);
				
				$db->sql_transaction('begin');
				$remove_success = RPGArmorParts::removeArmorPartByPlayerAndType($player, ARMOR_LEGGINGS);
				$add_success = RPGPlayers::giveItemToPlayer($player, $item);
				$db->sql_transaction('commit');
				
				update_player_state($player); // update pv and pf
				
				if($remove_success and $add_success) return 'equipment_remove_ok';
				else return 'error';
			}
			break;
		case 'glove':
			{
				$part = $player->getEquipment(ARMOR_GLOVES);
				$item = new SetPart(RPGGloves::getGlove($part->getPartId()), ARMOR_GLOVES);
				
				$db->sql_transaction('begin');
				$remove_success = RPGArmorParts::removeArmorPartByPlayerAndType($player, ARMOR_GLOVES);
				$add_success = RPGPlayers::giveItemToPlayer($player, $item);
				$db->sql_transaction('commit');
				
				update_player_state($player); // update pv and pf
				
				if($remove_success and $add_success) return 'equipment_remove_ok';
				else return 'error';
			}
			break;
		case 'shoe':
			{
				$part = $player->getEquipment(ARMOR_SHOES);
				$item = new SetPart(RPGShoes::getShoe($part->getPartId()), ARMOR_SHOES);
				
				$db->sql_transaction('begin');
				$remove_success = RPGArmorParts::removeArmorPartByPlayerAndType($player, ARMOR_SHOES);
				$add_success = RPGPlayers::giveItemToPlayer($player, $item);
				$db->sql_transaction('commit');
				
				update_player_state($player); // update pv and pf
				
				if($remove_success and $add_success) return 'equipment_remove_ok';
				else return 'error';
			}
			break;
		default:
			return 'error';
	}
}

function remove_orb(Player &$player, $slot) {
	global $db;
	
	//look if player has enough space in inventory
	if($player->getInventory()->isFull()) { return 'orb_remove_no_space'; }
	if(intval($slot) < 1 or intval($slot) > 4) return 'error';
	
	$orb = $player->getOrb(intval($slot));
	if($orb === null) return 'error';
	
	$db->sql_transaction('begin');
	$remove_success = RPGPlayers::removeOrbByPlayer($player, intval($slot));
	$add_success = RPGPlayers::giveItemToPlayer($player, $orb);
	$db->sql_transaction('commit');
	
	update_player_state($player); // update pv and pf
	
	if($remove_success and $add_success) return 'orb_remove_ok';
	else return 'error';
}

function use_item(Player &$player, $slot) {
	global $db;
	
	$item_type = RPGInventories::getTypeOfItemByPlayerAndSlot($player->getId(), $slot);
	
	switch($item_type) {
		case 'syringe':
			{
				$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
				if(!$item->isUsableOutsideBattle()) return 'not_usable';
				
				$db->sql_transaction('begin');
				
				$pv_update = true;
				$pf_update = true;
				
				// heal pv ?
				if($item->getPV() > 0) {
					if($player->getPV() >= $player->getMaxPV()) return 'not_usable'; // no need to heal pv
					
					$pv_update = player_heal_pv($player, $item->getPV());
				}
				// heal pf ?
				if($item->getPF() > 0) {
					if($player->getPF() >= $player->getMaxPF()) return 'not_usable'; // no need to heal pf
					
					$pf_update = player_heal_pf($player, $item->getPF());
				}
				
				$db->sql_transaction('commit');
				
				if($pv_update and $pf_update) { drop_item($player, $slot, 1); return 'use_ok'; }
				else return 'error';
			}
			break;
		case 'special':
			{
				$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
				
				switch($item->getEffect()) {
					/* Upgrade Weapon */
					case SPECIAL_EFFECT_UPGRADE_WEAPON:
						{
							$weapon = $player->getWeapon();
							$grade = $weapon->getGrade();
							$new_grade = false;
							
							switch($grade) {
								case WEAPON_GRADE_D:
									$new_grade = WEAPON_GRADE_C;
									break;
								case WEAPON_GRADE_C:
									$new_grade = WEAPON_GRADE_B;
									break;
								case WEAPON_GRADE_B:
									$new_grade = WEAPON_GRADE_A;
									break;
								case WEAPON_GRADE_A:
									$new_grade = WEAPON_GRADE_S;
									break;
								case WEAPON_GRADE_S:
									$new_grade = WEAPON_GRADE_SS;
									break;
								case WEAPON_GRADE_SS:
									return 'not_usable';
								default:
									return 'error';
							}
							
							$db->sql_transaction('begin');
							
							if($new_grade and RPGWeapons::setWeaponGradeByPlayer($player, $new_grade) and drop_item($player, $slot, 1)) {
								$db->sql_transaction('commit');
								return 'use_ok';
							}
							else
								return 'error';
						}
						break;
						
					/* Reset Points */
					case SPECIAL_EFFECT_RESET_POINTS:
						{
							$db->sql_transaction('begin');
							
							if(	RPGPlayers::setPointsByPlayerAndStat($player, STAT_ATTACK, 0) and
								RPGPlayers::setPointsByPlayerAndStat($player, STAT_DEFENSE, 0) and
								RPGPlayers::setPointsByPlayerAndStat($player, STAT_SPEED, 0) and
								RPGPlayers::setPointsByPlayerAndStat($player, STAT_FLUX, 0) and
								RPGPlayers::setPointsByPlayerAndStat($player, STAT_RESISTANCE, 0) and
								drop_item($player, $slot, 1)
							) {
								$db->sql_transaction('commit');
								return 'use_ok';
							}
							else
								return 'error';
						}
						break;
						
					/* Reset Skills */
					case SPECIAL_EFFECT_RESET_SKILLS:
						{
							$db->sql_transaction('begin');
							
							if(	RPGPlayers::removeSkillByPlayer($player, 1) and
								RPGPlayers::removeSkillByPlayer($player, 2) and
								RPGPlayers::removeSkillByPlayer($player, 3) and
								RPGPlayers::removeSkillByPlayer($player, 4) and
								drop_item($player, $slot, 1)
							) {
								$db->sql_transaction('commit');
								return 'use_ok';
							}
							else
								return 'error';
						}
						break;
						
					/* Up energy (Max + 5, Regen + 1) */
					case SPECIAL_EFFECT_UP_ENERGY:
						{
							$db->sql_transaction('begin');
							
							if( RPGPlayers::setMaxEnergyBonusOfPlayer($player, $player->getMaxEnergyBonus() + 5) and
								RPGPlayers::setIncEnergyBonusOfPlayer($player, $player->getIncEnergyBonus() + 1) and
								drop_item($player, $slot, 1)
							) {
								$db->sql_transaction('commit');
								return 'use_ok';
							}
							else
								return 'error';
							
						}
						break;
				}
			}
			break;
		case 'cloth':
		case 'leggings':
		case 'glove':
		case 'shoe':
		case 'orb':
		case 'ralz':
		default:
			return false;
	}
}

function drop_item(Player &$player, $slot, $quantity) {
	$item_type = RPGInventories::getTypeOfItemByPlayerAndSlot($player->getId(), $slot);
				
	// look for quantity according to the type of the item
	switch($item_type) {
		case 'cloth':
		case 'leggings':
		case 'glove':
		case 'shoe':
			if(RPGInventories::removeItemByPlayerAndSlot($player, $slot))
				return true;
			else
				return false;
				
			break;
		case 'syringe':
		case 'orb':
		case 'special':
			if($quantity < 1) { return 'error'; }
			
			if(RPGInventories::dropQuantityOfItemByPlayerAndSlot($player, $slot, $quantity))
				return true;
			else
				return false;
				
			break;
		case 'ralz':
			if($quantity < 1) { return 'error'; }
			
			if(RPGPlayers::setRalzByPlayer($player, $player->getRalz() - $quantity))
				return true;
			else
				return false;
				
			break;
		default:
			return false;
			break;
	}
}

/*
* Update PV and PF of a player in database.
* Used for example when a player unequip something.
*/
function update_player_state(Player &$player) {
	global $db;
	
	$max_pv = $player->getMaxPV();
	$bdd_pv = $player->getPV();
	
	$max_pf = $player->getMaxPF();
	$bdd_pf = $player->getPF();
	
	$pv_update = true;
	$pf_update = true;
	
	$db->sql_transaction('begin');
	if($bdd_pv > $max_pv) $pv_update = RPGPlayers::setPVOfPlayer($player, $max_pv);
	if($bdd_pf > $max_pf) $pf_update = RPGPlayers::setPFOfPlayer($player, $max_pf);
	$db->sql_transaction('commit');
	
	return $pv_update and $pf_update;
}

?>