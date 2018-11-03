<?php
	include_once(__DIR__ . "/Item.class.php");
	include_once(__DIR__ . "/rpgconfig.php");
				
	class Weapon{
		
		//private static 
										
		protected $id;
		protected $name;
		protected $desc;
		private $atk;
		private $acc;
		private $crit;
		private $grade;
		
		public function __construct($weapon_data) {
			$this->id = $weapon_data['id'];
			$this->name = $weapon_data['name'];
			$this->desc = $weapon_data['desc'];
			$this->setGrade($weapon_data['grade']);
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
		
		public function getAttack() {
			return $this->atk;
		}
		
		public function getAccuracy() {
			return $this->acc;
		}
		
		public function getCritical() {
			return $this->crit;
		}
		
		public function getGrade() {
			return $this->grade;
		}
		
		/*public function getGradeLetter() {
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
		}*/
		
		public static function compareGrade($grade1, $grade2) {
			$array = array(WEAPON_GRADE_D, WEAPON_GRADE_C, WEAPON_GRADE_B, WEAPON_GRADE_A, WEAPON_GRADE_S, WEAPON_GRADE_SS, WEAPON_GRADE_X);
			
			$pos1 = 0;
			$pos2 = 0;
			
			for($i = 0 ; $i < count($array) ; $i++) {
				if($grade1 === $array[$i]) {
					$pos1 = $i;
				}
				if($grade2 === $array[$i]) {
					$pos2 = $i;
				}
			}
			
			if($pos1 < $pos2) return -1;
			if($pos1 == $pos2) return 0;
			if($pos1 > $pos2) return 1;
		}
		
		public function isUnderGrade($grade) {
			switch($this->grade) {
				case WEAPON_GRADE_D:
					if($grade !== WEAPON_GRADE_D)
						return true;
					else
						return false;
				case WEAPON_GRADE_C:
					if($grade === WEAPON_GRADE_B or $grade === WEAPON_GRADE_A or $grade === WEAPON_GRADE_S or $grade === WEAPON_GRADE_SS or $grade === WEAPON_GRADE_X)
						return true;
					else
						return false;
				case WEAPON_GRADE_B:
					if($grade === WEAPON_GRADE_A or $grade === WEAPON_GRADE_S or $grade === WEAPON_GRADE_SS or $grade === WEAPON_GRADE_X)
						return true;
					else
						return false;
				case WEAPON_GRADE_A:
					if($grade === WEAPON_GRADE_S or $grade === WEAPON_GRADE_SS or $grade === WEAPON_GRADE_X)
						return true;
					else
						return false;
				case WEAPON_GRADE_S:
					if($grade === WEAPON_GRADE_SS or $grade === WEAPON_GRADE_X)
						return true;
					else
						return false;
				case WEAPON_GRADE_SS:
					if($grade === WEAPON_GRADE_X)
						return true;
					else
						return false;
				case WEAPON_GRADE_X:
					return false;
				default:
					return false;
			}
		}
		
		public function setGrade($grade) {
		
			global $_WEAPON_ATTACKS, $_WEAPON_ACCURACIES, $_WEAPON_CRITICALS;
			
			switch($grade){
				case WEAPON_GRADE_D:
				case WEAPON_GRADE_C:
				case WEAPON_GRADE_B:
				case WEAPON_GRADE_A:
				case WEAPON_GRADE_S:
				case WEAPON_GRADE_SS:
				case WEAPON_GRADE_X:
					$this->grade 	= $grade;
					$this->atk 		= $_WEAPON_ATTACKS[$grade];
					$this->acc 		= $_WEAPON_ACCURACIES[$grade];
					$this->crit 	= $_WEAPON_CRITICALS[$grade];
					break;
			}
		}
	}
	
?>