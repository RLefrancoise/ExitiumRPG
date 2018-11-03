<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('pve_total_battles') >= 300) $unlocked = true;

?>