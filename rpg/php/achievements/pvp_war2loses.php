<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('war_total_loses') >= 2) $unlocked = true;

?>