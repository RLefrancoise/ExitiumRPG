<?php

include_once("../rpg/database/RPGUsersPlayers.class.php");

$player = RPGUsersPlayers::getPlayerByUserId(2);
print_r($player);

?>