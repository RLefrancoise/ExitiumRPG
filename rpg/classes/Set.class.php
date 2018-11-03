<?php
	include_once(__DIR__ . "/SetPart.class.php");
	include_once(__DIR__ . "/../database/RPGClothes.class.php");
	include_once(__DIR__ . "/../database/RPGLeggings.class.php");
	include_once(__DIR__ . "/../database/RPGGloves.class.php");
	include_once(__DIR__ . "/../database/RPGShoes.class.php");
	
	class Set {
		
		private $id;
		private $name;
		private $desc;
		private $price;
		private $cloth;
		private $leggings;
		private $gloves;
		private $shoes;
		private $pv;
		private $pf;
		private $atk;
		private $def;
		private $vit;
		private $flux;
		private $res;
		
		public function __construct($set_data) {
			$this->id = $set_data['id'];
			$this->name = $set_data['name'];
			$this->desc = $set_data['descr'];
			$this->price = $set_data['price'];
			$this->cloth = new SetPart(RPGClothes::getCloth($set_data['cloth_id']), ARMOR_CLOTH);
			$this->leggings = new SetPart(RPGLeggings::getLegging($set_data['leggings_id']), ARMOR_LEGGINGS);
			$this->gloves = new SetPart(RPGGloves::getGlove($set_data['gloves_id']), ARMOR_GLOVES);
			$this->shoes = new SetPart(RPGShoes::getShoe($set_data['shoes_id']), ARMOR_SHOES);
			$this->pv = $set_data['pv'];
			$this->pf = $set_data['pf'];
			$this->atk = $set_data['atk'];
			$this->def = $set_data['def'];
			$this->vit = $set_data['vit'];
			$this->flux = $set_data['flux'];
			$this->res = $set_data['res'];
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
		
		public function getPrice() {
			return $this->price;
		}
		
		public function getCloth() {
			return $this->cloth;
		}
		
		public function getLeggings() {
			return $this->leggings;
		}
		
		public function getGloves() {
			return $this->gloves;
		}
		
		public function getShoes() {
			return $this->shoes;
		}
		
		public function getPV() {
			return $this->pv;
		}
		
		public function getPF() {
			return $this->pf;
		}
		
		public function getAtk() {
			return $this->atk;
		}
		
		public function getDef() {
			return $this->def;
		}
		
		public function getVit() {
			return $this->vit;
		}
		
		public function getFlux() {
			return $this->flux;
		}
		
		public function getResistance() {
			return $this->res;
		}
	}
?>
