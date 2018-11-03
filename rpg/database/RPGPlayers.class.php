<?php
	include_once(__DIR__ . '/../../common.php');
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Player.class.php");
	include_once(__DIR__ . "/RPGRalz.class.php");
	include_once(__DIR__ . "/../classes/RPGConfig.class.php");
	
	class RPGPlayers {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getPlayerInfo($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_players
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return $info;
		}
		
		/*
		* Get the next free orb slot.
		*
		* - player : the Player object
		* Returns the first available free orb slot, or false if no slot is available. 
		*/
		public static function getNextFreeOrbSlot(Player &$player) {
			//$orbs = $player->getOrbs();
			
			$i = 1;
			$orb = null;
			while($i <= 4 and ($orb = $player->getOrb($i)) !== null) {
				//$i++;
				$i += $orb->getSize();
			}
			
			if($i > 4) return false;
			else return $i;
		}
		
		
		/*
		* Create a new player.
		*
		* user_id : the user_id to be associated with the player
		* gender : the gender of the player (M or F)
		* organisation : the organisation of the player (EMPIRE, REVO, ECLYPSE, CONSEIL, CITOYEN)
		*
		* Returns the id of the created player if successful, false otherwise.
		*/
		public static function createPlayer($user_id, $gender, $organisation, $weaponname, $clothname, $leggingsname, $glovesname, $shoesname) {
			global $db;
			
			if((strtoupper($gender) !== 'M') and (strtoupper($gender) !== 'F')) return false;
			if((strtoupper($organisation) !== 'EMPIRE') and (strtoupper($organisation) !== 'REVO') and (strtoupper($organisation) !== 'ECLYPSE') and (strtoupper($organisation) !== 'CONSEIL') and (strtoupper($organisation) !== 'CITOYEN')) return false;
			if($weaponname == '') return false;
			
			if(strtoupper($organisation) === 'EMPIRE')
				$orga_id = 1;
			else if(strtoupper($organisation) === 'REVO')
				$orga_id = 2;
			else if(strtoupper($organisation) === 'ECLYPSE')
				$orga_id = 3;
			else if(strtoupper($organisation) === 'CONSEIL')
				$orga_id = 4;
			else if(strtoupper($organisation) === 'CITOYEN')
				$orga_id = 5;
				
			$db->sql_transaction('begin');
			
			$insert_data = array(
				'gender'		=> $db->sql_escape(strtoupper($gender)),
				'level'			=> DEFAULT_LEVEL,
				'leader'		=> false,
				'pv'			=> MIN_PV,
				'pf'			=> MIN_PF,
				'xp'			=> DEFAULT_XP,
				'karma'			=> DEFAULT_KARMA,
				'energy'		=> MAX_ENERGY,
				'atk_points'	=> 0,
				'def_points'	=> 0,
				'spd_points'	=> 0,
				'flux_points'	=> 0,
				'res_points'	=> 0,
				'ralz'			=> DEFAULT_RALZ,
				'orb1'			=> NULL,
				'orb2'			=> NULL,
				'orb3'			=> NULL,
				'orb4'			=> NULL,
				'organisation_id' => (int) $db->sql_escape($orga_id),
				'clan_id'		=> NULL,
				'cloth_name'	=> $clothname,
				'leggings_name'	=> $leggingsname,
				'gloves_name'	=> $glovesname,
				'shoes_name'	=> $shoesname,
			);	
			
			$sql = 'INSERT INTO rpg_players ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);

			
			$pid = $db->sql_nextid();
			
			$associate_success = RPGUsersPlayers::associatePlayerToUser($user_id, $pid);
			
			// set weapon name of player
			$player = RPGUsersPlayers::getPlayerByUserId($user_id);
			$weapon_success = RPGWeapons::setWeaponByPlayer($player, $weaponname, WEAPON_GRADE_D);
			
			// give player a ralz item to manage ralz
			$give_success = RPGPlayers::giveItemToPlayer($player, RPGRalz::getRalzByPlayer($player->getId()));
			
			//give default items if any
			if(!RPGPlayers::giveDefaultItems($player)) return false;
			
			$db->sql_transaction('commit');
			
			if($insert_success and $associate_success and $weapon_success and $give_success)
				return $pid;
			else
				return false;
		}
		
		public static function deletePlayer($player_id) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_players
					WHERE id = ' . (int) $db->sql_escape($player_id);
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		// SETTERS
		public static function setSoundOptionByPlayer(Player &$player, $state) {
			global $db;
			
			$update_array = array(
				'enable_sound' => (bool) $db->sql_escape($state),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			$player->setSoundEnabled($state);
				
			return $update_success;
		}
		
		public static function setAnimationsOptionByPlayer(Player &$player, $state) {
			global $db;
			
			$update_array = array(
				'enable_animations' => (bool) $db->sql_escape($state),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			$player->setAnimationsEnabled($state);
				
			return $update_success;
		}
		
		public static function setAlphaOptionByPlayer(Player &$player, $state) {
			global $db;
			
			$update_array = array(
				'enable_alpha' => (bool) $db->sql_escape($state),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			$player->setAlphaEnabled($state);
				
			return $update_success;
		}
		
		public static function setHDOptionByPlayer(Player &$player, $state) {
			global $db;
			
			$update_array = array(
				'enable_hd' => (bool) $db->sql_escape($state),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			$player->setHDEnabled($state);
				
			return $update_success;
		}
		
		public static function setIntroductionLinkByPlayer(Player &$player, $link) {
			global $db;
			
			$update_array = array(
				'introduction_link' => (string) $link,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			$player->setIntroductionLink((string) $link);
				
			return $update_success;
		}
		
		public static function setPointsByPlayerAndStat(Player &$player, $stat, $points) {
			global $db;
			
			if($player->getPointsOfStat($stat) == $points) return true;
			
			$old_points = $player->getPointsOfStat($stat);
			$player->setPointsOfStat($stat, $points);
			
			if( ($player->getBaseAtk() > STAT_MAX_CAPACITY)
				|| ($player->getBaseDef() > STAT_MAX_CAPACITY)
				|| ($player->getBaseSpd() > STAT_MAX_CAPACITY)
				|| ($player->getBaseFlux() > STAT_MAX_CAPACITY)
				|| ($player->getBaseRes() > STAT_MAX_CAPACITY)
			){
				$player->setPointsOfStat($stat, $old_points);
				RPGPlayers::setPointsByPlayerAndStat($player, $stat, $points - 1);
			}
			else {
				$player->updatePoints();
				
				$field_name = '';
				if($stat === STAT_ATTACK) $field_name = 'atk_points';
				else if($stat === STAT_DEFENSE) $field_name = 'def_points';
				else if($stat === STAT_SPEED) $field_name = 'spd_points';
				else if($stat === STAT_FLUX) $field_name = 'flux_points';
				else if($stat === STAT_RESISTANCE) $field_name = 'res_points';
				else return false;
				
				$update_array = array(
					$field_name => (int) $db->sql_escape($points),
				);
				
				$sql = 'UPDATE rpg_players
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE id = ' . (int) $db->sql_escape($player->getId());
				$db->sql_query($sql);
				$update_success = ($db->sql_affectedrows() > 0);
				
				//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
				$player->updateStatsPointsFromBDD();
				
				return $update_success;
			}
		}
		
		public static function setSkillByPlayer(Player &$player, $skill_nb, $skill_type) {
			global $db;
			
			if($skill_nb < 1 or $skill_nb > 4) return false;
			
			$field_name = 'skill_'.$skill_nb;
			
			$update_array = array(
				$field_name => (string) $db->sql_escape($skill_type),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->updateSkillsFromBDD();
			
			return $update_success;
		}
		
		public static function setSubSkillByPlayer(Player &$player, $skill_nb, $skill_type) {
			global $db;
			
			if($skill_nb < 1 or $skill_nb > 4) return false;
			
			$field_name = 'skill_'.$skill_nb.'_subskill';
			
			$update_array = array(
				$field_name => (string) $db->sql_escape($skill_type),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->updateSkillsFromBDD();
			
			return $update_success;
		}
		
		public static function removeSkillByPlayer(Player &$player, $skill_nb) {
			global $db;
			
			if($skill_nb < 1 or $skill_nb > 4) return false;
			
			if($player->getSkill($skill_nb) == null) return true;
			
			$field_name = 'skill_'.$skill_nb;
			$skill_name = $field_name.'_name';
			$skill_element = $field_name.'_element';
			$skill_sub = $field_name.'_subskill';
			
			$sql = "UPDATE rpg_players
					SET $field_name = NULL, $skill_name = NULL, $skill_element = 'none', $skill_sub = NULL
					WHERE id = " . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->updateSkillsFromBDD();
			
			return $update_success;
		}
		
		public static function setSkillNameByPlayer(Player &$player, $name, $skill_nb) {
			global $db;
			
			if($skill_nb < 1 or $skill_nb > 4) return false;
			
			$field_name = 'skill_'.$skill_nb.'_name';
			
			$update_array = array(
				$field_name => (string) $name,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->setSkillName($skill_nb, $name);
			
			return $update_success;
		}
		
		public static function setSkillElementByPlayer(Player &$player, $slot, $element) {
			global $db, $_ELEMENTS_STRINGS;
			
			if(!array_key_exists($element, $_ELEMENTS_STRINGS)) return false;
			if($slot < 1 or $slot > 4) return false;
			
			$field_name = 'skill_'.$slot.'_element';
			
			$update_array = array(
				$field_name => (string) $element,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->updateSkillsFromBDD();
			
			return $update_success;
		}
		
		public static function setPVOfPlayer(&$player, $life) {
			global $db;
			
			$update_array = array(
				'pv'	=> $life,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			if($update_success) $player->setPV($life);
			
			return $update_success;
		}
		
		public static function setPFOfPlayer(&$player, $flux) {
			global $db;
			
			$update_array = array(
				'pf'	=> $flux,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			if($update_success) $player->setPF($flux);
			
			return $update_success;
		}
		
		public static function setLevelOfPlayer(Player &$player, $level) {
			global $db;
			
			$update_array = array(
				'level'	=> (int) $db->sql_escape($level),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			if($update_success) $player->setLevel($level);
			
			return $update_success;
		}
		
		public static function setXPOfPlayer(Player &$player, $xp) {
			global $db;
			
			$update_array = array(
				'xp'	=> (int) $db->sql_escape($xp),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			if($update_success) $player->setXP($xp);
			
			return $update_success;
		}
		
		public static function setKarmaOfPlayer(Player &$player, $karma) {
			global $db;
			
			if($karma < 0 or $karma > MAX_KARMA) return true;
			
			$update_array = array(
				'karma'	=> (int) $db->sql_escape($karma),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			if($update_success) $player->setKarma($karma);
			
			return $update_success;
		}
		
		public static function setEnergyOfPlayer(Player &$player, $energy) {
			global $db;
			
			//if($energy == $player->getEnergy()) return true;
			//if( ($energy < 0) or ($energy > ( MAX_ENERGY + $player->getMaxEnergyBonus() )) ) return true;
			
			$update_array = array(
				'energy'	=> (int) $energy,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $player->setEnergy($energy);
			
			return $update_success;
		}
		
		public static function setMaxEnergyBonusOfPlayer(Player &$player, $bonus) {
			global $db;
			
			if($bonus < 0) return false;
			
			$update_array = array(
				'max_energy_bonus'	=> (int) $bonus,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $player->setMaxEnergyBonus($bonus);
			
			return $update_success;
		}
		
		public static function setIncEnergyBonusOfPlayer(Player &$player, $bonus) {
			global $db;
			
			if($bonus < 0) return false;
			
			$update_array = array(
				'inc_energy_bonus'	=> (int) $bonus,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $player->setIncEnergyBonus($bonus);
			
			return $update_success;
		}
		
		public static function setTotalBattlesOfPlayer(Player &$player, $total) {
			global $db;
			
			if($total < 0) return true;
			
			$update_array = array(
				'total_battles'	=> (int) $total,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $player->setTotalBattles($total);
			
			return $update_success;
		}
		
		public static function setHonorOfPlayer(Player &$player, $honor) {
			global $db;
			
			if($honor < 0) return true;
			
			$update_array = array(
				'honor'	=> (int) $honor,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $player->setHonor($honor);
			
			return $update_success;
		}
		
		public static function setClanIdByPlayer(&$player, $id) {
			global $db;
			
			/*$newid = 'NULL';
			if($id !== null) { $newid = $id; }*/
			
			$update_array = array(
				'clan_id'	=> $id,
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $db->sql_escape($player->getId());
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			
			return $update_success;
		}
		
		/*
		* Set Ralz of player.
		* - player : the player object
		* - ralz : the new ralz number
		*/
		public static function setRalzByPlayer(Player &$player, $ralz) {
			global $db;
			
			if(intval($ralz) < 0) $ralz = 0;
			
			$update_array = array(
				'ralz'	=> (int) $db->sql_escape($ralz),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $player->getId();
			$db->sql_query($sql);
				
			$success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->setRalz($ralz);
			
			return $success;
		}
		
		/*
		* Set gender of player.
		* - player : the player object
		* - gender : the new gender of player (M or F)
		*/
		public static function setGenderByPlayer(Player &$player, $gender) {
			if($gender !== 'M' and $gender !== 'F') return false;
			
			global $db;
			
			$update_array = array(
				'gender'	=> $db->sql_escape($gender),
			);
			
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $player->getId();
			$db->sql_query($sql);
				
			$success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->setGender($gender);
			
			return $success;
		}
		
		/*
		* Set armor part name of a player.
		* - player : the player object
		* - type : ARMOR_CLOTH, ARMOR_LEGGINGS, ARMOR_GLOVES, ARMOR_SHOES
		* - name : The new name of the part
		*/
		public static function setArmorPartNameByPlayerAndType(Player &$player, $name, $type) {
			global $db;
			
			if($type === ARMOR_CLOTH) {
				$update_array = array(
					'cloth_name'	=> $name,
				);
			}
			else if($type === ARMOR_LEGGINGS) {
				$update_array = array(
					'leggings_name'	=> $name,
				);
			}
			else if($type == ARMOR_GLOVES) {
				$update_array = array(
					'gloves_name'	=> $name,
				);
			}
			else if($type === ARMOR_SHOES) {
				$update_array = array(
					'shoes_name'	=> $name,
				);
			}
			else return false;
				
			$sql = 'UPDATE rpg_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE id = ' . (int) $player->getId();
			$db->sql_query($sql);
				
			$success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->setEquipmentName($type, $name);
			
			return $success;
		}
		
		// ORB
		
		/*
		* Set an orb to the specified slot
		*
		* - player : the player object
		* - slot : the slot of the orb (between 1 and 4)
		* - orb_id : the ID of the orb in the DB
		*/
		public static function setOrbByPlayer(Player &$player, $slot, $orb_id) {
			global $db;
			
			if(intval($slot) < 1 or intval($slot) > 4) return false;
			
			// orb_id is valid ?
			$orb = RPGOrbs::getOrb($orb_id);
			if($orb === null) return false;
			
			$orb_field = 'orb' . intval($slot);
			
			$sql = 'UPDATE rpg_players
					SET ' . $orb_field . ' = ' . (int) $db->sql_escape($orb_id) . '
					WHERE id = ' . (int) $player->getId();
			$db->sql_query($sql);
				
			$success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->setOrb($slot, $orb_id);
			
			return $success;
		}
		
		public static function removeOrbByPlayer(Player &$player, $slot) {
			global $db;
			
			if(intval($slot) < 1 or intval($slot) > 4) return false;
			
			$orb_field = 'orb' . intval($slot);
			
			$sql = 'UPDATE rpg_players
					SET ' . $orb_field . ' = NULL
					WHERE id = ' . (int) $player->getId();
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			$player->removeOrb($slot);
			
			return $success;
		}
		
		// INVENTORY
		public static function giveDefaultItems(Player &$player) {
			$starting_items = RPGConfig::$_STARTING_ITEMS;
			
			if(count($starting_items) > 0) {
				foreach($starting_items as $index => $item_data) {
					switch($item_data['type']) {
						case 'syringe':
							{
								$item = RPGSyringes::getSyringe($item_data['id']);
								for($i = 0 ; $i < $item_data['number'] ; $i++) {
									if(!RPGPlayers::giveItemToPlayer($player, $item)) return false;
								}
							}
							break;
					}
				}
			}
			
			return true;
		}
		
		/*
		* Give an item to the player
		*/
		public static function giveItemToPlayer(Player &$player, Item $i, $quantity = 1) {
			global $db;
			
			
			
			//look for the type of item (set part, orb, syringe)
			$item_type = $player->getInventory()->getTypeOfItem($i);
			if($item_type == '') return false;
			
			$slot = -1;
			$number = -1;
			$req_type = '';
			
			// this item is limited to one per slot, so we add it in the first free slot
			if($i->isOnePerSlot()) {
				if($player->getInventory()->isFull()) return false;
				
				$slot = $player->getInventory()->getNextFreeSlot();
				$number = 1;
				$req_type = 'insert';
			}
			
			// this item is allowed to be multiple times in the same slot, so we check if at least one examplary exists
			else {
				$item_found = false;
				for($j = 0 ; !$item_found && $j < INVENTORY_SIZE ; $j++) {
					$item2 = $player->getInventory()->getItem($j);
					if($item2 == null) continue;
					if( ($i->getId() == $item2->getId()) and ($item_type == RPGInventories::getTypeOfItemByPlayerAndSlot($player->getId(), $j+1)) ){
						$item_found = true;
					}
				}
				// if an examplary is found, we just add one to its quantity
				if($item_found) {
					$slot = $j;
					//$number = RPGInventories::getQuantityOfItemByPlayer($player->getId(), $slot) + 1;
					$number = RPGInventories::getQuantityOfItemByPlayer($player->getId(), $slot) + $quantity;
					$req_type = 'update';
				}
				// else we add it in the next available slot
				else {
					if($player->getInventory()->isFull()) return false;
					
					$slot = $player->getInventory()->getNextFreeSlot();
					//$number = 1;
					$number = $quantity;
					$req_type = 'insert';
				}
			}
			
			if($slot == -1 or $number == -1 or $req_type == '') { return false; }

			if(strcmp($req_type, 'insert') == 0) {
				$insert_array = array(
					'player_id'	=> (int) $player->getId(),
					'slot'		=> (int) $slot,
					'item_id'	=> (int) $i->getId(),
					'item_type'	=> $item_type,
					'number'	=> (int) $number,
				);
				$sql = 'INSERT INTO rpg_inventories ' . $db->sql_build_array('INSERT', $insert_array);
				$db->sql_query($sql);

				$request_success = ($db->sql_affectedrows() > 0);
			}
			else {
				$update_array = array(
					'number' => (int) $number,
				);
				
				$sql = 'UPDATE rpg_inventories
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE player_id = ' . (int) $player->getId() . '
						AND slot = ' . (int) $slot;
				$db->sql_query($sql);
				
				$request_success = ($db->sql_affectedrows() > 0);
			}
			
			//$player 	= RPGUsersPlayers::getPlayerByUserId($player->getUserId());
			//$player->giveItem($i);
			//$player->setItem($i, $number, $slot);
			$player->updateInventory();
			
			return $request_success;
		}
	}
?>