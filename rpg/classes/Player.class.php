<?php
	include_once(__DIR__ . "/Creature.class.php");
	include_once(__DIR__ . "/Inventory.class.php");
	include_once(__DIR__ . "/Skill.class.php");
	include_once(__DIR__ . "/RPGConfig.class.php");
	include_once(__DIR__ . "/../database/RPGOrbs.class.php");
	include_once(__DIR__ . "/../database/RPGArmorParts.class.php");
	include_once(__DIR__ . "/../database/RPGWeapons.class.php");
	include_once(__DIR__ . '/../database/RPGOrganisations.class.php');
	include_once(__DIR__ . '/../database/RPGClans.class.php');
	include_once(__DIR__ . '/../database/RPGInventories.class.php');
	include_once(__DIR__ . '/../database/RPGSets.class.php');
	include_once(__DIR__ . '/../database/RPGPVEBattles.class.php');
	include_once(__DIR__ . '/../database/RPGPVPBattles.class.php');
	include_once(__DIR__ . '/../database/RPGEventBattles.class.php');
	include_once(__DIR__ . "/../database/RPGUsersPlayers.class.php");
	include_once(__DIR__ . '/../database/RPGPlayersStats.class.php');
	include_once(__DIR__ . '/../database/RPGWarehouses.class.php');
	include_once(__DIR__ . '/../database/RPGQuests.class.php');
	
	//load mode flags
	define("PLAYER_ALL"			, 1);
	define("PLAYER_GENERAL"		, 1 << 1);
	define("PLAYER_INVENTORY"	, 1 << 2);
	define("PLAYER_WAREHOUSE"	, 1 << 3);
	define("PLAYER_SKILLS"		, 1 << 4);
	define("PLAYER_EQUIPMENT"	, 1 << 5);
	define("PLAYER_ORBS"		, 1 << 6);
	define("PLAYER_MAP"			, 1 << 7);
	
	class Player extends Creature {
	
		//general info
		private $uid;
		private $avatar;
		private $gender;
		private $orga;
		private $is_leader;
		private $bgm;
		private $energy;
		private $max_energy_bonus;
		private $inc_energy_bonus;
		private $honor;
		private $intro_link;
		private $salary_multiplier;
		private $enable_salary_multiplier;
		private $total_battles;
		private $player_stats;
		private $clan_id;
		
		//options
		private $enable_sound;
		private $enable_animations;
		private $enable_alpha;
		private $enable_hd;
		
		// equipment names
		private $equipment_names;
		
		//stats
		private $pf;
		private $xp;
		private $karma;
		private $points;
		private $points_per_stats;
		
		//items
		private $inventory;
		private $orbs;
		private $equipment;
		private $weapon;
		
		//skills
		private $skills;
		private $skills_names;
		
		//warehouse
		private $warehouse;
		
		//map
		private $map_name;
		private $map_position;
		
		
		public function __construct($player_data, $load_mode = PLAYER_ALL) {
		
			//general info
			parent::__construct($player_data['player_id'], $player_data['username'], $player_data['pv'], $player_data['level']);
								
			
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL) 
			or ( ($load_mode & PLAYER_GENERAL) == PLAYER_GENERAL) ) {
			
				$this->uid 							= $player_data['user_id']		;
				$this->avatar						= $player_data['user_avatar']	;
				$this->gender						= $player_data['gender']		;
				$this->orga							= RPGOrganisations::getOrganisation($player_data['organisation_id']);
				$this->is_leader 					= $player_data['leader']		;
				$this->bgm							= $player_data['bgm']			;
				$this->energy						= $player_data['energy']		;
				$this->max_energy_bonus 			= $player_data['max_energy_bonus'];
				$this->inc_energy_bonus 			= $player_data['inc_energy_bonus'];
				$this->honor						= $player_data['honor']			;
				$this->intro_link 					= $player_data['introduction_link'];
				$this->salary_multiplier 			= $player_data['salary_multiplier'];
				$this->enable_salary_multiplier 	= $player_data['enable_salary_level_multiplier'];
				$this->total_battles 				= $player_data['total_battles'];
				$this->player_stats 				= RPGPlayersStats::getStatsByPlayer($player_data['player_id']);
				$this->clan_id						= $player_data['clan_id'];
				
				//options
				$this->enable_sound 		= $player_data['enable_sound']				;
				$this->enable_animations 	= $player_data['enable_animations']			;
				$this->enable_alpha 		= $player_data['enable_alpha']				;
				$this->enable_hd			= $player_data['enable_hd']					;
				
				//equipment names
				$this->equipment_names = array();
				
				$this->equipment_names[ARMOR_CLOTH] 	= $player_data['cloth_name']	;
				$this->equipment_names[ARMOR_LEGGINGS] 	= $player_data['leggings_name']	;
				$this->equipment_names[ARMOR_GLOVES] 	= $player_data['gloves_name']	;
				$this->equipment_names[ARMOR_SHOES] 	= $player_data['shoes_name']	;
				
				//stats
				$this->pf 		= $player_data['pf']			;
				$this->xp 		= $player_data['xp']			;
				$this->karma	= $player_data['karma']			;
				
				$this->points_per_stats = array();
				$this->points_per_stats[STAT_ATTACK] = $player_data['atk_points'];
				$this->points_per_stats[STAT_DEFENSE] = $player_data['def_points'];
				$this->points_per_stats[STAT_SPEED] = $player_data['spd_points'];
				$this->points_per_stats[STAT_FLUX] = $player_data['flux_points'];
				$this->points_per_stats[STAT_RESISTANCE] = $player_data['res_points'];
				
				$this->updatePoints();
			}
			
			//inventory
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL) 
			or ( ($load_mode & PLAYER_INVENTORY) == PLAYER_INVENTORY) ) {
				
				$this->inventory 					= RPGInventories::getInventoryByPlayer($player_data['player_id'])					;
			}

			//warehouse
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL) 
			or ( ($load_mode & PLAYER_WAREHOUSE) == PLAYER_WAREHOUSE) ) {
				
				$this->warehouse					= RPGWarehouses::getWarehouseByPlayer($player_data['player_id'])					;
			}
			
			//orbs
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL) 
			or ( ($load_mode & PLAYER_ORBS) == PLAYER_ORBS) ) {
			
				$this->orbs		= array()						;
				
				if($player_data['orb1'] != null)
					$this->orbs['orb1']	= 	RPGOrbs::getOrb($player_data['orb1']);
					
				if($player_data['orb2'] != null)
					$this->orbs['orb2']	= 	RPGOrbs::getOrb($player_data['orb2']);
					
				if($player_data['orb3'] != null)
					$this->orbs['orb3']	= 	RPGOrbs::getOrb($player_data['orb3']);
					
				if($player_data['orb4'] != null)
					$this->orbs['orb4']	= 	RPGOrbs::getOrb($player_data['orb4']);
			}
			
			//equipment
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL) 
			or ( ($load_mode & PLAYER_EQUIPMENT) == PLAYER_EQUIPMENT) ) {
			
				//weapon
				$this->weapon 						= RPGWeapons::getWeaponByPlayer($this->id)											;				
				
				$this->equipment = array()						;
				$this->equipment[ARMOR_CLOTH] 		= RPGArmorParts::getArmorPartByPlayerAndType($this->id, ARMOR_CLOTH)				;
				$this->equipment[ARMOR_LEGGINGS] 	= RPGArmorParts::getArmorPartByPlayerAndType($this->id, ARMOR_LEGGINGS)				;
				$this->equipment[ARMOR_GLOVES] 		= RPGArmorParts::getArmorPartByPlayerAndType($this->id, ARMOR_GLOVES)				;
				$this->equipment[ARMOR_SHOES] 		= RPGArmorParts::getArmorPartByPlayerAndType($this->id, ARMOR_SHOES)				;
			}
			
			//skills
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL) 
			or ( ($load_mode & PLAYER_SKILLS) == PLAYER_SKILLS) ) {
			
				$this->skills = array();
				$this->skills[1] 					= Skill::getSkillByType($player_data['skill_1'], $player_data['skill_1_element'], $player_data['skill_1_subskill'])	;
				$this->skills[2] 					= Skill::getSkillByType($player_data['skill_2'], $player_data['skill_2_element'], $player_data['skill_2_subskill'])	;
				$this->skills[3] 					= Skill::getSkillByType($player_data['skill_3'], $player_data['skill_3_element'], $player_data['skill_3_subskill'])	;
				$this->skills[4] 					= Skill::getSkillByType($player_data['skill_4'], $player_data['skill_4_element'], $player_data['skill_4_subskill'])	;
				
				$this->skills_names = array(1 => "", 2 => "", 3 => "", 4 => "");
				$this->skills_names[1]				= (string) $player_data['skill_1_name']														;
				$this->skills_names[2]				= (string) $player_data['skill_2_name']														;
				$this->skills_names[3]				= (string) $player_data['skill_3_name']														;
				$this->skills_names[4]				= (string) $player_data['skill_4_name']														;
			}
			
			//map
			if( ( ($load_mode & PLAYER_ALL) == PLAYER_ALL)
			or ( ($load_mode & PLAYER_MAP) == PLAYER_MAP) ) {
				$this->map_charset = ($player_data['map_charset'] != null) ? (string) $player_data['map_charset'] : false;
				$this->map_name = ($player_data['map_name'] != null) ? (string) $player_data['map_name'] : false;
				
				if($player_data['map_position'] != null) {
					$this->map_position = array();
					$pos = explode(",", $player_data['map_position']);
					$this->map_position['x'] = $pos[0];
					$this->map_position['y'] = $pos[1];
				} else {
					$this->map_position = false;
				} 
			}
		}
		
		// POINTS 
		
		/*
		* Update remaining points according to level and points given in each stat.
		*/
		public function updatePoints() {
			$level_points = $this->level * POINTS_PER_LEVEL;
			$given_points = 0;
			
			foreach($this->points_per_stats as $points) {
				$given_points += $points;
			}
			
			$level_points -= $given_points;
			$this->points = $level_points;
		}
		
		/*
		* Reset points. Remove points given in each stat.
		*/
		public function resetPoints() {
			$this->points = $this->level * POINTS_PER_LEVEL;
			
			$this->points_per_stats[STAT_ATTACK] = 0;
			$this->points_per_stats[STAT_DEFENSE] = 0;
			$this->points_per_stats[STAT_SPEED] = 0;
			$this->points_per_stats[STAT_FLUX] = 0;
			$this->points_per_stats[STAT_RESISTANCE] = 0;
			
			$this->updatePoints();
		}
		
		public function getPointsOfStat($stat) {
			return $this->points_per_stats[$stat];
		}
		
		public function setPointsOfStat($stat, $points) {
			$old_points = $this->getPointsOfStat($stat);
			$this->points_per_stats[$stat] = $points;
			
			if( ($this->getBaseAtk() > STAT_MAX_CAPACITY)
				|| ($this->getBaseDef() > STAT_MAX_CAPACITY)
				|| ($this->getBaseSpd() > STAT_MAX_CAPACITY)
				|| ($this->getBaseFlux() > STAT_MAX_CAPACITY)
				|| ($this->getBaseRes() > STAT_MAX_CAPACITY)
			){
				$this->points_per_stats[$stat] = $old_points;
				$this->setPointsOfStat($stat, $points - 1);
			}
			
			$this->updatePoints();
		}
		
		//updates
		public function updateStatsPointsFromBDD() {
		
			$player_data = RPGPlayers::getPlayerInfo($this->id);
			
			$this->points_per_stats = array();
			$this->points_per_stats[STAT_ATTACK] = $player_data['atk_points'];
			$this->points_per_stats[STAT_DEFENSE] = $player_data['def_points'];
			$this->points_per_stats[STAT_SPEED] = $player_data['spd_points'];
			$this->points_per_stats[STAT_FLUX] = $player_data['flux_points'];
			$this->points_per_stats[STAT_RESISTANCE] = $player_data['res_points'];
			
			$this->updatePoints();
		}
		
		public function updateSkillsFromBDD() {
			$player_data = RPGPlayers::getPlayerInfo($this->id);
			
			$this->skills = array();
			$this->skills[1] 					= Skill::getSkillByType($player_data['skill_1'], $player_data['skill_1_element'], $player_data['skill_1_subskill'])	;
			$this->skills[2] 					= Skill::getSkillByType($player_data['skill_2'], $player_data['skill_2_element'], $player_data['skill_2_subskill'])	;
			$this->skills[3] 					= Skill::getSkillByType($player_data['skill_3'], $player_data['skill_3_element'], $player_data['skill_3_subskill'])	;
			$this->skills[4] 					= Skill::getSkillByType($player_data['skill_4'], $player_data['skill_4_element'], $player_data['skill_4_subskill'])	;
		}
		
		public function updateWeaponFromBDD() {
			$this->weapon = RPGWeapons::getWeaponByPlayer($this->id);
		}
		
		public function updateStatsFromBDD() {
			$this->player_stats = RPGPlayersStats::getStatsByPlayer($this->id);
		}
		
		//---GETTERS---
		//general info
		public function getUserId() {
			return $this->uid;
		}
		
		public function getAvatar() {
			return $this->avatar;
		}
		
		public function soundEnabled() {
			return $this->enable_sound;
		}
		
		public function animationsEnabled() {
			return $this->enable_animations;
		}
		
		public function alphaEnabled() {
			return $this->enable_alpha;
		}
		
		public function hdEnabled() {
			return $this->enable_hd;
		}
		
		public function getBGM() {
			return $this->bgm;
		}
		
		public function getEnergy() {
			return $this->energy;
		}
		
		public function getMaxEnergyBonus() {
			return $this->max_energy_bonus;
		}
		
		public function getIncEnergyBonus() {
			return $this->inc_energy_bonus;
		}
		
		public function getTotalBattles() {
			return $this->total_battles;
		}
		
		public function getPlayerStats() {
			return $this->player_stats;
		}
		
		public function getHonor() {
			return $this->honor;
		}
		
		public function getSalary() {
			global $SALARIES;
			
			$data = RPGUsersPlayers::getUserData($this->uid);
			$rank_id = (int) $data['user_rank'];
			if(!array_key_exists($rank_id, $SALARIES)) return 0;
			
			$lvl_coef = 1;
			if($this->getLevel() >= 30) $lvl_coef = 4;
			else if($this->getLevel() >= 20) $lvl_coef = 3;
			else if($this->getLevel() >= 10) $lvl_coef = 2;
			
			if($this->enable_salary_multiplier)
				return (int) ($SALARIES[$rank_id] * $lvl_coef * $this->salary_multiplier);
			else
				return (int) ($SALARIES[$rank_id] * $this->salary_multiplier);
		}
		
		public function getIntroductionLink() {
			return $this->intro_link;
		}
		
		public function getRalz() {
			$value = 0;
			for($i = 0 ; $i < INVENTORY_SIZE ; $i++) {
				$item = $this->inventory->getItem($i);
				if(get_class($item) === "Ralz")
					$value += $item->getValue();
			}
			
			return $value;
			//return $this->ralz;
		}
		
		public function getGender() {
			return $this->gender;
		}
		
		public function getOrganisation() {
			return $this->orga;
		}
		
		public function getClanId() {
			return $this->clan_id;
		}
		
		public function getClan() {
			return RPGClans::getClanByUserId($this->uid);
		}
		
		public function getArmorPartName($type) {
			return ( $this->equipment_names[$type] !== "" ? $this->equipment_names[$type] : ($this->getEquipment($type) !== null ? $this->getEquipment($type)->getName() : "") );
		}
		
		//stats
		
		/* Redefinition from Creature.class.php */
		public function getMaxPV() {
			// get orb multiplier
			$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getPV() != 0) ) {
					$orb_multiplier += $orb->getPV();
				}
			}
			
			// base pv
			$set = $this->getSet();
			
			$base_pv = MIN_PV
						+ ( ($this->getDefense() - DEFAULT_DEF) * PV_PER_DEF_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1) )
						;
						
			$equipment_pv = 0;
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_pv += $equip->getPV();
			}
			
			//$clan = $this->getClan();
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_PV)) == 0 ) )
				$clan_pv = 0;
			else
				$clan_pv = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_PV];
			
			return ( ( $base_pv + ceil($base_pv * $orb_multiplier) + ($set !== null ? $set->getPV() : 0) + $equipment_pv + $clan_pv) * ( $this->isLeader() ? LEADER_PV_MULTIPLIER : 1) );
		}
		
		public function getPF() {
			return $this->pf;
		}
		
		public function getMaxPF() {
			// get orb multiplier
			$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getPF() != 0) ) {
					$orb_multiplier += $orb->getPF();
				}
			}
			
			// base pf
			$set = $this->getSet();
			
			$base_pf = MIN_PF
						+ ( ($this->getResistance() - DEFAULT_RES) * PF_PER_RES_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1) )
						;
						
			$equipment_pf = 0;
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_pf += $equip->getPF();
			}
			
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_PF)) == 0 ) )
				$clan_pf = 0;
			else
				$clan_pf = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_PF];
				
			return ( ( $base_pf + ceil($base_pf * $orb_multiplier) + ($set !== null ? $set->getPF() : 0) + $equipment_pf + $clan_pf ) * ( $this->isLeader() ? LEADER_PF_MULTIPLIER : 1) );
		}
		
		public function getAttack() {
			// get orb multiplier
			/*$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getAttack() != 0) ) {
					$orb_multiplier += $orb->getAttack();
				}
			}*/
			$orb_bonus = 0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getAttack() != 0) ) {
					$orb_bonus += $orb->getAttack();
				}
			}
			
			$base_atk = $this->getBaseAtk();
			$equipment_atk = 0;
			$set = $this->getSet();
			
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_atk += $equip->getAttack();
			}
			
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_ATTACK)) == 0 ) )
				$clan_atk = 0;
			else
				$clan_atk = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_ATTACK];
			
			return $base_atk
				//+ ceil($base_atk * $orb_multiplier)
				+ $orb_bonus
				+ $equipment_atk
				+ ($set !== null ? $set->getAtk() : 0)
				+ $clan_atk;
		}
		
		public function getDefense() {
			// get orb multiplier
			/*$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getDefense() != 0) ) {
					$orb_multiplier += $orb->getDefense();
				}
			}*/
			$orb_bonus = 0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getDefense() != 0) ) {
					$orb_bonus += $orb->getDefense();
				}
			}
			
			$base_def = $this->getBaseDef();
			$equipment_def = 0;
			$set = $this->getSet();
			
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_def += $equip->getDefense();
			}
			
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_DEFENSE)) == 0 ) )
				$clan_def = 0;
			else
				$clan_def = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_DEFENSE];
				
			return $base_def
				//+ ceil($base_def * $orb_multiplier)
				+ $orb_bonus
				+ $equipment_def
				+ ($set !== null ? $set->getDef() : 0)
				+ $clan_def;
		}
		
		public function getSpeed() {
			// get orb multiplier
			/*$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getSpeed() != 0) ) {
					$orb_multiplier += $orb->getSpeed();
				}
			}*/
			$orb_bonus = 0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getSpeed() != 0) ) {
					$orb_bonus += $orb->getSpeed();
				}
			}
			
			$base_spd = $this->getBaseSpd();
			$equipment_spd = 0;
			$set = $this->getSet();
			
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_spd += $equip->getSpeed();
			}
			
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_SPEED)) == 0 ) )
				$clan_spd = 0;
			else
				$clan_spd = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_SPEED];
				
			return $base_spd
				//+ ceil($base_spd * $orb_multiplier)
				+ $orb_bonus
				+ $equipment_spd
				+ ($set !== null ? $set->getVit() : 0)
				+ $clan_spd;
		}
		
		public function getFlux() {
			// get orb multiplier
			/*$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getFlux() != 0) ) {
					$orb_multiplier += $orb->getFlux();
				}
			}*/
			$orb_bonus = 0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getFlux() != 0) ) {
					$orb_bonus += $orb->getFlux();
				}
			}
			
			$base_flux = $this->getBaseFlux();
			$equipment_flux = 0;
			$set = $this->getSet();
			
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_flux += $equip->getFlux();
			}
			
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_FLUX)) == 0 ) )
				$clan_flux = 0;
			else
				$clan_flux = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_FLUX];
				
			return $base_flux
				//+ ceil($base_flux * $orb_multiplier)
				+ $orb_bonus
				+ $equipment_flux
				+ ($set !== null ? $set->getFlux() : 0)
				+ $clan_flux;
		}
		
		public function getResistance() {
			// get orb multiplier
			/*$orb_multiplier = 0.0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getResistance() != 0) ) {
					$orb_multiplier += $orb->getResistance();
				}
			}*/
			$orb_bonus = 0;
			foreach($this->orbs as $orb) {
				if( ($orb !== null) and ($orb->getResistance() != 0) ) {
					$orb_bonus += $orb->getResistance();
				}
			}
			
			$base_res = $this->getBaseRes();
			$equipment_res = 0;
			$set = $this->getSet();
			
			foreach($this->equipment as $equip) {
				if($equip !== null)
					$equipment_res += $equip->getResistance();
			}
			
			$stat_level = 0;
			if( !$this->clan_id or ( ($stat_level = RPGClans::getStatLevel($this->clan_id, STAT_RESISTANCE)) == 0 ) )
				$clan_res = 0;
			else
				$clan_res = RPGConfig::$_CLAN_STAT_BONUS[$stat_level][STAT_RESISTANCE];
				
			return $base_res
				//+ ceil($base_res * $orb_multiplier)
				+ $orb_bonus
				+ $equipment_res
				+ ($set !== null ? $set->getResistance() : 0)
				+ $clan_res;
		}
		
		public function getBaseAtk() {
			return parent::getAttack()
				+ $this->getPointsOfStat(STAT_ATTACK) * ATK_PER_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1); 
		}
		
		public function getBaseDef() {
			return parent::getDefense()
				+ $this->getPointsOfStat(STAT_DEFENSE) * DEF_PER_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1); 
		}
		
		public function getBaseSpd() {
			return parent::getSpeed()
				+ $this->getPointsOfStat(STAT_SPEED) * SPD_PER_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1); 
		}
		
		public function getBaseFlux() {
			return parent::getFlux()
				+ $this->getPointsOfStat(STAT_FLUX) * FLUX_PER_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1); 
		}
		
		public function getBaseRes() {
			return parent::getResistance()
				+ $this->getPointsOfStat(STAT_RESISTANCE) * RES_PER_POINT * ($this->isLeader() ? LEADER_STAT_EFFECT_MULTIPLIER : 1); 
		}
		
		public function getXP() {
			return $this->xp;
		}
		
		public function getKarma(){
			return $this->karma;
		}
		
		public function getPoints() {
			return $this->points;
		}
		
		//items
		public function getWeapon() {
			return $this->weapon;
		}
		
		public function getEquipment($type) {
			return $this->equipment[$type];
		}
		
		public function getOrb($index) {
			if($index == 1) return $this->orbs['orb1'];
			if($index == 2) return $this->orbs['orb2'];
			if($index == 3) return $this->orbs['orb3'];
			if($index == 4) return $this->orbs['orb4'];
			return null;
		}
		
		public function hasEquippedOrb(Orb $orb) {
			foreach($this->orbs as $key => $o) {
				if($orb->getId() == $o->getId()) return true;
			}
			
			return false;
		}
		
		public function getOrbsSize() {
			$i = 0;
			
			foreach($this->orbs as $key => $o) {
				$i += $o->getSize();
			}
			
			return $i;
		}
		
		public function isLeader() {
			return $this->is_leader;
		}
		
		public function getInventory() {
			return $this->inventory;
		}
		
		public function getWarehouse() {
			return $this->warehouse;
		}
		
		public function getSkill($index) {
			$index = (int) $index;
			
			if( ($index !== 1) and ($index !== 2) and ($index !== 3) and ($index !== 4) ) return null;
			
			return $this->skills[$index];
		}
		
		public function getSkillName($index) {
			$index = (int) $index;
			
			if( ($index !== 1) and ($index !== 2) and ($index !== 3) and ($index !== 4) ) return "";
			
			return $this->skills_names[$index];
		}
		
		//bonus
		public function getSet() {
			if($this->equipment[ARMOR_CLOTH] === null
				or $this->equipment[ARMOR_LEGGINGS] === null
				or $this->equipment[ARMOR_GLOVES] === null
				or $this->equipment[ARMOR_SHOES] === null) return null;
				
			$set = RPGSets::getSetByParts($this->equipment[ARMOR_CLOTH]->getPartId()
										, $this->equipment[ARMOR_LEGGINGS]->getPartId()
										, $this->equipment[ARMOR_GLOVES]->getPartId()
										, $this->equipment[ARMOR_SHOES]->getPartId());
			return $set;
		}
		
		public function isInBattle() {
			/*$pvp_battle = RPGPVPBattles::getBattleByPlayerId($this->getId());
			
			if(RPGPVEBattles::getBattleByPlayerId($this->getId()) or ($pvp_battle and $pvp_battle->isStarted()) or RPGEventBattles::isInAnyEvent($this->id) or RPGQuests::isInAnyBattle($this->getId())) return true;
			else return false;*/
			
			return ($this->isInPVE() or $this->isInPVP() or $this->isInEvent() or $this->isInQuest());
		}
		
		public function isInPVE() {
			if(RPGPVEBattles::getBattleByPlayerId($this->getId()))
				return true;
			else
				return false;
		}
		
		public function isInPVP() {
			$pvp_battle = RPGPVPBattles::getBattleByPlayerId($this->getId());
			
			if($pvp_battle and $pvp_battle->isStarted())
				return true;
			else
				return false;
		}
		
		public function isInEvent() {
			if(RPGEventBattles::isInAnyEvent($this->getId()))
				return true;
			else
				return false;
		}
		
		public function isInQuest() {
			if(RPGQuests::isInAnyBattle($this->getId()))
				return true;
			else
				return false;
		}
		
		//setters
		public function setGender($gender) {
			$this->gender = $gender;
		}
		
		
		public function setSoundEnabled($state) {
			$this->enable_sound = (bool) $state;
		}
		
		public function setAnimationsEnabled($state) {
			$this->enable_animations = (bool) $state;
		}
		
		public function setAlphaEnabled($state) {
			$this->enable_alpha = (bool) $state;
		}
		
		public function setHDEnabled($state) {
			$this->enable_hd = (bool) $state;
		}
		
		public function setIntroductionLink($link) {
			$this->intro_link = $link;
		}
		
		// Override
		public function setLevel($level) {
			if($level < $this->getLevel())
				$this->resetPoints();
				
			parent::setLevel($level);
			$this->updatePoints();
		}
		
		public function setRalz($ralz) {
			for($i = 0 ; $i < INVENTORY_SIZE ; $i++) {
				$item = $this->inventory->getItem($i);
				if(get_class($item) === "Ralz") {
					$item->setValue($ralz);
					$this->inventory->setItem($item, 1, $i);
				}
			}
			
		}
		
		public function setPF($pf) {
			$this->pf = $pf;
			
			if($pf < 0)
				$this->pf = 0;
			if($pf > $this->getMaxPF())
				$this->pf = $this->getMaxPF();
		}
		
		public function setLeader($leader) {
			$this->is_leader = $leader;
			resetPoints();
		}
		
		/*public function setMaxPF($pf) {
			if($pf < MIN_PF)
				return;
				
			$this->max_pf = $pf;
		}*/
		
		public function setXP($xp) {
			if($xp < 0) $xp = 0;
			$this->xp = $xp;
		}
		
		public function setKarma($karma) {
			if($karma < 1 or $karma > 5)
				return;
				
			$this->karma = $karma;
		}
		
		public function setEnergy($energy) {
			if($energy < 0 or $energy > MAX_ENERGY) return;
			
			$this->energy = $energy;
		}
		
		public function setMaxEnergyBonus($bonus) {
			if($bonus < 0) return;
			
			$this->max_energy_bonus = $bonus;
		}
		
		public function setIncEnergyBonus($bonus) {
			if($bonus < 0) return;
			
			$this->inc_energy_bonus = $bonus;
		}
		
		public function setTotalBattles($total) {
			if($total < 0) return;
			
			$this->total_battles = $total;
		}
		
		public function setHonor($honor) {
			if($honor < 0) return;
			
			$this->honor = $honor;
		}
		
		
		public function setEquipmentName($type, $name) {
			$this->equipment_names[$type] = $name;
		}
		
		public function setSkillName($skill_number, $name) {
			$this->skills_names[$skill_number] = $name;
		}
		
		public function setOrb($slot, $id) {
			if($slot == 1)
				$this->orbs['orb1']	= 	RPGOrbs::getOrb($id);
			else if($slot == 2)
				$this->orbs['orb2']	= 	RPGOrbs::getOrb($id);
			else if($slot == 3)
				$this->orbs['orb3']	= 	RPGOrbs::getOrb($id);
			else if($slot == 4)
				$this->orbs['orb4']	= 	RPGOrbs::getOrb($id);
		}
		
		public function removeOrb($slot) {
			if($slot == 1)
				unset($this->orbs['orb1']);
			else if($slot == 2)
				unset($this->orbs['orb2']);
			else if($slot == 3)
				unset($this->orbs['orb3']);
			else if($slot == 4)
				unset($this->orbs['orb4']);
		}
		
		/*public function setPoints($points) {
			if($points < 0)
				return;
				
			$this->points = $points;
		}*/
		
		// INVENTORY
		public function giveItem(Item $i) {
			$this->inventory->addItem($i);
		}
		
		public function updateInventory() {
			$this->inventory = RPGInventories::getInventoryByPlayer($this->id);
		}
		
		public function updateWarehouse() {
			$this->warehouse = RPGWarehouses::getWarehouseByPlayer($this->id);
		}
		
		public function setItem(Item $i, $number, $index) {
			$this->inventory->setItem($i, $number, $index);
		}
		
		
		// BATTLE STATS
		public function getBattleDamage($attack_buff = 0) {
			return (int) ($this->getAttack() + $attack_buff + $this->getWeapon()->getAttack());
		}
		
		public function getBattleDefense($defense_buff = 0) {
			return (int) floor( ($this->getDefense() + $defense_buff) / 2);
		}
		
		public function getBattleMagicDamage($flux_buff = 0) {
			return (int) ($this->getFlux() + $flux_buff + $this->getWeapon()->getAttack());
		}
		
		public function getBattleMagicDefense($res_buff = 0) {
			return (int) floor(($this->getResistance() + $res_buff) / 2);
		}
		
		public function getBattleAccuracy($attack_buff = 0, $speed_buff = 0) {
			$dex = (($this->getSpeed() + $speed_buff) + ($this->getAttack() + $attack_buff) * 2) / 2;
			
			return (int) (BASE_ACCURACY_VALUE + $this->getWeapon()->getAccuracy() + $dex);
		}
		
		public function getBattleMagicAccuracy($flux_buff = 0, $speed_buff = 0) {
			$dex = (($this->getSpeed() + $speed_buff) + ($this->getFlux() + $flux_buff) * 2) / 2;
			
			return (int) (BASE_ACCURACY_VALUE + $this->getWeapon()->getAccuracy() + $dex);
		}
		
		public function getBattleCritical($spd_buff = 0 /*$attack_buff = 0, $flux_buff = 0*/) {
			//$base_crit = (int) floor( ($this->getAttack() + $attack_buff + $this->getFlux() + $flux_buff) / 2);
			$base_crit = (int) floor( ($this->getSpeed() + $spd_buff) / 2);
			return (int) floor(BASE_CRITICAL_VALUE + $this->getWeapon()->getCritical() + $base_crit);
		}
		
		public function getBattleEvade($speed_buff = 0) {
			return (int) (($this->getSpeed() + $speed_buff) * 1.2);
		}
		
		public function getBattleDodge($defense_buff = 0, $res_buff = 0) {
			return (int) (($this->getDefense() + $defense_buff + $this->getResistance() + $res_buff) / 2);
		}
		
		
		
		
		
		
		
		// MAP
		public function getMapCharset() {
			return $this->map_charset;
		}
		
		public function getMapName() {
			return $this->map_name;
		}
		
		public function getMapPosition() {
			return $this->map_position;
		}
		
		
	}
	
?>