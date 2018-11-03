<?php
 
//header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGPlayers.class.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.' . $phpEx);
include_once('./rpg/php/status_functions.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL | PLAYER_EQUIPMENT | PLAYER_ORBS | PLAYER_MAP);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

$mode = request_var('mode', '');
if($mode == '')
{
	echo 'error';
	return;
}

switch($mode) {
	case 'self_spawn':
	{
		$spawn = array();
		$map_charset = $player->getMapCharset();
		$map_name = $player->getMapName();
		$map_position = $player->getMapPosition();
		
		//if no map charset for player, give him default charset
		if($map_charset === false) {
			$map_charset = 'default.png';
			
			$sql = 'UPDATE rpg_players
					SET map_charset = ' . "'$map_charset'" . '
					WHERE id = ' . $player->getId();
			$db->sql_query($sql);
		}
		
		//if player is nowhere, spawn him in map
		if($map_name === false) {
			$map_name = 'map1';
			$map_position = array();
			$map_position['x'] = 0;
			$map_position['y'] = 0;
			
			$sql = 'UPDATE rpg_players
					SET map_name = ' . "'$map_name'". ', map_position = ' . "'{$map_position['x']},{$map_position['y']}'" . '
					WHERE id = ' . $player->getId();
			$db->sql_query($sql);
		}
		
		$spawn['charset'] = $map_charset;
		$spawn['map'] = $map_name;
		$spawn['position'] = $map_position;
		$spawn['accountData'] = array(
			'id'		=>	$user->data['user_id'],
			'name'		=>	$user->data['username'],
			'admin'		=>	(!$auth->acl_get('a_') ? false : true),
			'hp'		=>	$player->getPV(),
			'max_hp'	=>	$player->getMaxPV(),
			'fp'		=>	$player->getPF(),
			'max_fp'	=>	$player->getMaxPF(),
		);
		
		echo json_encode($spawn);
		return;		
	}
	
	case 'update':
	{
		//save position of player
		$x = request_var('x', -1);
		$y = request_var('y', -1);
		
		if($x == -1 or $y == -1) {
			echo 'error';
			return;
		}
		
		//if position is same than previous, no update
		$pos = $player->getMapPosition();
		if($pos['x'] != $x or $pos['y'] != $y) {
			$sql = 'UPDATE rpg_players
					SET map_position = ' . "'$x,$y'" . '
					WHERE id = ' . $player->getId();
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if(!$update_success) {
				echo 'error';
				return;
			}
		}
		
		
		//give to player characters in map (except himself)
		$sql = 'SELECT DISTINCT *
				FROM rpg_players AS p, ' . USERS_TABLE . ' AS u, rpg_users_players AS up
				WHERE p.map_name = \'' . $player->getMapName() . '\'
				AND p.id != ' . $player->getId() . '
				AND u.user_id = up.user_id
				AND p.id = up.player_id';
		$result = $db->sql_query($sql);
		
		$characters = array();
		
		while($info = $db->sql_fetchrow($result)) {
			$data = array();
			
			$c = RPGUsersPlayers::getPlayerByUserId($info['user_id'], PLAYER_GENERAL | PLAYER_EQUIPMENT | PLAYER_ORBS);
			
			//id
			$data['id'] = $info['user_id'];
			
			//name
			$data['name'] = $info['username'];
			
			//admin
			$auth2 = new auth();
			$sql = 'SELECT *
					FROM ' . USERS_TABLE . '
					WHERE user_id = ' . $info['user_id'];
			$result2 = $db->sql_query($sql);
			$user_row = $db->sql_fetchrow($result2);
			$auth2->acl($user_row);
			$db->sql_freeresult($result2);
			$data['admin'] = ($auth2->acl_get('a_') ? true : false);
			
			//hp, max_hp, fp, max_fp
			$data['hp'] = $c->getPV();
			$data['max_hp'] = $c->getMaxPV();
			$data['fp'] = $c->getPF();
			$data['max_fp'] = $c->getMaxPF();
			
			//charset
			$data['charset'] = $info['map_charset'];
			
			//map
			$data['map'] = $info['map_name'];
			$_ = explode(",", $info['map_position']);
			
			//position
			$data['x'] = $_[0];
			$data['y'] = $_[1];
			
			$characters[] = $data;
		}
		
		$db->sql_freeresult($result);
		
		$ret = array();
		$ret['characters'] = $characters;
		
		//print_r($ret);
		
		echo json_encode($ret);
		return;
	}
	
	default:
		echo 'error';
		return;
}

