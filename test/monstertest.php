<?php

echo 'MONSTER TEST', PHP_EOL;

include_once(__DIR__ . "/../rpg/database/RPGMonsters.class.php");

$monster = RPGMonsters::getMonster(1);
if(!$monster)
	echo 'no monster';
else
	print_r($monster);

?>