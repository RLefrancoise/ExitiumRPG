<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('war_total_wins') >= 3) $unlocked = true;

?>