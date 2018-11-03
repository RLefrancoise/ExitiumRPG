<?php

$res = Weapon::compareGrade($player->getWeapon()->getGrade(), WEAPON_GRADE_SS);
if($res == 0 or $res == 1) $unlocked = true;

?>
