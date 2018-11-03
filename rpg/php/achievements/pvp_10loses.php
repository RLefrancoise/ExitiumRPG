<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pvp_total_loses') >= 10) $unlocked = true;

?>