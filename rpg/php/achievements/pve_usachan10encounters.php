<?php

$mb = RPGMonsterBooks::getMonsterBook($player->getId());

$encounters = 0;

$areas = RPGBattleAreas::getAreas();

foreach($areas as $area) {
	
	$parts = $area->getAreaParts();
	foreach($parts as $part) {
		$monster_stats = $mb->getMonsterStats(6, $part->getId());
		if( ($monster_stats !== false) and ($monster_stats['encounters'] > 0) ) {
			$encounters++;
		}
	}
}

if($encounters >= 10) $unlocked = true;

?>