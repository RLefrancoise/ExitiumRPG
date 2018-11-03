<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGSets.class.php");
	include_once(__DIR__ . "/RPGClothes.class.php");
	include_once(__DIR__ . "/RPGLeggings.class.php");
	include_once(__DIR__ . "/RPGGloves.class.php");
	include_once(__DIR__ . "/RPGShoes.class.php");
	include_once(__DIR__ . "/RPGUpgrades.class.php");
	include_once(__DIR__ . "/RPGSyringes.class.php");
	include_once(__DIR__ . "/RPGSpecials.class.php");
	include_once(__DIR__ . "/../classes/ArmorPart.class.php");
	
	define(CATEGORY_SETS, 'sets');
	define(CATEGORY_EQUIPS, 'equips');
	define(CATEGORY_UPGRADES, 'upgrades');
	define(CATEGORY_SYRINGES, 'syringes');
	define(CATEGORY_SPECIAL, 'special');
	
	class RPGBlackMarket {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getItemByCategoryAndPlace($category, $place){
			global $db;
			
			$sql = 'SELECT DISTINCT item_id 
					FROM rpg_blackmarket 
					WHERE category = LOWER(\'' . $db->sql_escape($category) . '\') 
					AND place = ' . $db->sql_escape($place);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$item = null;
			
			switch($category){
				case CATEGORY_SETS:
					$item = RPGSets::getSet($info['item_id']);
					break;
				case CATEGORY_EQUIPS:
					$item = RPGBlackMarket::getPart($info['item_id']);
					break;
				case CATEGORY_UPGRADES:
					$item = RPGUpgrades::getUpgrade($info['item_id']);
					break;
				case CATEGORY_SYRINGES:
					$item = RPGSyringes::getSyringe($info['item_id']);
					break;
				case CATEGORY_SPECIAL:
					$item = RPGSpecials::getSpecial($info['item_id']);
					break;
				default:
					break;
			}
			
			return $item;
		}
		
		public static function getItemsByCategory($category) {
			global $db;
			
			$sql = 'SELECT DISTINCT item_id
					FROM rpg_blackmarket
					WHERE category = LOWER(\'' . $db->sql_escape($category) . '\')
					ORDER BY place';
			$result = $db->sql_query($sql);
		
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT item_id FROM rpg_blackmarket WHERE category = LOWER(?) ORDER BY place');
			$req->execute(array($category));*/
			
			$items = array();
			
			//while($info = $req->fetch()) {
			while($info = $db->sql_fetchrow($result)) {
				switch($category) {
					case CATEGORY_SETS:
						$items[] = RPGSets::getSet($info['item_id']);
						break;
					case CATEGORY_EQUIPS:
						$items[] = RPGBlackMarket::getPart($info['item_id']);
						break;
					case CATEGORY_UPGRADES:
						$items[] = RPGUpgrades::getUpgrade($info['item_id']);
						break;
					case CATEGORY_SYRINGES:
						$items[] = RPGSyringes::getSyringe($info['item_id']);
						break;
					case CATEGORY_SPECIAL:
						$items[] = RPGSpecials::getSpecial($info['item_id']);
						break;
					default:
						break;
				}
			}
			
			//$req->closeCursor();
			$db->sql_freeresult($result);
			
			return $items;
		}
		
		public static function getPlacesByCategory($category) {
			global $db;
			
			$sql = 'SELECT DISTINCT place 
					FROM rpg_blackmarket 
					WHERE category = LOWER(\'' . $db->sql_escape($category) . '\') 
					ORDER BY place';
			$result = $db->sql_query($sql);
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT place FROM rpg_blackmarket WHERE category = LOWER(?) ORDER BY place');
			$req->execute(array($category));*/
			
			$places = array();
			
			//while($info = $req->fetch()) {
			while($info = $db->sql_fetchrow($result)) {
				$places[] = $info['place'];
			}
			
			//$req->closeCursor();
			$db->sql_freeresult($result);
			
			return $places;
		}
		
		public static function getPart($part_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_blackmarket_equips 
					WHERE id = ' . $db->sql_escape($part_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_blackmarket_equips WHERE id = ?');
			$req->execute(array($part_id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			$part = null;
			
			switch($info['type']){
				case ARMOR_CLOTH:
					$part = new SetPart(RPGClothes::getCloth($info['item_id']), ARMOR_CLOTH);
					break;
				case ARMOR_LEGGINGS:
					$part = new SetPart(RPGLeggings::getLegging($info['item_id']), ARMOR_LEGGINGS);
					break;
				case ARMOR_GLOVES:
					$part = new SetPart(RPGGloves::getGlove($info['item_id']), ARMOR_GLOVES);
					break;
				case ARMOR_SHOES:
					$part = new SetPart(RPGShoes::getShoe($info['item_id']), ARMOR_SHOES);
					break;
			}
			
			return $part;
		}
		
	}
?>