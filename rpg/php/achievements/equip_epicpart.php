<?php

$cloth = $player->getEquipment(ARMOR_CLOTH);
$leggings = $player->getEquipment(ARMOR_LEGGINGS);
$gloves = $player->getEquipment(ARMOR_GLOVES);
$shoes = $player->getEquipment(ARMOR_SHOES);

//equipped epic cloth ?
if($cloth) {
	switch($cloth->getPartId()) {
		case 7:
		case 8:
		case 9:
			$unlocked = true;
			return;
	}
}

//equipped epic leggings ?
if($leggings) {
	switch($leggings->getPartId()) {
		case 7:
		case 8:
		case 9:
			$unlocked = true;
			return;
	}
}


//equipped epic gloves ?
if($gloves) {
	switch($gloves->getPartId()) {
		case 7:
		case 8:
		case 9:
			$unlocked = true;
			return;
	}
}


//equipped epic shoes ?
if($shoes) {
	switch($shoes->getPartId()) {
		case 7:
		case 8:
		case 9:
			$unlocked = true;
			return;
	}
}


?>