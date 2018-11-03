<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGAchievements.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/achievements_functions.' . $phpEx);

// Start session management
$user->session_begin();
$user->setup();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewachievements' => 'viewachievements.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));



$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_ALL);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));


//first check if any achievement can be unlocked
check_achievements($user, $player);

/*$achievements = RPGAchievements::getAchievements();
foreach($achievements as $ach) {
	if(!RPGAchievements::isUnlocked($ach->getId(), $player->getId()) and $ach->canUnlock($user->data, $player)) {
		if(!RPGAchievements::unlockAchievement($ach->getId(), $player->getId())) {
			trigger_error("Failed to unlock achievement with id: {$ach->getId()} for player with id {$player->getId()}.", E_ERROR);
		}
	}
}*/

//update achievements array
$achievements = RPGAchievements::getAchievements();

//then display achievements
$_ = array();

foreach($achievements as $key => $a) {
	if(!array_key_exists($a->getCategory()->getName(), $_)) {
		$_[$a->getCategory()->getName()] = array();
	}
	
	$_[$a->getCategory()->getName()][] = $a;
}

ksort($_, SORT_STRING);

foreach($_ as $category => $achs) {
	$t->assign_block_vars('category_bloc', array(
		'CATEGORY_NAME'	=>	$category,
	));
	
	foreach($achs as $k => $ach) {
		$t->assign_block_vars('category_bloc.achievement_bloc', array(
			'ID'	=>	$ach->getId(),
			'NAME'	=>	$ach->getName(),
			'CONDITION'	=>	( (!$ach->hideCondition() or RPGAchievements::isUnlocked($ach->getId(), $player->getId())) ? $ach->getCondition() : '[Condition cachée]'),
		));
		
		if(RPGAchievements::isUnlocked($ach->getId(), $player->getId())) {
			$t->assign_block_vars('category_bloc.achievement_bloc.unlocked_bloc', array(
			));
		} else {
			$t->assign_block_vars('category_bloc.achievement_bloc.locked_bloc', array(
			));
		}
	}
}

$t->pparse('viewachievements');

?>