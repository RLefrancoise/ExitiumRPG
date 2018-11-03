<?php
	
	include_once(__DIR__ . "/rpgconfig.php");
	
	abstract class Creature {
		protected $id;
		protected $name;
		protected $pv;
		protected $max_pv;
		protected $level;
		protected $attack;
		protected $defense;
		protected $speed;
		protected $flux;
		protected $resistance;
		
		public function __construct($id, $name, $pv, $level) {
			$this->id = $id;
			$this->name = $name;
			$this->pv = $pv;
			$this->level = $level;
			
			$this->attack = DEFAULT_ATK;
			$this->defense = DEFAULT_DEF;
			$this->speed = DEFAULT_SPD;
			$this->flux = DEFAULT_FLUX;
			$this->resistance = DEFAULT_RES;
		}
		
		/*public function __construct($id, $name, $pv, $max_pv, $l, $a, $d, $s, $f) {
			$this->id = $id;
			$this->name = $name;
			$this->pv = $pv;
			$this->max_pv = $max_pv;
			$this->level = $l;
			$this->attack = $a;
			$this->defense = $d;
			$this->speed = $s;
			$this->flux = $f;
		}*/
		
		public function getId() {
			return $this->id;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getPV() {
			return $this->pv;
		}
		
		public function getMaxPV() {
			//return $this->max_pv;
			return MIN_PV + ($this->getDefense() - DEFAULT_DEF) * PV_PER_DEF_POINT;
		}
		
		public function getLevel() {
			return $this->level;
		}
		
		public function getBaseAtk() {
			return $this->getAttack();
		}
		
		public function getBaseDef() {
			return $this->getDefense();
		}
		
		public function getBaseSpd() {
			return $this->getSpeed();
		}
		
		public function getBaseFlux() {
			return $this->getFlux();
		}
		
		public function getBaseRes() {
			return $this->getResistance();
		}
		
		public function getAttack() {
			return $this->attack;
		}
		
		public function getDefense() {
			return $this->defense;
		}
		
		public function getSpeed() {
			return $this->speed;
		}
		
		public function getFlux() {
			return $this->flux;
		}
		
		public function getResistance() {
			return $this->resistance;
		}
		
		public function setPV($pv) {
			$this->pv = $pv;
			
			if($pv < 0)
				$this->pv = 0;
			if($pv > $this->getMaxPV())
				$this->pv = $this->getMaxPV();
		}
		
		/*public function setMaxPV($pv) {
			if($pv < MIN_PV)
				return;
				
			$this->max_pv = $pv;
		}*/
		
		public function setLevel($level) {
			$this->level = $level;
			
			if($level < 1)
				$this->level = 1;
			if($level > MAX_LEVEL)
				$this->level = MAX_LEVEL;
		}
		
		public function setAttack($attack) {
			$attack >= 0 ? $this->attack = $attack : $this->attack = 0;
		}
		
		public function setDefense($defense) {
			$defense >= 0 ? $this->defense = $defense : $this->defense = 0;
		}
		
		public function setSpeed($speed) {
			$speed >= 0 ? $this->speed = $speed : $this->speed = 0;
		}
		
		public function setFlux($flux) {
			$flux >= 0 ? $this->flux = $flux : $this->flux = 0;
		}
		
		public function setResistance($res) {
			$res >= 0 ? $this->resistance = $res : $this->resistance = 0;
		}
		
		// BATTLE STATS
		public function getBattleDamage($attack_buff = 0) {
			return (int) ($this->getAttack() + $attack_buff);
		}
		
		public function getBattleDefense($defense_buff = 0) {
			return (int) floor( ($this->getDefense() + $defense_buff) / 2);
		}
		
		public function getBattleMagicDamage($flux_buff = 0) {
			return (int) ($this->getFlux() + $flux_buff);
		}
		
		public function getBattleMagicDefense($res_buff = 0) {
			return (int) floor( ($this->getResistance() + $res_buff) / 2);
		}
		
		public function getBattleAccuracy($attack_buff = 0, $speed_buff = 0) {
			$dex = ( ($this->getSpeed() + $speed_buff) + ($this->getAttack() + $attack_buff) * 2) / 2;
			
			return (int) (BASE_ACCURACY_VALUE + $dex);
		}
		
		public function getBattleMagicAccuracy($flux_buff = 0, $speed_buff = 0) {
			$dex = ( ($this->getSpeed() + $speed_buff) + ($this->getFlux() + $flux_buff) * 2) / 2;
			
			return (int) (BASE_ACCURACY_VALUE + $dex);
		}
		
		public function getBattleCritical($attack_buff = 0, $flux_buff = 0) {
			$base_crit = (int) floor( ($this->getAttack() + $attack_buff + $this->getFlux() + $flux_buff) / 2);
			return (int) floor(BASE_CRITICAL_VALUE + $base_crit);
		}
		
		public function getBattleEvade($speed_buff = 0) {
			return (int) (($this->getSpeed() + $speed_buff) * 1.2);
		}
		
		public function getBattleDodge($defense_buff = 0, $res_buff = 0) {
			return (int) (($this->getDefense() + $defense_buff + $this->getResistance() + $res_buff) / 2);
		}
	}
	
?>