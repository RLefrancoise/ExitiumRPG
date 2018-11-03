<?php

$player_stats = $player->getPlayerStats();
if($player_stats->getStat('quests_number') >= 20) $unlocked = true;

?>
