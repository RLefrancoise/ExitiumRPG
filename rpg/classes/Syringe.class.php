<?php
	include_once(__DIR__ . "/Item.class.php");

	class Syringe extends Item {
		
		private $usable_outside_battle;
		private $pv;
		private $max_pv;
		private $pf;
		private $max_pf;
		private $atk;
		private $def;
		private $vit;
		private $flux;
		private	$res;
		
		public function __construct($syringe_data) {
			parent::__construct($syringe_data['id'], $syringe_data['name'], $syringe_data['descr'], $syringe_data['price'], $syringe_data['img'], false);
			$this->pv		=	$syringe_data['pv'];
			$this->max_pv	=	$syringe_data['max_pv'];
			$this->pf		=	$syringe_data['pf'];
			$this->max_pf	=	$syringe_data['max_pf'];
			$this->atk		=	$syringe_data['atk'];
			$this->def		=	$syringe_data['def'];
			$this->vit		=	$syringe_data['vit'];
			$this->flux		=	$syringe_data['flux'];
			$this->res		=	(int) $syringe_data['res'];
			$this->usable_outside_battle = $syringe_data['usable_outside_battle'];
		}
		
		public function getPV() {
			return $this->pv;
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
		
		public function getAttack() {
			return $this->atk;
		}
		
		public function getDefense() {
			return $this->def;
		}
		
		public function getSpeed() {
			return $this->vit;
		}
		
		public function getFlux() {
			return $this->flux;
		}
		
		public function getResistance() {
			return $this->res;
		}
		
		public function isUsableOutsideBattle() {
			return $this->usable_outside_battle;
		}
	}
	
?>
