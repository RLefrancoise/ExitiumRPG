<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pve_total_battles') >= 1) $unlocked = true;

?>