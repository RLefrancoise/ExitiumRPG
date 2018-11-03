<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pvp_total_wins') >= 50) $unlocked = true;

?>