<?php

$orb1 = $player->getOrb(1);
$orb2 = $player->getOrb(2);
$orb3 = $player->getOrb(3);
$orb4 = $player->getOrb(4);

if(	$orb1 and $orb1->isPassive() and
	$orb2 and $orb2->isPassive() and
	$orb3 and $orb3->isPassive() and
	$orb4 and $orb4->isPassive()
) $unlocked = true;

?>