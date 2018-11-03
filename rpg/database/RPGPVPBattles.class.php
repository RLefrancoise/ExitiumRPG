<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	include_once(__DIR__ . "/../classes/AbstractBattle.class.php");
	include_once(__DIR__ . '/../../common.php');
	
	class RPGPVPBattles {
		private static $theInst;

		private function __construct() {
		}
		
		public static function forumIsPVP($forum_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_pvp_forums
					WHERE forum_id = ' . (int) $forum_id;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			
			if($info) return true;
			else return false;
		}
		
		public static function getPVPRequest($token){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pvp_battles_requests 
					WHERE token = \'' . $db->sql_escape($token) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		public static function getPVPRequestByBattleToken($battle_token){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pvp_battles_requests 
					WHERE battle_token = \'' . $db->sql_escape($battle_token) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		public static function getPVPRequestByUserId($user_id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pvp_battles_requests 
					WHERE user1_id = ' . (int) $db->sql_escape($user_id) . '
					OR user2_id = ' . (int) $db->sql_escape($user_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		public static function getBattle($token){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pvp_battles 
					WHERE token = \'' . $db->sql_escape($token) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$b = new PVPBattle($info);
			return $b;
		}
		
		public static function getBattleByPlayerId($player_id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pvp_battles 
					WHERE player1_id = ' . (int) $db->sql_escape($player_id) . '
					OR player2_id = ' . (int) $db->sql_escape($player_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$b = new PVPBattle($info);
			return $b;
		}
		
		public static function getBattleActions($battle_token, $player_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_pvp_battles_actions
					WHERE battle_token = \'' . $db->sql_escape($battle_token) . '\'
					AND player_id = ' . (int) $player_id. '
					ORDER BY turn';
			$result = $db->sql_query($sql);
			
			$actions = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$turn = $info['turn'];
				$actions[$turn] = array( 	'player_id'	=> $info['player_id'],
											'action'	=> $info['action'],
											'skill_name' => $info['skill_name'],
											'skill_slot' => $info['skill_slot'],
											'battle_token' => $info['battle_token'],
										);
			}
			
			return $actions;
		}
		
		public static function getBattleActionsByTurn($battle_token, $player_id, $turn) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_pvp_battles_actions
					WHERE battle_token = \'' . $db->sql_escape($battle_token) . '\'
					AND player_id = ' . (int) $player_id. '
					AND turn = ' . (int) $turn;
			$result = $db->sql_query($sql);
			
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		public static function getBattleTurnResults($token, $turn) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pvp_battles_turn_results 
					WHERE token = \'' . $db->sql_escape($token) . '\'
					AND turn = ' . (int) $turn;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		public static function createPVPRequest($user1_id, $user2_id, $forum_id, $topic_id) {
			global $db;
			
			$token = (string) md5(uniqid());
			
			$insert_array = array(
				'token'		=> $token,
				'user1_id'	=> (int) $user1_id,
				'user2_id'	=> (int) $user2_id,
				'approved'	=> false,
				'forum_id'	=> (int) $forum_id,
				'topic_id'	=> (int) $topic_id,
			);
			
			$sql = 'INSERT INTO rpg_pvp_battles_requests ' . $db->sql_build_array('INSERT', $insert_array);
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);
			
			if(!$insert_success) return false;
			
			return $token;
		}
		
		public static function deletePVPRequest($token) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_pvp_battles_requests
					WHERE token = \'' . (string) $db->sql_escape($token) . '\'';
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function createBattle($player1_id, $player2_id, $player1_hp, $player2_hp, $player1_fp, $player2_fp, $player1_bgm, $player2_bgm) {
			global $db;
			
			$token = md5(uniqid());
			
			$insert_data = array(
				'token'			=> (string) $token,
				'player1_id'		=> (int) $db->sql_escape($player1_id),
				'player2_id'		=> (int) $db->sql_escape($player2_id),
				'player1_hp'		=> (int) $db->sql_escape($player1_hp),
				'player2_hp'		=> (int) $db->sql_escape($player2_hp),
				'player1_fp'		=> (int) $db->sql_escape($player1_fp),
				'player2_fp'		=> (int) $db->sql_escape($player2_fp),
				'turn'				=> 1,
				'player1_bgm'		=> $db->sql_escape($player1_bgm),
				'player2_bgm'		=> $db->sql_escape($player2_bgm),
				'player1_in_battle'	=> false,
				'player2_in_battle'	=> false,
			);
			
			$sql = 'INSERT INTO rpg_pvp_battles ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);
			
			if(!$insert_success) return false;
			
			return $token;
		}
		
		public static function deleteBattle($token) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_pvp_battles
					WHERE token = \'' . (string) $db->sql_escape($token) . '\'';
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function approvePVPRequest($token) {
			global $db;
			
			$update_array = array(
				'approved' => true,
			);
			
			$sql = 'UPDATE rpg_pvp_battles_requests
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setBattleToRequest($request_token, $battle_token) {
			global $db;
			
			$update_array = array(
				'battle_token' => (string) $battle_token,
			);
			
			$sql = 'UPDATE rpg_pvp_battles_requests
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $request_token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setPlayerInBattle($token, $player_nb, $in_battle) {
			global $db;
			
			if($player_nb == 1) {
				$update_array = array(
					'player1_in_battle'	=> (bool) $in_battle,
				);
			}
			else if($player_nb == 2) {
				$update_array = array(
					'player2_in_battle'	=> (bool) $in_battle,
				);
			}
			else return false;
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setBattleStartedFlag($battle_token) {
			global $db;
			
			/*$battle = RPGPVPBattles::getBattle($battle_token);
			if(!$battle) return false;
			
			$p1 = RPGUsersPlayers::getPlayerByUserId($battle->getPlayerId());
			$p2 = RPGUsersPlayers::getPlayerByUserId($battle->getOpponentId());
			
			if(!$p1 or !$p2) return false;*/
			
			$update_array = array(
				'is_started'	=> true,
				/*'player1_hp'	=> $p1->getPV(),
				'player1_fp'	=> $p1->getPF(),
				'player2_hp'	=> $p2->getPV(),
				'player2_fp'	=> $p2->getPF(),*/
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle_token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setBattleOverFlag($battle_token) {
			global $db;
			
			$update_array = array(
				'is_over'	=> true,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle_token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setBattleTurnTime($battle_token, $turn) {
			global $db;
			
			$update_array = array(
				'turn_time'	=> $turn,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle_token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setPlayerLastActiveTurn($battle_token, $player_nb, $turn) {
			global $db;
			
			if($player_nb == 1) {
				if(RPGPVPBattles::getBattle($battle_token)->getPlayer1LastActiveTurn() == $turn) return true;
				
				$update_array = array(
					'player1_last_active'	=> (int) $turn,
				);
			}
			
			else if($player_nb == 2) {
				if(RPGPVPBattles::getBattle($battle_token)->getPlayer2LastActiveTurn() == $turn) return true;
				
				$update_array = array(
					'player2_last_active'	=> (int) $turn,
				);
			}
			else return false;
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle_token . '\'';
					
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setBattleTurnAction($player_id, $battle_token, $turn, $action, $skill_name = false, $skill_slot = false) {
			global $db;
			
			//insert or update ?
			$actions = RPGPVPBattles::getBattleActionsByTurn($battle_token, $player_id, $turn);
			
			$data_array = array(
					'player_id'		=> (int) $player_id,
					'turn'			=> (int) $turn,
					'action'		=> $db->sql_escape($action),
					'battle_token'	=> $battle_token,
				);
				
			if($skill_name) $data_array['skill_name'] = (string) $skill_name;
			if($skill_slot) $data_array['skill_slot'] = (int) $skill_slot;
				
			//insert
			if(!$actions) {
				$sql = 'INSERT INTO rpg_pvp_battles_actions ' . $db->sql_build_array('INSERT', $data_array);
				$db->sql_query($sql);
			}
			//update
			else {
				$sql = 'UPDATE rpg_pvp_battles_actions
						SET ' . $db->sql_build_array('UPDATE', $data_array) . '
						WHERE player_id = ' . (int) $player_id . '
						AND turn = ' . (int) $turn;
					
				$db->sql_query($sql);
			}
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function storeBattleTurnResult($token, $turn, $result) {
			global $db;
			
			$data_array = array(
					'token'			=> (string) $token,
					'turn'			=> (int) $turn,
					'result'		=> $result,
				);
				
			$sql = 'INSERT INTO rpg_pvp_battles_turn_results ' . $db->sql_build_array('INSERT', $data_array);
			$db->sql_query($sql);
				
			/*$sql = 'UPDATE rpg_pvp_battles_turn_results
					SET ' . $db->sql_build_array('UPDATE', $data_array) . '
					WHERE token = \'' . (string) $token . '\'
					AND turn = ' . (int) $turn;
				
			$db->sql_query($sql);*/
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setBattleTurnResultReadFlag($token, $turn, $player_nb) {
			global $db;
			
			if($player_nb == 1) {
				$data_array = array(
					'player1_read' => true,
				);
			}
			else if($player_nb == 2) {
				$data_array = array(
					'player2_read' => true,
				);
			}
			else return false;
			
			$sql = 'UPDATE rpg_pvp_battles_turn_results
					SET ' . $db->sql_build_array('UPDATE', $data_array) . '
					WHERE token = \'' . (string) $token . '\'
					AND turn = ' . (int) $turn;
				
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function incrementTurn(PVPBattle &$battle) {
			global $db;
			
			$update_array = array(
				'turn' => (int) ($battle->getTurn() + 1),
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setTurn($battle->getTurn() + 1);
				
			return $update_success;
		}
		
		
		
		public static function setPlayer1Skills(PVPBattle &$battle, $skills) {
			global $db;
			
			//if($battle->playerSkillsToString() === $skills) return true;
			
			$update_array = array(
				'player1_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1Skills($skills);
				
			return $update_success;
		}
		
		public static function setPlayer2Skills(PVPBattle &$battle, $skills) {
			global $db;
			
			//if($battle->playerSkillsToString() === $skills) return true;
			
			$update_array = array(
				'player2_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2Skills($skills);
				
			return $update_success;
		}
		
		public static function setPlayer1ActiveSkills(PVPBattle &$battle, $skills) {
			global $db;
			
			$update_array = array(
				'player1_active_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1ActiveSkills($skills);
				
			return $update_success;
		}
		
		public static function setPlayer2ActiveSkills(PVPBattle &$battle, $skills) {
			global $db;
			
			$update_array = array(
				'player2_active_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2ActiveSkills($skills);
				
			return $update_success;
		}
		
		public static function resetPlayer1ActiveSkills(PVEBattle &$battle) {
			global $db;
			
			if($battle->player1ActiveSkillsToString() == '') return true;
			
			$update_array = array(
				'player1_active_skills' => '',
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->resetPlayer1ActiveSkills();
				
			return $update_success;
		}
		
		public static function resetPlayer2ActiveSkills(PVEBattle &$battle) {
			global $db;
			
			if($battle->player2ActiveSkillsToString() == '') return true;
			
			$update_array = array(
				'player2_active_skills' => '',
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->resetPlayer2ActiveSkills();
				
			return $update_success;
		}
		
		public static function setPlayer1Buffs(PVPBattle &$battle, $buffs) {
			global $db;
			
			$update_array = array(
				'player1_buffs' => $buffs,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1Buffs($buffs);
				
			return $update_success;
		}
		
		public static function setPlayer2Buffs(PVPBattle &$battle, $buffs) {
			global $db;
			
			$update_array = array(
				'player2_buffs' => $buffs,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2Buffs($buffs);
				
			return $update_success;
		}
		
		public static function setPlayer1ActiveOrbs(PVPBattle &$battle, $orbs) {
			global $db;
			
			if($battle->player1ActiveOrbsToString() == $orbs) return true;
			
			$update_array = array(
				'player1_active_orbs' => $orbs,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1ActiveOrbs($orbs);
				
			return $update_success;
		}
		
		public static function setPlayer2ActiveOrbs(PVPBattle &$battle, $orbs) {
			global $db;
			
			if($battle->player2ActiveOrbsToString() == $orbs) return true;
			
			$update_array = array(
				'player2_active_orbs' => $orbs,
			);
			
			$sql = 'UPDATE rpg_pvp_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2ActiveOrbs($orbs);
				
			return $update_success;
		}
	}
	
?>