<?php
	
	abstract class Item {
	
		protected $id;
		protected $name;
		protected $desc;
		protected $price;
		protected $img;
		protected $one_per_slot;
		
		protected function __construct($id, $name, $desc, $price, $img, $one_per_slot) {
			$this->id = $id;
			$this->name = $name;
			$this->desc = $desc;
			$this->price = $price;
			$this->img = $img;
			$this->one_per_slot = $one_per_slot;
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
		
		public function getToolTipText() {
			return '<strong>'.$this->name.'</strong><br>'.$this->desc;
		}
		
		/* Returns description with name
		*/
		public function getFullDescription() {
			return '<strong>'.$this->name.'</strong><br>'.$this->desc;
		}
		
		public function getPrice() {
			return $this->price;
		}
		
		public function getIcon() {
			return $this->img;
		}
		
		public function isOnePerSlot() {
			return $this->one_per_slot;
		}
	}
?>