<?php

$player_stats = $player->getPlayerStats();
if( $player_stats->getStat('warehouse_max_slots') >= WAREHOUSE_SIZE ) $unlocked = true;

?>
