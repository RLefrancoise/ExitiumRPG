<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('war_total_wins') + $player_stats->getStat('war_total_loses') >= 1) $unlocked = true;

?>