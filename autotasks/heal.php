<?php

define('IN_PHPBB', true);
$phpbb_root_path = '../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.php');
include_once($phpbb_root_path . 'rpg/php/post_functions.php');
include_once($phpbb_root_path . 'rpg/php/player_functions.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup();

//$auth->login(RPG_ACCOUNT_LOGIN, RPG_ACCOUNT_PASSWORD);

//heal each player
$sql = 'SELECT *
		FROM ' . USERS_TABLE . ' u, rpg_users_players p
		WHERE u.user_id = p.user_id';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	
	$player = RPGUsersPlayers::getPlayerByUserId($info['user_id']);
	
	if( ($player->getPV() == $player->getMaxPV()) and ($player->getPF() == $player->getMaxPF()) ) continue;
	
	$db->sql_transaction('begin');
	
	$heal_pv = player_heal_pv($player, $player->getMaxPV());
	$heal_pf = player_heal_pf($player, $player->getMaxPF());
	
	if(!$heal_pv) {
		echo "[Error] Ligne " . __LINE__ . " : player_heal_pv with player id = {$player->getId()}" . PHP_EOL;
	} else if(!$heal_pf) {
		echo "[Error] Ligne " . __LINE__ . " : player_heal_pf with player id = {$player->getId()}" . PHP_EOL;
	} else {
		$db->sql_transaction('commit');
		$to = array('u' => array( $info['user_id'] => 'to'));
		rpg_pm("Soins", "Vous avez récupéré vos PV et PF.", $to);
	}
}

//$user->session_kill();
//$user->session_begin();

echo "End of script";

?>