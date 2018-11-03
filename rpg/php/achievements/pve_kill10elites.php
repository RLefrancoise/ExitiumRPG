<?php

$mb = RPGMonsterBooks::getMonsterBook($player->getId());

$elites_killed = 0;

$areas = RPGBattleAreas::getAreas();

foreach($areas as $area) {
	
	$parts = $area->getAreaParts();
	foreach($parts as $part) {
		
		//iterate on every monsters of this part
		$monsters = $part->getMonsters();
		foreach($monsters as $monster) {
			$monster_stats = $mb->getMonsterStats($monster->getId(), $part->getId());
			if( ($monster_stats !== false) and ($monster_stats['wins'] > 0) and (strpos($monster->getName(), "[Elite]") !== false) ) {
				$elites_killed += $monster_stats['wins'];
			}
		}
	}
}

if($elites_killed >= 10) $unlocked = true;

?>