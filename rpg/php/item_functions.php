<?php

include_once(__DIR__ . '/../database/RPGClothes.class.php');
include_once(__DIR__ . '/../database/RPGLeggings.class.php');
include_once(__DIR__ . '/../database/RPGGloves.class.php');
include_once(__DIR__ . '/../database/RPGShoes.class.php');
include_once(__DIR__ . '/../database/RPGSyringes.class.php');
include_once(__DIR__ . '/../database/RPGSpecials.class.php');
include_once(__DIR__ . '/../database/RPGOrbs.class.php');

function get_item($id, $type) {
	switch($type) {
	
		case 'syringe':
			return RPGSyringes::getSyringe($id);
		case 'special':
			return RPGSpecials::getSpecial($id);
		case 'cloth':
			return new SetPart(RPGClothes::getCloth($id), ARMOR_CLOTH);
		case 'leggings':
			return new SetPart(RPGLeggings::getLegging($id), ARMOR_LEGGINGS);
		case 'gloves':
			return new SetPart(RPGGloves::getGlove($id), ARMOR_GLOVES);
		case 'shoes':
			return new SetPart(RPGShoes::getShoe($id), ARMOR_SHOES);
		case 'orb':
			return RPGOrbs::getOrb($id);
		default:
			return false;
	}
}
?>
