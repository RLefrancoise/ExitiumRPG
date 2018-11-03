<?php
 
//header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGPlayersStats.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGWarehouses.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/status_functions.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/string_functions.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/player_functions.' . $phpEx);

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


$mode = request_var('mode', '');
if($mode == '') { echo 'error'; return; }

switch($mode) {
	case 'retrieve':
	{
		$slot = request_var('slot', -1);
		if($slot == -1) { echo 'error'; return; }
		
		$quantity = request_var('q', -1);
		if($quantity == -1) { echo 'error'; return; }
		
		//check quantity
		$q = RPGWarehouses::getQuantityOfItemByPlayer($player->getId(), $slot);
		if($quantity > $q) $quantity = $q; 
		
		$warehouse = $player->getWarehouse();
		$item = $warehouse->getItem($slot - 1);
		if(!$item) { echo 'error'; return; }
		
		$db->sql_transaction('begin');
		
		if(!RPGWarehouses::dropQuantityOfItemByPlayerAndSlot($player, $slot, $quantity)) { echo 'error'; return; }
		if(!RPGPlayers::giveItemToPlayer($player, $item, $quantity)) { echo 'inventory_error'; return; }
		
		$db->sql_transaction('commit');
		
		echo 'retrieve_ok';
		return;
	}
	
	case 'store_ralz':
	{
		$quantity = request_var('q', -1);
		if($quantity == -1) { echo 'error'; return; }
		
		//check if quantity is not > than player ralz
		$ralz = RPGRalz::getRalzByPlayer($player->getId());
		if(!$ralz) { echo 'error'; return; }
		
		if($quantity > $ralz->getValue()) $quantity = $ralz->getValue();
		if($quantity == 0) { echo 'no_ralz'; return; }
		
		$db->sql_transaction('begin');
		if(!RPGWarehouses::storeRalzOfPlayer($player, $quantity) or !player_give_ralz($player, -1 * $quantity)) {
			$db->sql_transaction('cancel');
			echo 'error';
			return;
		} else {
			$db->sql_transaction('commit');
			echo 'store_ok';
			return;
		}
		
	}
	break;
	
	case 'retrieve_ralz':
	{
		$quantity = request_var('q', -1);
		if($quantity == -1) { echo 'error'; return; }
		
		//check if quantity is not > than warehouse ralz
		$ralz = RPGWarehouses::getRalzOfPlayer($player);
		
		if($quantity > $ralz) $quantity = $ralz;
		if($quantity == 0) { echo 'no_ralz'; return; }
		
		$db->sql_transaction('begin');
		
		if(!RPGWarehouses::retrieveRalzOfPlayer($player, $quantity) or !player_give_ralz($player, $quantity)) {
			$db->sql_transaction('cancel');
			echo 'error';
			return;
		} else {
			$db->sql_transaction('commit');
			echo 'retrieve_ok';
			return;
		}
		
	}
	break;
}

echo 'error';
?>