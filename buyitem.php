<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGSets.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUpgrades.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGSyringes.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGSpecials.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGBlackMarket.class.' . $phpEx);
include_once('./rpg/php/status_functions.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

$mode = (isset($_GET["mode"])) ? $_GET["mode"] : NULL;
$s = (isset($_GET["s"])) ? $_GET["s"] : NULL;
$q = (isset($_GET["q"])) ? $_GET["q"] : NULL;

if ( ($mode !== null) && ($s !== null) && ($q !== null) ) {
	// get the item to buy
	$item = null;
	
	/*switch($mode) {
		case 'sets':
			$item = RPGSets::getSet($s);
			break;
		case 'equips':
			$item = RPGBlackMarket::getPart($s);
			break;
		case 'syringes':
			$item = RPGSyringes::getSyringe($s);
			break;
		case 'special':
			$item = RPGSpecials::getSpecial($s);
			break;
		default:
			break;
	}*/
	
	$item = RPGBlackMarket::getItemByCategoryAndPlace($mode, $s);
	
	if($item == null) {
		echo 'error';
		die();
	}
	
	$quantity = intval($q, 10);
	if($quantity <= 0) $quantity = 1;
	
	// look if player has enough money to buy the item
	if($player->getRalz() >= $item->getPrice() * $quantity){
		
		//look if there is enough space in inventory
		$has_place = false;
		if($mode != 'sets'){
			if($item->isOnePerSlot()) {
				if($player->getInventory()->getNumberOfItems() + $quantity <= INVENTORY_SIZE)
					$has_place = true;
			} else {
				if(!$player->getInventory()->hasItem($item)) {
					if($player->getInventory()->getNumberOfItems() + 1 <= INVENTORY_SIZE)
						$has_place = true;
				}
				else $has_place = true;
			}
		}
		else {
			if($player->getInventory()->getNumberOfItems() + 4 * $quantity <= INVENTORY_SIZE)
				$has_place = true;
		}
		
		if($has_place) {
			if(!$db->sql_transaction('begin')) { echo 'error'; return; }
			
			if(!RPGPlayersStats::incrementStatByPlayer($player, 'buy_times', $quantity)) { echo 'error'; return; }
			
			if($mode != 'sets') {
				if(!RPGPlayers::giveItemToPlayer($player, $item, $quantity)) {
					echo 'error';
					return;
				}
			}
			else {
				
				if(!RPGPlayers::giveItemToPlayer($player, $item->getCloth())) {
					echo 'error';
					return;
				}
				
				if(!RPGPlayers::giveItemToPlayer($player, $item->getLeggings())) {
					echo 'error';
					return;
				}
				
				if(!RPGPlayers::giveItemToPlayer($player, $item->getGloves())) {
					echo 'error';
					return;
				}
				
				if(!RPGPlayers::giveItemToPlayer($player, $item->getShoes())) {
					echo 'error';
					return;
				}
				
			}
			
			//remove needed ralz to buy
			$price = ($item->getPrice() * $quantity);
			player_give_ralz($player, -1 * $price);
			//$ralz = $player->getRalz();
			//RPGPlayers::setRalzByPlayer($player, $ralz - ($item->getPrice() * $quantity));
			
			RPGPlayersStats::setStatByPlayer($player, 'max_ralz_buy', $price);
			
			if(!$db->sql_transaction('commit')) { echo 'error'; return; }
			echo 'buy_ok';
		}
		else echo 'inventory_full';
	}
	else {
		echo 'no_money';
	}
	
} else {
    echo "error";
}
 
?>