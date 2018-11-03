<?php

define('IN_PHPBB', true);
$phpbb_root_path = '../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGWarehouses.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.php');
include_once($phpbb_root_path . 'rpg/php/post_functions.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup();

//$auth->login(RPG_ACCOUNT_LOGIN, RPG_ACCOUNT_PASSWORD);


//for every users
$sql = 'SELECT *
		FROM ' . USERS_TABLE . ' u, rpg_users_players p
		WHERE u.user_id = p.user_id';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$player = RPGUsersPlayers::getPlayerByUserId($info['user_id']);
	
	$in_bank = RPGWarehouses::getRalzOfPlayer($player);
	if($in_bank == 0) continue;
	
	$benefit = (int) floor($in_bank * CALL_RATE);
	if($benefit == 0) continue;
	
	if(!RPGWarehouses::storeRalzOfPlayer($player, $benefit)) {
		echo "[ERROR] storeRalzOfPlayer failed for player with id = {$player->getId()}" . PHP_EOL; 
	} /*else {
		$to = array('u' => array( $info['user_id'] => 'to'));
		rpg_pm("Intérêts", "Votre placement en banque vous a rapporté $benefit Ralz.", $to);
	}*/
}

echo "End of script";

?>