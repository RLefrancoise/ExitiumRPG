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

/*$SALARIES = array(
	//1 Site Admin, nothing
	2 	=> 3000, // Général de l'ouest
	3	=> 3000, // Général de l'est
	4	=> 3000, // Général du Nord
	5	=> 3000, // Général du Sud
	6	=> 4000, // 3e Empereur
	7	=> 2000, // Soldat Impérial
	8	=> 2000, // Esclave
	9	=> 3500, // Intendant
	10	=> 4000, // Dirigeante
	11	=> 3000, // Leader de l'ouest
	12	=> 3000, // Leader de l'est
	13	=> 3000, // Leader du Nord
	14	=> 3000, // Leader du Sud
	15	=> 2000, // Rebelles
	16	=> 2000, // Libertin
	17	=> 3000, // Conseiller
	18	=> 4000, // Suprême
	19	=> 2000, // Traqueur
	20	=> 2000, // Soldat Exitian
	21	=> 3000, // Haut Traqueur
	22	=> 4000, // Chef Eclypse
	23	=> 3000, // Leader Eclypse
	24	=> 2000, // Membre Eclypse
	25	=> 2000, // Citoyen
);*/





//give salary to each player
$sql = 'SELECT *
		FROM ' . USERS_TABLE . ' u, rpg_users_players p
		WHERE u.user_id = p.user_id';
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	
	$data = RPGUsersPlayers::getUserData($info['user_id']);
	$rank_id = (int) $data['user_rank'];
	if(!array_key_exists($rank_id, $SALARIES)) continue;
	
	$player = RPGUsersPlayers::getPlayerByUserId($info['user_id']);
	
	//$salary = $SALARIES[$rank_id];
	$salary = $player->getSalary();
	
	if($salary == 0) continue;
	
	if(!player_give_ralz($player, $salary)) {
		echo "[Error] Ligne " . __LINE__ . " : player_give_ralz with player id = {$player->getId()} and salary = $salary" . PHP_EOL;
	} else {
		$to = array('u' => array( $info['user_id'] => 'to'));
		rpg_pm("Salaire", "Vous avez reçu votre salaire mensuel de $salary Ralz.", $to);
	}
}

//$user->session_kill();
//$user->session_begin();

echo "End of script";

?>