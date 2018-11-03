<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGEventBattles.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);
include_once($phpbb_root_path . 'rpg/php/string_functions.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "Vous n'êtes pas connecté.";
	die();
}

$items = array(
	1	=> array(
		array(
			'item_type'	=> 'clothes',
			'item_id'	=> 1,
			'number'	=> 1,
		),
		array(
			'item_type'	=> 'syringe',
			'item_id'	=> 4,
			'number'	=> 5,
		),
	),
	2	=> array(
		array(
			'item_type'	=> 'orb',
			'item_id'	=> 1,
			'number'	=> 1,
		),
		array(
			'item_type'	=> 'orb',
			'item_id'	=> 4,
			'number'	=> 1,
		),
	),
);

$token = RPGEventBattles::createEvent(76, 50, 0, 'Event', '', 12, 144, array());
if(!$token) { echo 'Erreur lors de la création de l\'event.'; return; }

//print_r(RPGEventBattles::getEventItems($token));

//$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
//RPGPlayers::giveItemToPlayer($player, RPGSyringes::getSyringe(1));

//echo RPGEventBattles::giveEventItems('97ff613724178952de37a196c4e99aa9');

echo 'Event créé.';

?>