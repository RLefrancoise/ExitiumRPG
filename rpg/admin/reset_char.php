<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGPlayers.class.php');
include_once($phpbb_root_path . 'rpg/admin/php/admin_functions.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

// Is user any type of admin? No, then stop here, each script needs to
// check specific permissions but this is a catchall
if (!$auth->acl_get('a_'))
{
	trigger_error('NO_ADMIN');
}

// if post data, reset chosen player
if(!empty($_POST)) {
	
	$capcha = request_var('capcha', '');
	if($capcha != 'DELETE') { echo "Vous n'avez pas tapé DELETE." .PHP_EOL; return; }
		
	$action = request_var('a', '');
	if($action == 'reset_all') {
		
		$sql = 'SELECT DISTINCT player_id, username
				FROM rpg_users_players as up, ' . USERS_TABLE . ' as u
				WHERE up.user_id = u.user_id
				ORDER BY u.username';
		$result = $db->sql_query($sql);

		$all_success = true;
		
		while($info = $db->sql_fetchrow($result)) {
			if(!reset_player($info['player_id'])) {
				echo "[ERROR] Erreur lors du reset du personnage {$info['username']}<br>" . PHP_EOL;
				$all_success = false;
			}
		}

		$db->sql_freeresult($result);
		
		echo "Fin de l'exécution.<br>" . PHP_EOL;
		if(!$all_success) echo "Certains personnages n'ont pas pu être reset." . PHP_EOL;
				
	} else {
		$player_id = request_var('characters', -1);
		if($player_id == -1) { echo 'ID invalide' . PHP_EOL; return; }
		
		if(!reset_player($player_id)) {
			echo "Une erreur est survenue.";
			return;
		}
		
		echo 'Le personnage a été reset.';
	}
	
	return;
}


$t = new CustomTemplate($phpbb_root_path . 'rpg/admin/tpl');

$mode = request_var('mode', '');
if($mode == 'all') {
	$t->set_filenames(array('reset_char' => 'reset_char_all.tpl'));
	
	$t->assign_vars(array(
		'SID'	=> request_var('sid', ''),
		'ROOT'	=> $phpbb_root_path,
	));
	
} else {

	$t->set_filenames(array('reset_char' => 'reset_char.tpl'));


	$t->assign_vars(array(
		'SID'	=> request_var('sid', ''),
		'ROOT'	=> $phpbb_root_path,
	));

	$sql = 'SELECT DISTINCT player_id, username
			FROM rpg_users_players as up, ' . USERS_TABLE . ' as u
			WHERE up.user_id = u.user_id
			ORDER BY u.username';
	$result = $db->sql_query($sql);

	$options = '';

	while($info = $db->sql_fetchrow($result)) {
		$options .= '<option value="' . $info['player_id']. '">' . $info['username'] . '</option>' . PHP_EOL;
	}

	$db->sql_freeresult($result);
	
	$t->assign_vars(array(
		'CHAR_OPTIONS'	=>	$options,
	));

}

$t->pparse('reset_char');

?>