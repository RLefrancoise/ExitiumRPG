<?php

$player_stats = $player->getPlayerStats();
if($player_stats->getStat('event_times') >= 1) $unlocked = true;

?>
