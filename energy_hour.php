<?php

define('IN_PHPBB', true);
$phpbb_root_path = './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.php');
include_once($phpbb_root_path . 'rpg/php/post_functions.php');

//$user->session_begin();
//$auth->acl($user->data);
//$user->setup();

//$auth->login(RPG_ACCOUNT_LOGIN, RPG_ACCOUNT_PASSWORD);

//give +2 energy of every users
$sql = 'SELECT up.user_id
		FROM ' . USERS_TABLE . ' u, rpg_users_players up, rpg_players p
		WHERE u.user_id = up.user_id
		AND up.player_id = p.id';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	
	
	$player = RPGUsersPlayers::getPlayerByUserId($info['user_id']);
	
	$energy = $player->getEnergy();
	$max_energy = (MAX_ENERGY + $player->getMaxEnergyBonus());
	if($energy >= $max_energy) continue;
	$new_energy = $energy + 2 + $player->getIncEnergyBonus();
	if($new_energy > $max_energy) $new_energy = $max_energy;
	
	if(!RPGPlayers::setEnergyOfPlayer($player, $new_energy)) {
		echo "[Error] Ligne " . __LINE__ . " : setEnergyOfPlayer with player id = {$player->getId()} and energy = $new_energy" . PHP_EOL;
	} /*else {
		$to = array('u' => array( $info['user_id'] => 'to'));
		rpg_pm("Gain d'enérgie", "Vous avez récupéré 2 points d'énergie.", $to);
	}*/
}

$db->sql_freeresult($result);

//$user->session_kill();
//$user->session_begin();

echo "End of script";

?>