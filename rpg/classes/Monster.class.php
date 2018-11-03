<?php
	include_once(__DIR__ . "/rpgconfig.php");
	include_once(__DIR__ . "/Creature.class.php");
	include_once(__DIR__ . '/../database/RPGMonsters.class.php');
	
	class Monster extends Creature {
		
		private $pf;
		private $max_pf;
		private $img;
		private $skills;
		private $skills_names;
		private $drops;
		private $bgm;
		private $behaviors;
		private $ralz;
		private $drops_number;
		
		public function __construct($monster_data) {
			parent::__construct($monster_data['id'], $monster_data['name'], $monster_data['pv'], $monster_data['level']);
			
			$this->max_pv = $monster_data['pv'];
			$this->pf = $monster_data['pf'];
			$this->max_pf = $monster_data['pf'];
			
			$this->setAttack($monster_data['atk']);
			$this->setDefense($monster_data['def']);
			$this->setSpeed($monster_data['spd']);
			$this->setFlux($monster_data['flux']);
			$this->setResistance($monster_data['res']);
			
			//image
			$this->img = $monster_data['img'];
			
			//skills
			$this->skills = RPGMonsters::getSkillsByMonster($monster_data['id']);
			$this->skills_names = RPGMonsters::getNamesOfSkillsByMonster($monster_data['id']);

			//drops
			$this->drops = RPGMonsters::getDropsByMonster($monster_data['id']);
			$this->drops_number = ($monster_data['drops_number'] != null ? $monster_data['drops_number'] : false);
			
			//bgm
			$this->bgm = $monster_data['bgm'];
			
			//behavior
			global $_MONSTERS_BEHAVIOR_FLAGS;
			$this->behaviors = array();
			$mnemonics = explode(',', $monster_data['behaviors']);
			
			foreach($mnemonics as $mnemonic) {
				if(!$_MONSTERS_BEHAVIOR_FLAGS[$mnemonic]) continue;
				if(array_key_exists($mnemonic, $this->behaviors)) continue;
				
				$this->behaviors[] = $mnemonic;
			}
			
			//ralz
			$this->ralz = $monster_data['ralz'];
		}
		
		public function getMaxPV() {
			return $this->max_pv;
		}
		
		public function getPF() {
			return $this->pf;
		}
		
		public function getMaxPF() {
			return $this->max_pf;
		}
		
		public function getImage() {
			return $this->img;
		}
		
		public function getSkills() {
			return $this->skills;
		}
		
		public function getSkillsNames() {
			return $this->skills_names;
		}
		
		public function getDrops() {
			return $this->drops;
		}
		
		public function getDropsNumber() {
			return $this->drops_number;
		}
		
		public function getDropsByAreaPart($area_part_id) {
			return $this->drops[$area_part_id];
		}
		
		public function getBGM() {
			return $this->bgm;
		}
		
		public function getBehaviors() {
			return $this->behaviors;
		}
		
		public function getXP() {
			$exp = ($this->getMaxPV() / 2 + $this->getMaxPF() / 2 + $this->getAttack() * 5 + $this->getDefense() * 5 + $this->getResistance() * 5 + $this->getSpeed() * 5 + $this->getFlux() * 5) / 7;
			return (int) floor($exp);
		}
		
		public function getRalz() {
			return (int) $this->ralz;
		}
	}
	
?>