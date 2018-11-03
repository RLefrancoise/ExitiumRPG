<?php

	include_once(__DIR__ . "/Item.class.php");
	include_once(__DIR__ . "/rpgconfig.php");
	
	class Ralz extends Item {
		
		private $value;
		
		public function __construct($ralz_data) {
			parent::__construct($ralz_data['id'], $ralz_data['name'], $ralz_data['desc'] . $ralz_data['ralz'], 0 , $ralz_data['img'], true);
			$this->value = $ralz_data['ralz'];
		}
		
		public function getValue() {
			return $this->value;
		}
		
		public function setValue($ralz) {
			($ralz >= 0) ? $this->value = $ralz : $this->value = 0;
		}
	}
?>