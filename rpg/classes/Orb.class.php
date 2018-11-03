<?php

	include_once(__DIR__ . "/Item.class.php");
	include_once(__DIR__ . "/rpgconfig.php");
	
	class Orb extends Item {
		
		private $level;
		private $type;
		private $attack;
		private $defense;
		private $speed;
		private $flux;
		private $resistance;
		private $pv;
		private $pf;
		private $effect;
		private $effect_trigger;
		private $size;

		public function __construct($orb_data) {
			parent::__construct($orb_data['id'], $orb_data['name'], $orb_data['descr'], $orb_data['price'] , $orb_data['img'], false);
			$this->level = $orb_data['level'];
			$this->type = $orb_data['type'];
			
			$this->attack = $orb_data['attack'];
			$this->defense = $orb_data['defense'];
			$this->speed = $orb_data['speed'];
			$this->flux = $orb_data['flux'];
			$this->resistance = $orb_data['resistance'];
			$this->pv = $orb_data['pv'];
			$this->pf = $orb_data['pf'];
			$this->effect = $orb_data['effect'];
			$this->effect_trigger = $orb_data['trig'];
			$this->size = $orb_data['size'];
		}
		
		public function getToolTipText() {
			return parent::getToolTipText() . '<br><em>Nombre de slots : ' . $this->size . '</em>';
		}
		/*public function getLevel() {
			return $this->level;
		}
		
		public function getType() {
			return $this->type;
		}*/
		
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
		
		public function getPV() {
			return $this->pv;
		}
		
		public function getPF() {
			return $this->pf;
		}
		
		public function getEffect() {
			return $this->effect;
		}
		
		public function getEffectTrigger() {
			return $this->effect_trigger;
		}
		
		public function getSize() {
			return $this->size;
		}
		
		public function isStandard() {
			return ( ($this->attack) or ($this->defense) or ($this->speed) or ($this->flux) or ($this->resistance) or ($this->pv) or ($this->pf) );
		}
		
		public function isPassive() {
			return ($this->effect and $this->effect_trigger);
		}
	}
?>