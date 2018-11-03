<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGInventories.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/status_functions.' . $phpEx);

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

$slot = (isset($_GET["slot"])) ? intval($_GET["slot"]) : null;

if ( ($slot !== null) and ($slot > 0) and ($slot <= INVENTORY_SIZE) ) {
	$item_type = RPGInventories::getTypeOfItemByPlayerAndSlot($player->getId(), $slot);
	
	switch($item_type) {
		case 'cloth':
		case 'leggings':
		case 'glove':
		case 'shoe':
			echo get_equipment_inventory_html($slot);
			break;
		case 'orb':
			echo get_orb_inventory_html($slot);
			break;
		case 'syringe':
			echo get_syringe_inventory_html($slot);
			break;
		case 'ralz':
			echo get_ralz_inventory_html($slot);
			break;
		case 'special':
			echo get_special_inventory_html($slot);
			break;
		default:
			echo 'error';
			break;
	}
} else {
    echo "error";
}
 
?>