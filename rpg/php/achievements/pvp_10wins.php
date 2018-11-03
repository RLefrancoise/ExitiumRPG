<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pvp_total_wins') >= 10) $unlocked = true;

?>