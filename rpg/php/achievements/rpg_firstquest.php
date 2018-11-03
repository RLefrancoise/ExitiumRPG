<?php

$player_stats = $player->getPlayerStats();
if($player_stats->getStat('quests_number') >= 1) $unlocked = true;

?>
