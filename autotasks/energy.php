<?php

define('IN_PHPBB', true);
$phpbb_root_path = '../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.php');
include_once($phpbb_root_path . 'rpg/php/post_functions.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup();

$auth->login(RPG_ACCOUNT_LOGIN, RPG_ACCOUNT_PASSWORD);

//restore energy of every users
$sql = 'SELECT *
		FROM ' . USERS_TABLE . ' u, rpg_users_players p
		WHERE u.user_id = p.user_id';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$player = RPGUsersPlayers::getPlayerByUserId($info['user_id']);
	
	$energy = $player->getEnergy();
	if($energy >= MAX_ENERGY) continue;
	
	if(!RPGPlayers::setEnergyOfPlayer($player, MAX_ENERGY)) {
		echo "[Error] Ligne " . __LINE__ . " : setEnergyOfPlayer with player id = {$player->getId()} and energy = $energy" . PHP_EOL;
	} else {
		$to = array('u' => array( $info['user_id'] => 'to'));
		rpg_pm("Réinitialisation de l'enérgie", "Vous avez récupéré vos points d'énergie.", $to);
	}
}

$user->session_kill();
$user->session_begin();

echo "End of script";

?>