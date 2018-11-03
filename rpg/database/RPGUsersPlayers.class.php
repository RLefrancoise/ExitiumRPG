<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Player.class.php");
	
	class RPGUsersPlayers {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getPlayerByUserId($user_id, $load_mode = PLAYER_ALL){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_players, phpbb_users, rpg_users_players
					WHERE phpbb_users.user_id = ' . (int) $db->sql_escape($user_id) .'
					AND phpbb_users.user_id = rpg_users_players.user_id
					AND rpg_players.id = rpg_users_players.player_id';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$player = new Player($info, $load_mode);
			return $player;
		}
		
		public static function getPlayerByPlayerId($player_id, $load_mode = PLAYER_ALL){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_players, phpbb_users, rpg_users_players
					WHERE rpg_players.id = ' . (int) $db->sql_escape($player_id) .'
					AND phpbb_users.user_id = rpg_users_players.user_id
					AND rpg_players.id = rpg_users_players.player_id';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$player = new Player($info, $load_mode);
			return $player;
		}
		
		public static function associatePlayerToUser($user_id, $player_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_users_players
					WHERE user_id = ' . (int) $db->sql_escape($user_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			// user id not yet exists in table
			if(!$info) {
				$insert_array = array(
					'user_id'		=> (int) $user_id,
					'player_id'	=> (int) $player_id,
				);
				$sql = 'INSERT INTO rpg_users_players ' . $db->sql_build_array('INSERT', $insert_array);
				$db->sql_query($sql);
				
			// user id exists, we update the player_id associated with it
			} else {
				$update_array = array(
					'player_id' => (int) $player_id,
				);
				
				$sql = 'UPDATE rpg_users_players
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE user_id = ' . (int) $user_id;
				$db->sql_query($sql);
			}
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function getPlayerByUserName($name, $load_mode = PLAYER_ALL) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_players, phpbb_users, rpg_users_players
					WHERE phpbb_users.username = \'' . $db->sql_escape($name) .'\'
					AND phpbb_users.user_id = rpg_users_players.user_id
					AND rpg_players.id = rpg_users_players.player_id';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$player = new Player($info, $load_mode);
			return $player;
		}
		
		public static function getUserData($user_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM phpbb_users
					WHERE user_id = ' . (int) $db->sql_escape($user_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		public static function getUserRank($user_id) {
			global $db;
			
			$sql = 'SELECT rank_title
					FROM ' . RANKS_TABLE . ' r, ' . USERS_TABLE . ' u
					WHERE r.rank_id = u.user_rank
					AND u.user_id = ' . (int) $user_id;
			$result = $db->sql_query($sql);
			
			$info = $db->sql_fetchrow($result);
			
			if(!$info) return '';
			
			return $info['rank_title'];
		}
		
		public static function getOnlineUsers() {
			global $db, $config;
			
			$sql = 'SELECT u.user_id, u.username
					FROM ' . USERS_TABLE . ' u, ' . SESSIONS_TABLE . ' s
					WHERE u.user_id = s.session_user_id
					AND s.session_time >= ' . (time() - ($config['load_online_time'] * 60)) . '
					ORDER BY u.username';
			$result = $db->sql_query($sql);
			
			$users = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$id = $info['user_id'];
				$users[$id] = RPGUsersPlayers::getUserData($info['user_id']);
			}
			
			$db->sql_freeresult($result);
			
			return $users;
		}
		
		public static function getLastDayUsers() {
			global $db;
			
			$online_userlist = '';
			
			$sql = 'SELECT username, username_clean, user_id, user_type, user_allow_viewonline, user_colour
					FROM ' . USERS_TABLE . '
					WHERE user_lastvisit >= ' . (time() - 60 * 60 * 24) . '
					AND user_allow_viewonline = 1
					ORDER BY username_clean ASC';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				// User is logged in and therefore not a guest
				if ($row['user_id'] != ANONYMOUS)
				{
					$user_online_link = get_username_string(($row['user_type'] <> USER_IGNORE) ? 'full' : 'no_profile', $row['user_id'], $row['username'], $row['user_colour']);
					$online_userlist .= ($online_userlist != '') ? ', ' . $user_online_link : $user_online_link;
					
				}
			}
			$db->sql_freeresult($result);
			
			if(!$online_userlist) $online_userlist = "Aucun";
			
			return $online_userlist;
		}
		
	}
?>