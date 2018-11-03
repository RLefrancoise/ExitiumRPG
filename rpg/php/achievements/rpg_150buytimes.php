<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('buy_times') >= 150) $unlocked = true;

?>
