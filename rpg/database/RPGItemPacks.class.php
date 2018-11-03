<?php

include_once(__DIR__ . '/../../common.php');
	
include_once(__DIR__ . "/../classes/ItemPack.class.php");
include_once(__DIR__ . "/../database/RPGPlayers.class.php");

include_once(__DIR__ . "/../php/item_functions.php");

class RPGItemPacks {

	private function __construct() {
	}
	
	public static function getPack($id) {
		global $db;
		
		$sql = 'SELECT DISTINCT *
				FROM rpg_items_packs
				WHERE id = ' . $id;
				
		$result = $db->sql_query($sql);
		$info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		if(!$info) return null;
		
		$info['items'] = RPGItemPacks::getItemsOfPack($id);
		
		$p = new ItemPack($info);
		return $p;
	}
	
	public static function getItemsOfPack($pack_id) {
		global $db;
		
		$sql = 'SELECT DISTINCT *
				FROM rpg_items_packs_items
				WHERE pack_id = ' . $pack_id;
		$result = $db->sql_query($sql);
		
		$items = array();
		
		while($info = $db->sql_fetchrow($result)) {
			$item = get_item($info['item_id'], $info['item_type']);
			if(!$item) continue;
			
			$elem = new ItemPackElement($item, $info['number']);
			$items[] = $elem;
		}
		
		$db->sql_freeresult($result);
		
		return $items;
	}
	
	public static function giveItemPack(ItemPack &$pack, Player &$player) {
		global $db;
		
		$items = $pack->getItems();
		
		foreach ($items as $item) {
			if(!RPGPlayers::giveItemToPlayer($player, $item->item, $item->number))
				return false;
		}
		
		return true;
	}
	
}

?>