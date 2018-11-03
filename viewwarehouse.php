<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGWarehouses.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);

// Start session management
$user->session_begin();
$user->setup();
$auth->acl($user->data);

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL | PLAYER_INVENTORY | PLAYER_WAREHOUSE);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

$part = request_var('part', '');

if($part == 'warehouse' or $part == 'inventory' or $part == 'info') {

	if($user->data['username'] == "Anonymous") {
		echo "not_connected";
		return;
	}
	
	if($part == 'warehouse') {
		
		$t = new CustomTemplate('./rpg/tpl');
		$t->set_filenames(array('warehouse_items' => 'warehouse_items.tpl'));
		
		//warehouse
		$warehouse = $player->getWarehouse();

		for($i = 0 ; $i < WAREHOUSE_SIZE ; $i++) {
			
			$item = $warehouse->getItem($i);
			if($item) {
				$t->assign_block_vars('item', array(
					'ITEM_ICON' => 'images/rpg/icons/'. $item->getIcon(),
					'ITEM_SLOT'	=> $i + 1,
					'MULTI'		=> $item->isOnePerSlot() ? 'false' : 'true',
					'MOUSE_OVER'	=> 	"tooltip.show(this)",
					'MOUSE_OUT'		=> 	"tooltip.hide(this)",
					'TOOLTIP_TEXT' => $item->isOnePerSlot() ? $item->getToolTipText() : $item->getToolTipText() . '<br>Quantité : ' . $warehouse->getQuantityOfItem($i),
					'ON_CLICK'		=>	"javascript:retrieve(" . ($i + 1) . ", " . ($item->isOnePerSlot() ? 'false' : 'true') . ")",
					'STYLE'			=>	"cursor:pointer;",
					'ITEM_X'		=>	19 + (($i % 12) * 63) + 21,
					'ITEM_Y'		=>	15 + ((int) floor($i / 12)) * 55 + 21 + 55,
				));
			} else {
				$t->assign_block_vars('item', array(
					'ITEM_ICON' 	=> 	'images/rpg/icons/empty.png',
					'ON_CLICK'	=>	"",
					'STYLE'		=>	"",
					'ITEM_X'		=>	19 + (($i % 12) * 63) + 21,
					'ITEM_Y'		=>	15 + ((int) floor($i / 12)) * 55 + 21 + 55,
				));
			}
		}
		
		$t->pparse('warehouse_items');
		
		return;
	}
	else if($part == 'inventory') {
		
		$t = new CustomTemplate('./rpg/tpl');
		$t->set_filenames(array('warehouse_inventory' => 'warehouse_inventory.tpl'));
		
		$inventory = $player->getInventory();
			
		for($i = 0 ; $i < INVENTORY_SIZE ; $i++) {
			
			$item = $inventory->getItem($i);
			if($item) {
				$t->assign_block_vars('inventory_item', array(
					'ITEM_ICON' 	=> 	'images/rpg/icons/'. $item->getIcon(),
					'ITEM_SLOT'		=> 	$i + 1,
					'MULTI'			=> 	$item->isOnePerSlot() ? 'false' : 'true',
					'MOUSE_OVER'	=> 	"tooltip.show(this)",
					'MOUSE_OUT'		=> 	"tooltip.hide(this)",
					'TOOLTIP_TEXT' 	=> 	$item->isOnePerSlot() ? $item->getToolTipText() : $item->getToolTipText() . '<br>Quantité : ' . $inventory->getQuantityOfItem($i),
					'ON_CLICK'		=>	($inventory->getTypeOfItem($item) !== 'ralz') ? "javascript:store(" . ($i + 1) . ", " . ($item->isOnePerSlot() ? 'false' : 'true') . ")" : "",
					'STYLE'			=>	($inventory->getTypeOfItem($item) !== 'ralz') ? "cursor:pointer;" : "",
					'ITEM_X'		=>	19 + (($i % 8) * 63) + 21,
					'ITEM_Y'		=>	15 + ((int) floor($i / 8)) * 55 + 21,
				));
			} else {
				$t->assign_block_vars('inventory_item', array(
					'ITEM_ICON' 	=> 	'images/rpg/icons/empty.png',
					'ITEM_SLOT'		=> 	$i + 1,
					'MULTI'			=> 	'false',
					'MOUSE_OVER'	=> 	"",
					'MOUSE_OUT'		=> 	"",
					'TOOLTIP_TEXT' 	=> 	"",
					'ON_CLICK'		=>	"",
					'STYLE'			=>	"",
					'ITEM_X'		=>	19 + (($i % 8) * 63) + 21,
					'ITEM_Y'		=>	15 + ((int) floor($i / 8)) * 55 + 21,
				));
			}
		}
		
		$t->pparse('warehouse_inventory');
		
		return;
	}
	else if($part == 'info') {
		
		$t = new CustomTemplate('./rpg/tpl');
		$t->set_filenames(array('warehouse_info' => 'warehouse_info.tpl'));
		
		$t->assign_vars(array(
			'RALZ'		=>	RPGWarehouses::getRalzOfPlayer($player),
			'CALL_RATE'	=>	CALL_RATE * 100,
		));
		
		$t->pparse('warehouse_info');
		
		return;
	}

}

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewwarehouse' => 'viewwarehouse.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));



