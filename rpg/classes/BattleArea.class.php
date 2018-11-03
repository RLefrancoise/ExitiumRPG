<?php

	include_once(__DIR__ . "/../database/RPGBattleAreas.class.php");
	
	class BattleArea {
	
		private $id;
		private $name;
		private $desc;
		private $level;
		private $bgm;
		private $background;
		private $parts;
		
		public function __construct($area_data) {
			$this->id = $area_data['id'];
			$this->name = $area_data['name'];
			$this->desc = $area_data['desc'];
			$this->level = $area_data['level'];
			$this->bgm = $area_data['bgm'];
			$this->background = $area_data['background'];
			
			$parts = RPGBattleAreas::getAreaPartsByArea($this->id);
			if(!$parts) $this->parts = array();
			else $this->parts = $parts;
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
		
		public function getLevel() {
			return $this->level;
		}
		
		public function getBGM() {
			return $this->bgm;
		}
		
		public function getBackground() {
			return $this->background;
		}
		
		public function getAreaParts() {
			return $this->parts;
		}
		
		public function getAreaPartById($part_id) {
			for($i = 0 ; $i < count($this->parts) ; $i++) {
				if($this->parts[$i]->getId() == (int) $part_id) return $this->parts[$i];
			}
			
			return null;
		}
	}
	
	class BattleAreaPart {
		private $id;
		private $area_id;
		private $name;
		private $min_level;
		private $max_level;
		private $monsters;
		private $encounters;
		
		public function __construct($part_data) {
			$this->id = $part_data['id'];
			$this->area_id = $part_data['area_id'];
			$this->name = $part_data['name'];
			$this->min_level = $part_data['min_level'];
			$this->max_level = $part_data['max_level'];
			
			$this->monsters = RPGBattleAreas::getMonstersByAreaPart($this->id);
			if(!$this->monsters) $this->monsters = array();
			
			$this->encounters = array();
			
			foreach($this->monsters as $monster) {
				$rate = RPGBattleAreas::getEncounterRateByMonsterAndAreaPart($monster->getId(), $this->id);
				$this->encounters[] = ($rate ? $rate : 0); 
			}
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getAreaId() {
			return $this->area_id;
		}
		
		public function getName() {
			return $this->name;
		}
		
		public function getMinLevel() {
			return $this->min_level;
		}
		
		public function getMaxLevel() {
			return $this->max_level;
		}
		
		public function getMonsters() {
			return $this->monsters;
		}
		
		public function getEncounterRates() {
			return $this->encounters;
		}
	}

?>