<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('karma_points') >= 1) $unlocked = true;

?>
