<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pvp_total_wins') >= 1) $unlocked = true;

?>