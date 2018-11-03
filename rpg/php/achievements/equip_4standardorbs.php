<?php

$orb1 = $player->getOrb(1);
$orb2 = $player->getOrb(2);
$orb3 = $player->getOrb(3);
$orb4 = $player->getOrb(4);

if(	$orb1 and $orb1->isStandard() and
	$orb2 and $orb2->isStandard() and
	$orb3 and $orb3->isStandard() and
	$orb4 and $orb4->isStandard()
) $unlocked = true;

?>