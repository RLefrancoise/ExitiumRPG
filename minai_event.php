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

//look if minai event is not already created
$sql = 'SELECT DISTINCT *
		FROM rpg_event_battles
		WHERE forum_id = 26
		AND topic_id = 118';
		
$result = $db->sql_query($sql);
$info = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

if($info) { echo 'Cet event a déjà été créé.'; return; }


$items = array(
	1	=> array(
		array(
			'item_type'	=> 'syringe',
			'item_id'	=> 3,
			'number'	=> 25,
		),
	),
	2	=> array(
		array(
			'item_type'	=> 'syringe',
			'item_id'	=> 3,
			'number'	=> 20,
		),
	),
	3	=> array(
		array(
			'item_type'	=> 'syringe',
			'item_id'	=> 3,
			'number'	=> 15,
		),
	),
	4	=> array(
		array(
			'item_type'	=> 'syringe',
			'item_id'	=> 3,
			'number'	=> 10,
		),
	),
	5	=> array(
		array(
			'item_type'	=> 'syringe',
			'item_id'	=> 3,
			'number'	=> 5,
		),
	),
);

$token = RPGEventBattles::createEvent(75, 6000, 600, 'Event', 'desert blanc.jpg', 26, 118, $items);
if(!$token) { echo 'Erreur lors de la création de l\'event.'; return; }

//print_r(RPGEventBattles::getEventItems($token));

//$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
//RPGPlayers::giveItemToPlayer($player, RPGSyringes::getSyringe(1));

//echo RPGEventBattles::giveEventItems('97ff613724178952de37a196c4e99aa9');

echo 'Event créé.';

?>