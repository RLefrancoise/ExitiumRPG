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
include_once($phpbb_root_path . 'rpg/php/string_functions.' . $phpEx);

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
$q = (isset($_GET["q"])) ? intval($_GET["q"]) : null;

if ( ($q !== null) and ($slot !== null) and ($slot > 0) and ($slot <= INVENTORY_SIZE) ) {
	$item = RPGInventories::getItemByPlayerAndSlot($player, $slot);
	
	if($item === null) echo 'error';
	else {
		$item_quantity = RPGInventories::getQuantityOfItemByPlayer($player->getId(), $slot);
		if($item_quantity <= $q)
			echo ((int)($item->getPrice() / 2)) * $item_quantity . '|'; // | rajouté pour pouvoir séparer la valeur d'éventuel contenu ajouté après
		else
			echo ($item->getPrice() / 2) * $q . '|';
	}
} else {
    echo "error";
}
 
?>