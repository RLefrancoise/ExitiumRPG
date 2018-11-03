<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGBlackMarket.class.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$mode = request_var('mode', '');
if($mode == '') die();

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewblackmarketlist' => 'viewblackmarketlist.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
));

$t->assign_vars(array(
	'MODE'	=> $mode,
));



$items = array();
$places = array();

switch($mode) {
	case 'sets':
		$items = RPGBlackMarket::getItemsByCategory(CATEGORY_SETS);
		$places = RPGBlackMarket::getPlacesByCategory(CATEGORY_SETS);
		break;
	case 'equips':
		$items = RPGBlackMarket::getItemsByCategory(CATEGORY_EQUIPS);
		$places = RPGBlackMarket::getPlacesByCategory(CATEGORY_EQUIPS);
		break;
	case 'upgrades':
		$items = RPGBlackMarket::getItemsByCategory(CATEGORY_UPGRADES);
		$places = RPGBlackMarket::getPlacesByCategory(CATEGORY_UPGRADES);
		break;
	case 'syringes':
		$items = RPGBlackMarket::getItemsByCategory(CATEGORY_SYRINGES);
		$places = RPGBlackMarket::getPlacesByCategory(CATEGORY_SYRINGES);
		break;
	case 'special':
		$items = RPGBlackMarket::getItemsByCategory(CATEGORY_SPECIAL);
		$places = RPGBlackMarket::getPlacesByCategory(CATEGORY_SPECIAL);
		break;
	default:
		break;
}

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
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

if($items) {
	$i = 0;
	for($i = 0 ; $i < count($items) ; $i++) {
		if(!$items[$i]) continue;
		
		$name = $items[$i]->getName();
		//in case of equips, put required level in the name
		if($mode == 'equips') {
			$name = $name . ' [Niveau ' . $items[$i]->getRequiredLevel() . ']';
		}
		//in case of upgrades, display only upgrades and not downgrades
		if($mode == 'upgrades') {
			if(!$player->getWeapon()->isUnderGrade($items[$i]->getGrade())) continue;
			//if($items[$i]->getGrade() <= $player->getWeapon()->getGrade()) continue; 
		}
		
		$t->assign_block_vars('items_bloc', array(
			'ITEM_PLACE'=>	$places[$i],
			'ITEM_NAME'	=> $name,
			'ITEM_DESC'	=> $items[$i]->getDescription(),
			'ITEM_PRICE'=> $items[$i]->getPrice(),	
		));
	}
}

$t->pparse('viewblackmarketlist');

?>