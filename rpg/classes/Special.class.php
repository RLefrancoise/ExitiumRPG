<?php

	include_once(__DIR__ . "/Item.class.php");
	include_once(__DIR__ . "/rpgconfig.php");
	
	class Special extends Item {
		
		private $effect;
		
		public function __construct($special_data) {
			parent::__construct($special_data['id'], $special_data['name'], $special_data['descr'], $special_data['price'] , $special_data['img'], false);
			$this->effect = $special_data['effect'];
		}
		
		public function getEffect() {
			return $this->effect;
		}
	}
?>