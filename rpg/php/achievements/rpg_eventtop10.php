<?php

$player_stats = $player->getPlayerStats();
if( ($player_stats->getStat('event_best_rank') != 0) and ($player_stats->getStat('event_best_rank') <= 10) ) $unlocked = true;

?>