$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL | PLAYER_INVENTORY | PLAYER_WAREHOUSE);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//play BGM ?
if($player->soundEnabled()) {
	$t->assign_block_vars('background_music', array());
}

//warehouse
$warehouse = $player->getWarehouse();

for($i = 0 ; $i < WAREHOUSE_SIZE ; $i++) {
	
	$item = $warehouse->getItem($i);
	if($item) {
		$t->assign_block_vars('item', array(
			'ITEM_ICON' => 'images/rpg/icons/'. $item->getIcon(),
			'ITEM_SLOT'	=> $i + 1,
			'MULTI'		=> $item->isOnePerSlot() ? 'false' : 'true',
			'MOUSE_OVER'	=> 	"tooltip.show(this)",
			'MOUSE_OUT'		=> 	"tooltip.hide(this)",
			'TOOLTIP_TEXT' => $item->isOnePerSlot() ? $item->getToolTipText() : $item->getToolTipText() . '<br>Quantité : ' . $warehouse->getQuantityOfItem($i),
			'ON_CLICK'		=>	"javascript:retrieve(" . ($i + 1) . ", " . ($item->isOnePerSlot() ? 'false' : 'true') . ")",
			'STYLE'			=>	"cursor:pointer;",
			'ITEM_X'		=>	19 + (($i % 12) * 63) + 21,
			'ITEM_Y'		=>	15 + ((int) floor($i / 12)) * 55 + 21 + 55,
		));
	} else {
		//$t->assign_block_vars('no_item', array());
		$t->assign_block_vars('item', array(
			'ITEM_ICON' 	=> 	'images/rpg/icons/empty.png',
			'ON_CLICK'	=>	"",
			'STYLE'		=>	"",
			'ITEM_X'		=>	19 + (($i % 12) * 63) + 21,
			'ITEM_Y'		=>	15 + ((int) floor($i / 12)) * 55 + 21 + 55,
		));
	}
}

//inventory
$inventory = $player->getInventory();
			
for($i = 0 ; $i < INVENTORY_SIZE ; $i++) {
	
	$item = $inventory->getItem($i);
	if($item) {
		$t->assign_block_vars('inventory_item', array(
			'ITEM_ICON' 	=> 	'images/rpg/icons/'. $item->getIcon(),
			'ITEM_SLOT'		=> 	$i + 1,
			'MULTI'			=> 	$item->isOnePerSlot() ? 'false' : 'true',
			'MOUSE_OVER'	=> 	"tooltip.show(this)",
			'MOUSE_OUT'		=> 	"tooltip.hide(this)",
			'TOOLTIP_TEXT' 	=> 	$item->isOnePerSlot() ? $item->getToolTipText() : $item->getToolTipText() . '<br>Quantité : ' . $inventory->getQuantityOfItem($i),
			'ON_CLICK'		=>	($inventory->getTypeOfItem($item) !== 'ralz') ? "javascript:store(" . ($i + 1) . ", " . ($item->isOnePerSlot() ? 'false' : 'true') . ")" : "",
			'STYLE'			=>	($inventory->getTypeOfItem($item) !== 'ralz') ? "cursor:pointer;" : "",
			'ITEM_X'		=>	19 + (($i % 8) * 63) + 21,
			'ITEM_Y'		=>	15 + ((int) floor($i / 8)) * 55 + 21,
		));
	} else {
		$t->assign_block_vars('inventory_item', array(
			'ITEM_ICON' 	=> 	'images/rpg/icons/empty.png',
			'ITEM_SLOT'		=> 	$i + 1,
			'MULTI'			=> 	'false',
			'MOUSE_OVER'	=> 	"",
			'MOUSE_OUT'		=> 	"",
			'TOOLTIP_TEXT' 	=> 	"",
			'ON_CLICK'		=>	"",
			'STYLE'			=>	"",
			'ITEM_X'		=>	19 + (($i % 8) * 63) + 21,
			'ITEM_Y'		=>	15 + ((int) floor($i / 8)) * 55 + 21,
		));
	}
}

//other data
$year = gmdate('y');
$month = gmdate('n');
$day = gmdate('j');

$cr_date = gmmktime(24, 7, 0, $month, $day, $year);

$date1	= new DateTime(gmdate("Y/m/d H:i:s", $cr_date));
$date2	= new DateTime($user->format_date(time(), 'Y/m/d H:i:s'));

$time_span = $date1->diff($date2);

$t->assign_vars(array(
	'RALZ'		=>	RPGWarehouses::getRalzOfPlayer($player),
	'CALL_RATE'	=>	CALL_RATE * 100,
	'CALL_RATE_CLOCK'	=>	($time_span->h > 9 ? $time_span->h : "0" . $time_span->h) . ":" . ($time_span->i > 9 ? $time_span->i : "0" . $time_span->i) . ":" . ($time_span->s > 9 ? $time_span->s : "0" . $time_span->s),
));

$t->pparse('viewwarehouse');

?>