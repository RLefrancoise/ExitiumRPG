<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGBattleAreas.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewmap' => 'viewmap.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//play BGM ?
if($player->soundEnabled()) {
	$t->assign_block_vars('background_music', array());
}

$east_coast = RPGBattleAreas::getAreaById(1);
$white_desert = RPGBattleAreas::getAreaById(2);
$dry_desert = RPGBattleAreas::getAreaById(3);
$termorr_mines = RPGBattleAreas::getAreaById(4);

$t->assign_vars(array(
	'EAST_COAST_DESC' => $east_coast->getDescription(),
	'EAST_COAST_LEVEL'	=> $east_coast->getLevel(),
	'WHITE_DESERT_DESC' => $white_desert->getDescription(),
	'WHITE_DESERT_LEVEL' => $white_desert->getLevel(),
	'DRY_DESERT_DESC' => $dry_desert->getDescription(),
	'DRY_DESERT_LEVEL' => $dry_desert->getLevel(),
	'TERMORR_MINES_DESC' => $termorr_mines->getDescription(),
	'TERMORR_MINES_LEVEL' => $termorr_mines->getLevel(),
	
	'EAST_COAST_PART1_MIN_LVL' => $east_coast->getAreaPartById(1)->getMinLevel(),
	'EAST_COAST_PART1_MAX_LVL' => $east_coast->getAreaPartById(1)->getMaxLevel(),
	'EAST_COAST_PART2_MIN_LVL' => $east_coast->getAreaPartById(2)->getMinLevel(),
	'EAST_COAST_PART2_MAX_LVL' => $east_coast->getAreaPartById(2)->getMaxLevel(),
	'EAST_COAST_PART3_MIN_LVL' => $east_coast->getAreaPartById(3)->getMinLevel(),
	'EAST_COAST_PART3_MAX_LVL' => $east_coast->getAreaPartById(3)->getMaxLevel(),
	'EAST_COAST_PART4_MIN_LVL' => $east_coast->getAreaPartById(4)->getMinLevel(),
	'EAST_COAST_PART4_MAX_LVL' => $east_coast->getAreaPartById(4)->getMaxLevel(),
	
	'WHITE_DESERT_PART1_MIN_LVL' => $white_desert->getAreaPartById(5)->getMinLevel(),
	'WHITE_DESERT_PART1_MAX_LVL' => $white_desert->getAreaPartById(5)->getMaxLevel(),
	'WHITE_DESERT_PART2_MIN_LVL' => $white_desert->getAreaPartById(6)->getMinLevel(),
	'WHITE_DESERT_PART2_MAX_LVL' => $white_desert->getAreaPartById(6)->getMaxLevel(),
	'WHITE_DESERT_PART3_MIN_LVL' => $white_desert->getAreaPartById(7)->getMinLevel(),
	'WHITE_DESERT_PART3_MAX_LVL' => $white_desert->getAreaPartById(7)->getMaxLevel(),
	'WHITE_DESERT_PART4_MIN_LVL' => $white_desert->getAreaPartById(8)->getMinLevel(),
	'WHITE_DESERT_PART4_MAX_LVL' => $white_desert->getAreaPartById(8)->getMaxLevel(),
	
	'DRY_DESERT_PART1_MIN_LVL' => $dry_desert->getAreaPartById(9)->getMinLevel(),
	'DRY_DESERT_PART1_MAX_LVL' => $dry_desert->getAreaPartById(9)->getMaxLevel(),
	'DRY_DESERT_PART2_MIN_LVL' => $dry_desert->getAreaPartById(10)->getMinLevel(),
	'DRY_DESERT_PART2_MAX_LVL' => $dry_desert->getAreaPartById(10)->getMaxLevel(),
	'DRY_DESERT_PART3_MIN_LVL' => $dry_desert->getAreaPartById(11)->getMinLevel(),
	'DRY_DESERT_PART3_MAX_LVL' => $dry_desert->getAreaPartById(11)->getMaxLevel(),
	'DRY_DESERT_PART4_MIN_LVL' => $dry_desert->getAreaPartById(12)->getMinLevel(),
	'DRY_DESERT_PART4_MAX_LVL' => $dry_desert->getAreaPartById(12)->getMaxLevel(),
	
	'TERMORR_PART1_LVL'			=> ($termorr_mines->getAreaPartById(13)->getMinLevel() != $termorr_mines->getAreaPartById(13)->getMaxLevel()) ? $termorr_mines->getAreaPartById(13)->getMinLevel() . '-' . $termorr_mines->getAreaPartById(13)->getMaxLevel() : $termorr_mines->getAreaPartById(13)->getMinLevel(),
	'TERMORR_PART2_LVL'			=> ($termorr_mines->getAreaPartById(14)->getMinLevel() != $termorr_mines->getAreaPartById(14)->getMaxLevel()) ? $termorr_mines->getAreaPartById(14)->getMinLevel() . '-' . $termorr_mines->getAreaPartById(14)->getMaxLevel() : $termorr_mines->getAreaPartById(14)->getMinLevel(),
	'TERMORR_PART3_LVL'			=> ($termorr_mines->getAreaPartById(15)->getMinLevel() != $termorr_mines->getAreaPartById(15)->getMaxLevel()) ? $termorr_mines->getAreaPartById(15)->getMinLevel() . '-' . $termorr_mines->getAreaPartById(15)->getMaxLevel() : $termorr_mines->getAreaPartById(15)->getMinLevel(),
	'TERMORR_PART4_LVL'			=> ($termorr_mines->getAreaPartById(16)->getMinLevel() != $termorr_mines->getAreaPartById(16)->getMaxLevel()) ? $termorr_mines->getAreaPartById(16)->getMinLevel() . '-' . $termorr_mines->getAreaPartById(16)->getMaxLevel() : $termorr_mines->getAreaPartById(16)->getMinLevel(),
));

$t->pparse('viewmap');

?>