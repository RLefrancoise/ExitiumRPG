<?php

include_once('../database/RPGAchievements.class.php');

function check_achievements($user, Player &$player) {
	//first check if any achievement can be unlocked
	$achievements = RPGAchievements::getAchievements();
	foreach($achievements as $ach) {
		if(!RPGAchievements::isUnlocked($ach->getId(), $player->getId()) and $ach->canUnlock($user->data, $player)) {
			if(!RPGAchievements::unlockAchievement($ach->getId(), $player->getId())) {
				trigger_error("Failed to unlock achievement with id: {$ach->getId()} for player with id {$player->getId()}.", E_ERROR);
			}
		}
	}

}
?>