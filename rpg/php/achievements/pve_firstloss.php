<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pve_total_loses') >= 1) $unlocked = true;

?>