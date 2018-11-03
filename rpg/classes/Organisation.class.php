<?php

	class Organisation {
		
		private $id;
		private $name;
		
		public function __construct($orga_data) {
			$this->id = $orga_data['id'];
			$this->name = $orga_data['name'];
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getName() {
			return $this->name;
		}
	}
?>