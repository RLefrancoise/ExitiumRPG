<?php
	include_once(__DIR__ . "/Item.class.php");
	include_once(__DIR__ . "/rpgconfig.php");
	
	class SetPart extends Item{
		
		private $pv;
		private $pf;
		private $atk;
		private $def;
		private $vit;
		private $flux;
		private $res;
		private $req_lvl;
		private $type;
		
		public function __construct($setpart_data, $type) {
			parent::__construct($setpart_data['id'], $setpart_data['name'], $setpart_data['descr'], $setpart_data['price'], $setpart_data['img'], true);
			$this->pv = $setpart_data['pv'];
			$this->pf = $setpart_data['pf'];
			$this->atk = $setpart_data['atk'];
			$this->def = $setpart_data['def'];
			$this->vit = $setpart_data['vit'];
			$this->flux = $setpart_data['flux'];
			$this->res = $setpart_data['res'];
			$this->req_lvl = $setpart_data['req_lvl'];
			$this->type = $type;
		}
		
		public function getToolTipText() {
			return '<strong>'.$this->name.'</strong><br>Effet : '.$this->desc.'<br>Niveau requis : '.$this->req_lvl;
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
		
		public function getRes() {
			return $this->res;
		}
		
		public function getRequiredLevel() {
			return $this->req_lvl;
		}
		
		public function getType() {
			return $this->type;
		}
	}
?>
