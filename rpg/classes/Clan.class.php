<?php
	include_once(__DIR__ . '/rpgconfig.php');
	include_once(__DIR__ . '/RPGConfig.class.php');
	include_once(__DIR__ . '/../database/RPGUsersPlayers.class.php');
	include_once(__DIR__ . '/../database/RPGClans.class.php');
	
	class Clan {

		private $id;
		private $name;
		private $desc;
		private $img;
		private $leader;
		private $members;
		private $level;
		private $pi;
		/*private $atk_level;
		private $def_level;
		private $spd_level;
		private $flux_level;
		private $res_level;
		private $pv_level;
		private $pf_level;*/
		private $stat_level;
		
		public function __construct($clan_data, $members_list) {
			$this->id = $clan_data['id'];
			$this->name = $clan_data['name'];
			$this->desc = $clan_data['desc'];
			$this->img 	= $clan_data['img'];
			$this->leader = RPGUsersPlayers::getPlayerByUserId($clan_data['leader_id']);
			$this->members = $members_list;
			
			$lvls = 0;
			foreach($this->members as $member) {
				$lvls += $member->getLevel();
			}
			if(count($this->members) > 0)
				$this->level = (int) floor($lvls / count($this->members));
			else
				$this->level = 0;
				
			$this->pi			= 	$clan_data['pi'];
			/*$this->atk_level	=	$clan_data['atk_level'];
			$this->def_level	=	$clan_data['def_level'];
			$this->spd_level	=	$clan_data['spd_level'];
			$this->flux_level	=	$clan_data['flux_level'];
			$this->res_level	=	$clan_data['res_level'];
			$this->pv_level		=	$clan_data['pv_level'];
			$this->pf_level		=	$clan_data['pf_level'];*/
			
			$this->stat_level	=	array(
				STAT_ATTACK		=>	$clan_data['atk_level'],
				STAT_DEFENSE	=>	$clan_data['def_level'],
				STAT_SPEED		=>	$clan_data['spd_level'],
				STAT_FLUX		=>	$clan_data['flux_level'],
				STAT_RESISTANCE	=>	$clan_data['res_level'],
				STAT_PV			=>	$clan_data['pv_level'],
				STAT_PF			=>	$clan_data['pf_level'],
			);
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getDescription() {
			return $this->desc;
		}
		
		public function getImage() {
			return $this->img;
		}
		
		public function getLeader() {
			return $this->leader;
		}
		
		public function getMembers() {
			//return RPGClans::getMembersOfClan($this->getId());
			return $this->members;
		}
		
		public function getLevel() {
		
			//$members = $this->getMembers();
			
			$lvls = 0;
			foreach($this->members as $member) {
				$lvls += $member->getLevel();
			}
			if(count($this->members) > 0)
				$this->level = (int) floor($lvls / count($this->members));
			else
				$this->level = 0;
				
			return $this->level;
		}
		
		public function getMembersNumber() {
			//return count($this->getMembers());
			return count($this->members);
		}
		
		public function getHonor() {
			$honor = 0;
			
			foreach($this->members as $member) {
				$honor += $member->getHonor();
			}
			
			if(count($this->members) > 0)
				$honor = (int) floor($honor / count($this->members));
			else
				$honor = 0;
				
			return $honor;
		}
		
		public function getRalzBonus() {
			/*if($this->level >= 25)
				return 50;
			if($this->level >= 15) {
				return 25;
			}
			if($this->level >= 5) {
				return 10;
			}*/
			
			$members_nb = count($this->members);
			if($members_nb >= 50)
				return 50;
			if($members_nb >= 25) {
				return 25;
			}
			if($members_nb >= 10) {
				return 10;
			}
			
			return 0;
			
			/*$honor = $this->getHonor();
			
			if($honor >= 100)
				return 50;
			else if($honor >= 50)
				return 25;
			else if($honor >= 10)
				return 10;
			else
				return 0;*/
		}
		
		public function getXpBonus() {
			/*$members_nb = count($this->members);
			if($members_nb >= 50)
				return 50;
			if($members_nb >= 25) {
				return 25;
			}
			if($members_nb >= 10) {
				return 10;
			}
			
			return 0;*/
			
			$honor = $this->getHonor();
			
			if($honor >= 100)
				return 50;
			else if($honor >= 50)
				return 25;
			else if($honor >= 10)
				return 10;
			else
				return 0;
		}
		
		public function getPI() {
			return $this->pi;
		}
		
		public function getAttackLevel() {
			return $this->stat_level[STAT_ATTACK];
		}
		
		public function getDefenseLevel() {
			return $this->stat_level[STAT_DEFENSE];
		}
		
		public function getSpeedLevel() {
			return $this->stat_level[STAT_SPEED];
		}
		
		public function getFluxLevel() {
			return $this->stat_level[STAT_FLUX];
		}
		
		public function getResistanceLevel() {
			return $this->stat_level[STAT_RESISTANCE];
		}
		
		public function getPVLevel() {
			return $this->stat_level[STAT_PV];
		}
		
		public function getPFLevel() {
			return $this->stat_level[STAT_PF];
		}
		
		public function getStatLevel($stat) {
			return $this->stat_level[$stat];
		}
		
		public function getStatBonus($stat) {
			$level = 0;
			
			$level = $this->stat_level[$stat];
			
			if($level == 0) return 0;
			
			return RPGConfig::$_CLAN_STAT_BONUS[$level][$stat];
		}
		
		public function hasAnyStatBonus() {
			return 	(($this->getStatBonus(STAT_ATTACK) > 0)
				or	($this->getStatBonus(STAT_DEFENSE) > 0)
				or	($this->getStatBonus(STAT_SPEED) > 0)
				or	($this->getStatBonus(STAT_FLUX) > 0)
				or	($this->getStatBonus(STAT_RESISTANCE) > 0)
				or	($this->getStatBonus(STAT_PV) > 0)
				or	($this->getStatBonus(STAT_PF) > 0));
		}
		
		public function setPI($pi) {
			$this->pi = $pi;
		}
		
		public function setStatLevel($stat, $level) {
			$this->stat_level[$stat] = $level;
		}
	}
?>
