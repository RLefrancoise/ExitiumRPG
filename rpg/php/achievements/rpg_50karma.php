<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('karma_points') >= 50) $unlocked = true;

?>
