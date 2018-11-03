<?php

if($player->getSet() !== null) {

	$cloth = $player->getEquipment(ARMOR_CLOTH);
	$leggings = $player->getEquipment(ARMOR_LEGGINGS);
	$gloves = $player->getEquipment(ARMOR_GLOVES);
	$shoes = $player->getEquipment(ARMOR_SHOES);

	$number = 0;

	//equipped epic cloth ?
	if($cloth) {
		switch($cloth->getPartId()) {
			case 7:
			case 8:
			case 9:
				$number++;
		}
	}

	//equipped epic leggings ?
	if($leggings) {
		switch($leggings->getPartId()) {
			case 7:
			case 8:
			case 9:
				$number++;
		}
	}


	//equipped epic gloves ?
	if($gloves) {
		switch($gloves->getPartId()) {
			case 7:
			case 8:
			case 9:
				$number++;
		}
	}


	//equipped epic shoes ?
	if($shoes) {
		switch($shoes->getPartId()) {
			case 7:
			case 8:
			case 9:
				$number++;
		}
	}

	if($number == 4) $unlocked = true;
}

?>