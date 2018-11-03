<?php
	include(__DIR__ . "/rpgconfig.php");
	
	class Upgrade {
		
		private $id;
		private $grade;
		private $success;
		private $price;
		
		public function __construct($upgrade_data) {
			$this->id = $upgrade_data['id'];
			$this->grade = $upgrade_data['grade'];
			$this->success = $upgrade_data['success_rate'];
			$this->price = $upgrade_data['price'];
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getName() {
			return 'Amélioration de l\'arme grade ' . $this->grade;
		}
		
		public function getDescription() {
			return 'Chance de succès : ' . $this->success . '%';
		}
		
		public function getGrade() {
			return $this->grade;
		}
		
		public function getSuccessRate() {
			return $this->success;
		}
		
		public function getPrice() {
			return $this->price;
		}
		
		public function getGradeLetter() {
			switch($this->grade){
				case WEAPON_GRADE_D:
					return 'D';
				case WEAPON_GRADE_C:
					return 'C';
				case WEAPON_GRADE_B:
					return 'B';
				case WEAPON_GRADE_A:
					return 'A';
				case WEAPON_GRADE_S:
					return 'S';
				case WEAPON_GRADE_SS:
					return 'SS';
			}
		}
	}
?>
