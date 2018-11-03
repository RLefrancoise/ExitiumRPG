<?php

header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGEventBattles.class.php');
// Start session management

$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

$event_token = request_var('t', '');
if($event_token == '') { echo 'error'; return; }

$event_data = RPGEventBattles::getEventGeneralData($event_token);

$db->sql_transaction('begin');

if(!RPGEventBattles::manageEventEnding($event_token, $event_data['forum_id'], $event_data['topic_id']) or !RPGEventBattles::deleteEvent($event_token)) {
	echo 'error';
	return;
}
else {
	$db->sql_transaction('commit');
	echo 'manage_ok';
	return;
}

/*
//damage given ranking
$dg_ranking = RPGEventBattles::getDamageGivenRanking($battle->getToken());
if(!$dg_ranking) { // no ranking
	return;
}

//damage received ranking
$dr_ranking = RPGEventBattles::getDamageReceivedRanking($battle->getToken());
if(!$dr_ranking) { // no ranking
	return;
}

$dg_text = 'Classement en fonction des dégats infligés :' . PHP_EOL;
foreach($dg_ranking as $rank => $data) {
	$dg_text .= ("Rang $rank : {$data['username']} avec {$data['total_damage_given']}" . PHP_EOL);
}

$dr_text = 'Classement en fonction des dégats subits :' . PHP_EOL;
foreach($dr_ranking as $rank => $data) {
	$dr_text .= ("Rang $rank : {$data['username']} avec {$data['total_damage_received']}" . PHP_EOL);
}

//give event items
$items_text = RPGEventBattles::giveEventItems($battle->getToken());
if($items_text === false) return;

$text = 'Le world boss a été vaincu !' . PHP_EOL . PHP_EOL . $dg_text . PHP_EOL . $dr_text . PHP_EOL . $items_text;

rpg_post("Fin de l'event", $text, 'reply', $battle->getForumId(), $battle->getTopicId());*/

?>