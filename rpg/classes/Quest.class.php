<?php

include_once(__DIR__ . '/../php/numeric_functions.php');

abstract class Quest {
	private $id;
	private $name;
	private $desc;
	private $type;
	private $date;
	private $available;
	private $is_unique;
	private $posts_number;
	private $forum_id;
	private $rewards;
	
	private $bgm;
	private $background;
	private $xp;
	private $ralz;
	
	public function __construct($quest_data) {
		$this->id = $quest_data['id'];
		$this->name = $quest_data['name'];
		$this->desc = $quest_data['descr'];
		$this->type = $quest_data['type'];
		$this->date = $quest_data['date'];
		$this->available = $quest_data['available'];
		$this->is_unique = $quest_data['is_unique'];
		$this->posts_number = $quest_data['posts_number'];
		$this->forum_id = $quest_data['forum_id'];
		$this->rewards = $quest_data['rewards'];
		
		$this->bgm = $quest_data['bgm'];
		$this->background = $quest_data['background'];
		$this->xp = $quest_data['xp'];
		$this->ralz = $quest_data['ralz'];
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDesc() {
		return $this->desc;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function isAvailable() {
		return $this->available;
	}
	
	public function isUnique() {
		return $this->is_unique;
	}
	
	public function getRequiredPosts() {
		return $this->posts_number;
	}
	
	public function getForumId() {
		return $this->forum_id;
	}
	
	public function getRewards() {
		return $this->rewards;
	}
	
	public function getBGM() {
		return $this->bgm;
	}
	
	public function getBackground() {
		return $this->background;
	}
	
	public function getXp() {
		return $this->xp;
	}
	
	public function getRalz() {
		return $this->ralz;
	}
}

class BattleQuest extends Quest {
	
	private $battle_token;
	private $monster_id;
	//private $battle;
	
	public function __construct($quest_data) {
		parent::__construct($quest_data);
		
		$this->battle_token = $quest_data['battle_token'];
		
		$this->monster_id = $quest_data['monster_id'];
		//$this->battle = $quest_data['battle'];
	}
	
	public function getBattleToken() {
		return $this->battle_token;
	}
	
	public function getMonsterId() {
		return $this->monster_id;
	}
}

class SurvivalQuest extends Quest {

	private $monster_ids;
	
	public function __construct($quest_data) {
		parent::__construct($quest_data);
		
		$this->monster_ids = $quest_data['monster_ids'];
	}
	
	public function getMonsterIds() {
		return $this->monster_ids;
	}
}


class QuestRiddle {
	private $id;
	private $name;
	private $desc;
	private $answer;
	private $quest_id;
	
	public function __construct($riddle_data) {
		$this->id = $riddle_data['id'];
		$this->name = $riddle_data['name'];
		$this->descr = $riddle_data['descr'];
		$this->answer = $riddle_data['answer'];
		$this->quest_id = $riddle_data['quest_id'];
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDesc() {
		return $this->descr;
	}
	
	public function getAnswer() {
		return $this->answer;
	}
	
	public function getQuestId() {
		return $this->quest_id;
	}
}


class RiddleQuest extends Quest {
	private $riddles;
	
	public function __construct($quest_data) {
		parent::__construct($quest_data);
		
		$this->riddles = $quest_data['riddles'];
	}
	
	public function getRiddles() {
		return $this->riddles;
	}
	
	public function chooseRiddle() {
		if(!is_array($this->riddles)) return false;
		if(count($this->riddles) == 0) return false;
		
		$random = mt_rand(0, count($this->riddles) - 1);
		
		return $this->riddles[$random];
	}
}

?>
