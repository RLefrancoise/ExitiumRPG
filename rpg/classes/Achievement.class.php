<?php

include_once(__DIR__ . '/../database/RPGAchievements.class.php');
include_once(__DIR__ . '/../database/RPGMonsterBooks.class.php');
include_once(__DIR__ . '/../database/RPGBattleAreas.class.php');
include_once(__DIR__ . '/Player.class.php');

class AchievementCategory {
	private $id;
	private $name;
	
	public function __construct($data) {
		$this->id = $data['id'];
		$this->name = $data['name'];
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
}

class Achievement {

	private $id;
	private $name;
	private $condition;
	private $category;
	private $hide_condition;
	private $script;
	
	public function __construct($data) {
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->condition = $data['condition_desc'];
		$this->category = RPGAchievements::getCategory($data['category_id']);
		$this->hide_condition = $data['hide_condition'];
		$this->script = $data['script'];
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getCondition() {
		return $this->condition;
	}
	
	public function getCategory() {
		return $this->category;
	}
	
	public function hideCondition() {
		return $this->hide_condition;
	}
	
	public function canUnlock($user_data, Player &$player) {
		global $db;
		
		$unlocked = false;
		
		//if(!file_exists(__DIR__ . '/../php/achievements/' . $this->script . '.php')) return false;
		
		include_once(__DIR__ . '/../php/achievements/' . $this->script . '.php');
		
		return $unlocked;
	}
}

?>