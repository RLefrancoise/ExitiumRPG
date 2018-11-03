<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pve_total_wins') >= 750) $unlocked = true;

?>