<?php

$player_stats = $player->getPlayerStats();

if($player_stats->getStat('inn_times') >= 200) $unlocked = true;

?>
