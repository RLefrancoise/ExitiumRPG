<?php
	include_once(__DIR__ . "/Item.class.php");
	include_once(__DIR__ . "/../database/RPGClothes.class.php");
	include_once(__DIR__ . "/../database/RPGLeggings.class.php");
	include_once(__DIR__ . "/../database/RPGGloves.class.php");
	include_once(__DIR__ . "/../database/RPGShoes.class.php");
	
	include_once(__DIR__ . "/rpgconfig.php");
	
	class ArmorPart extends Item {
	
		private $part_name;
		private $part_id;
		private $type;
		private $atk;
		private $def;
		private $spd;
		private $flx;
		private $res;
		private $pv;
		private $pf;
		private $req_lvl;
		
		public function __construct($armor_data) {
			switch($armor_data['type']){
				case ARMOR_CLOTH:
					$part_info = RPGClothes::getCloth($armor_data['part_id']);
					break;
				case ARMOR_LEGGINGS:
					$part_info = RPGLeggings::getLegging($armor_data['part_id']);
					break;
				case ARMOR_GLOVES:
					$part_info = RPGGloves::getGlove($armor_data['part_id']);
					break;
				case ARMOR_SHOES:
					$part_info = RPGShoes::getShoe($armor_data['part_id']);
					break;
			}
			
			parent::__construct($armor_data['id'], $armor_data['name'], $part_info['descr'], $part_info['price'], $part_info['img'], true);
			$this->part_name = $part_info['name'];
			$this->part_id = $part_info['id'];
			$this->type = $armor_data['type'];
			$this->atk = $part_info['atk'];
			$this->def = $part_info['def'];
			$this->spd = $part_info['vit'];
			$this->flx = $part_info['flux'];
			$this->res = $part_info['res'];
			$this->pv  = $part_info['pv'];
			$this->pf  = $part_info['pf'];
			$this->req_lvl = $part_info['req_lvl'];
		}
		
		public function getToolTipText() {
			return '<strong>'.$this->name.'</strong><br>Effet : '.$this->desc.'<br>Niveau requis : '.$this->req_lvl;
		}
		
		public function getPartName() {
			return $this->part_name;
		}
		
		public function getPartId() {
			return $this->part_id;
		}
		
		public function getType() {
			return $this->type;
		}
		
		public function getAttack() {
			return $this->atk;
		}
		
		public function getDefense() {
			return $this->def;
		}
		
		public function getSpeed() {
			return $this->spd;
		}
		
		public function getFlux() {
			return $this->flx;
		}
		
		public function getResistance() {
			return $this->res;
		}
		
		public function getPV() {
			return $this->pv;
		}
		
		public function getPF() {
			return $this->pf;
		}
		
		public function getRequiredLevel() {
			return $this->req_lvl;
		}
	}
?>