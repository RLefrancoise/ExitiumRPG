<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGSets.class.php");
	include_once(__DIR__ . "/RPGClothes.class.php");
	include_once(__DIR__ . "/RPGLeggings.class.php");
	include_once(__DIR__ . "/RPGGloves.class.php");
	include_once(__DIR__ . "/RPGShoes.class.php");
	include_once(__DIR__ . "/RPGUpgrades.class.php");
	include_once(__DIR__ . "/RPGSyringes.class.php");
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
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT item_id FROM rpg_blackmarket WHERE category = LOWER(?) AND place = ?');
			$req->execute(array($category, $place));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$item = null;
			
			switch($category){
				case 'sets':
					$item = RPGSets::getSet($info['item_id']);
					break;
				case 'equips':
					$item = RPGBlackMarket::getPart($info['item_id']);
					break;
				case 'upgrades':
					$item = RPGUpgrades::getUpgrade($info['item_id']);
					break;
				case 'syringes':
					$item = RPGSyringes::getSyringe($info['item_id']);
					break;
				default:
					break;
			}
			
			return $item;
		}
		
		public static function getItemsByCategory($category) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT item_id FROM rpg_blackmarket WHERE category = LOWER(?) ORDER BY place');
			$req->execute(array($category));
			
			$items = array();
			
			while($info = $req->fetch()) {
				switch($category) {
					case 'sets':
						$items[] = RPGSets::getSet($info['item_id']);
						break;
					case 'equips':
						$items[] = RPGBlackMarket::getPart($info['item_id']);
						break;
					case 'upgrades':
						$items[] = RPGUpgrades::getUpgrade($info['item_id']);
						break;
					case 'syringes':
						$items[] = RPGSyringes::getSyringe($info['item_id']);
						break;
						
					default:
						break;
				}
			}
			
			$req->closeCursor();
			
			return $items;
		}
		
		public static function getPlacesByCategory($category) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT place FROM rpg_blackmarket WHERE category = LOWER(?) ORDER BY place');
			$req->execute(array($category));
			
			$places = array();
			
			while($info = $req->fetch()) {
				$places[] = $info['place'];
			}
			
			$req->closeCursor();
			
			return $places;
		}
		
		public static function getPart($part_id) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_blackmarket_equips WHERE id = ?');
			$req->execute(array($part_id));
			
			$info = $req->fetch();
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