<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/AbstractBattle.class.php");
	include_once(__DIR__ . '/../../common.php');
	
	class RPGPVEBattles {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getBattle($token){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pve_battles 
					WHERE token = \'' . $db->sql_escape($token) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$b = new PVEBattle($info);
			return $b;
		}
		
		public static function getBattleByPlayerId($player_id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_pve_battles 
					WHERE player_id = ' . (int) $db->sql_escape($player_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$b = new PVEBattle($info);
			return $b;
		}
		
		public static function createBattle($player_id, $monster_id, $monster_hp, $monster_fp, $bgm, $background, $area_part_id) {
			global $db;
			
			$token = md5(uniqid());
			
			$insert_data = array(
				'token'			=> (string) $token,
				'player_id'		=> (int) $db->sql_escape($player_id),
				'monster_id'	=> (int) $db->sql_escape($monster_id),
				'monster_hp'	=> (int) $db->sql_escape($monster_hp),
				'monster_fp'	=> (int) $db->sql_escape($monster_fp),
				'turn'			=> 1,
				'bgm'			=> $db->sql_escape($bgm),
				'background'	=> $db->sql_escape($background),
				'area_part_id'	=> (int) $db->sql_escape($area_part_id),
			);
			
			$sql = 'INSERT INTO rpg_pve_battles ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);
			
			if(!$insert_success) return false;
			
			return $token;
		}
		
		public static function deleteBattle($token) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_pve_battles
					WHERE token = \'' . (string) $db->sql_escape($token) . '\'';
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function incrementTurn(PVEBattle &$battle) {
			global $db;
			
			$update_array = array(
				'turn' => (int) $db->sql_escape($battle->getTurn() + 1),
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setTurn($battle->getTurn() + 1);
				
			return $update_success;
		}
		
		public static function setMonsterHP(PVEBattle &$battle, $monster_hp) {
			global $db;
			
			if($monster_hp < 0) {
				$update_array = array(
					'monster_hp' => 0,
				);
			}
			else {
				$update_array = array(
					'monster_hp' => (int) $db->sql_escape($monster_hp),
				);
			}
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setMonsterHP($monster_hp);
				
			return $update_success;
		}
		
		public static function setMonsterFP(PVEBattle &$battle, $monster_fp) {
			global $db;
			
			if($monster_hp < 0) {
				$update_array = array(
					'monster_fp' => 0,
				);
			}
			else {
				$update_array = array(
					'monster_fp' => (int) $db->sql_escape($monster_fp),
				);
			}
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setMonsterFP($monster_fp);
				
			return $update_success;
		}
		
		public static function setPlayerSkills(PVEBattle &$battle, $skills) {
			global $db;
			
			//if($battle->playerSkillsToString() === $skills) return true;
			
			$update_array = array(
				'player_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1Skills($skills);
				
			return $update_success;
		}
		
		public static function setMonsterSkills(PVEBattle &$battle, $skills) {
			global $db;
			
			//if($battle->monsterSkillsToString() === $skills) return true;
			
			$update_array = array(
				'monster_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2Skills($skills);
				
			return $update_success;
		}
		
		public static function setPlayerActiveSkills(PVEBattle &$battle, $skills) {
			global $db;
			
			$update_array = array(
				'player_active_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1ActiveSkills($skills);
				
			return $update_success;
		}
		
		public static function setMonsterActiveSkills(PVEBattle &$battle, $skills) {
			global $db;
			
			$update_array = array(
				'monster_active_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2ActiveSkills($skills);
				
			return $update_success;
		}
		
		public static function resetPlayerActiveSkills(PVEBattle &$battle) {
			global $db;
			
			if($battle->player1ActiveSkillsToString() == '') return true;
			
			$update_array = array(
				'player_active_skills' => '',
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->resetPlayer1ActiveSkills();
				
			return $update_success;
		}
		
		public static function resetMonsterActiveSkills(PVEBattle &$battle) {
			global $db;
			
			if($battle->player2ActiveSkillsToString() == '') return true;
			
			$update_array = array(
				'monster_active_skills' => '',
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->resetPlayer2ActiveSkills();
				
			return $update_success;
		}
		
		public static function setPlayerBuffs(PVEBattle &$battle, $buffs) {
			global $db;
			
			$update_array = array(
				'player_buffs' => $buffs,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1Buffs($buffs);
				
			return $update_success;
		}
		
		public static function setMonsterBuffs(PVEBattle &$battle, $buffs) {
			global $db;
			
			$update_array = array(
				'monster_buffs' => $buffs,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2Buffs($buffs);
				
			return $update_success;
		}
		
		public static function setPlayerActiveOrbs(PVEBattle &$battle, $orbs) {
			global $db;
			
			//if($battle->player1ActiveOrbsToString() == $orbs) return true;
			
			$update_array = array(
				'player_active_orbs' => $orbs,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1ActiveOrbs($orbs);
				
			return $update_success;
		}
		
		public static function setMonsterActiveOrbs(PVEBattle &$battle, $orbs) {
			global $db;
			
			//if($battle->player2ActiveOrbsToString() == $orbs) return true;
			
			$update_array = array(
				'monster_active_orbs' => $orbs,
			);
			
			$sql = 'UPDATE rpg_pve_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2ActiveOrbs($orbs);
				
			return $update_success;
		}
		
		
		
		public static function storeTurnData($data) {
			global $db;
			
			if(!array_key_exists('player_id', $data)) return false;
			
			$sql = 'INSERT INTO rpg_pve_battles_actions ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);
			
			if(!$insert_success) return false;
			else return true;
		}
	}
	
?>