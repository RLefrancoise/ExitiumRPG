<?php
	include_once(__DIR__ . "/rpgconfig.php");
	
	abstract class AbstractBattle {
	
		protected $token;
		protected $player_id;
		protected $opponent_id;
		protected $turn;
		protected $bgm;
		protected $background;
		protected $player1_skills;
		protected $player2_skills;
		protected $player1_active_skills;
		protected $player2_active_skills;
		protected $player1_buffs;
		protected $player2_buffs;
		protected $player1_active_orbs;
		protected $player2_active_orbs;
		
		protected function __construct($token, $player_id, $opponent_id, $turn, $bgm, $background, $player1_active_skills, $player2_active_skills, $player1_buffs, $player2_buffs, $player1_active_orbs, $player2_active_orbs) {
			$this->token = $token;
			$this->player_id = $player_id;
			$this->opponent_id = $opponent_id;
			$this->turn = $turn;
			$this->bgm = $bgm;
			$this->background = $background;
			
			$this->player1_skills = array();
			$this->player2_skills = array();
			
			$this->setPlayer1ActiveSkills($player1_active_skills);
			$this->setPlayer2ActiveSkills($player2_active_skills);
			
			$this->setPlayer1Buffs($player1_buffs);
			$this->setPlayer2Buffs($player2_buffs);
			
			$this->setPlayer1ActiveOrbs($player1_active_orbs);
			$this->setPlayer2ActiveOrbs($player2_active_orbs);
		}
		
		public function getToken() {
			return $this->token;
		}
		
		public function getPlayerId() {
			return $this->player_id;
		}
		
		public function getOpponentId() {
			return $this->opponent_id;
		}
		
		public function getTurn() {
			return $this->turn;
		}
		
		public function getBGM() {
			return $this->bgm;
		}
		
		public function getBackground() {
			return $this->background;
		}
		
		public function getPlayer1Skills() {
			return $this->player_skills;
		}
		
		public function getPlayer2Skills() {
			return $this->player2_skills;
		}
		
		public function getLastTurnOfPlayer1Skill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player1_skills)) return 0;
			
			return $this->player1_skills[$type];
		}
		
		public function getLastTurnOfPlayer2Skill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player2_skills)) return 0;
			
			return $this->player2_skills[$type];
		}
		
		public function getPlayer1ActiveSkills() {
			return $this->player1_active_skills;
		}
		
		public function getPlayer2ActiveSkills() {
			return $this->player2_active_skills;
		}
		
		public function getPlayer1Buff($type) {
			$buff = 0;
			
			foreach($this->player1_buffs as $skill => $data) {
				if($data['start'] + $data['duration'] < $this->getTurn()) continue;
				
				if($data['type'] == $type) $buff += (int) $data['value'];
			}
			
			return $buff;
		}
		
		public function getPlayer2Buff($type) {
			$buff = 0;
			
			foreach($this->player2_buffs as $skill => $data) {
				if($data['start'] + $data['duration'] < $this->getTurn()) continue;
				
				if($data['type'] == $type) $buff += (int) $data['value'];
			}
			
			return $buff;
		}
		
		public function player1OrbIsActive($slot) {
			return (bool) $this->player1_active_orbs[$slot];
		}
		
		public function player2OrbIsActive($slot) {
			return (bool) $this->player2_active_orbs[$slot];
		}
		
		public function player1SkillsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player1_skills as $type => $turn) {
				$str .= "$type,$turn";
				$i++;
				if($i < count($this->player1_skills)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player2SkillsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player2_skills as $type => $turn) {
				$str .= "$type,$turn";
				$i++;
				if($i < count($this->player2_skills)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player1ActiveSkillsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player1_active_skills as $type => $info_array) {
				$str .= "$type,{$info_array['start']},{$info_array['duration']}";
				$i++;
				if($i < count($this->player1_active_skills)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player2ActiveSkillsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player2_active_skills as $type => $info_array) {
				$str .= "$type,{$info_array['start']},{$info_array['duration']}";
				$i++;
				if($i < count($this->player2_active_skills)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player1BuffsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player1_buffs as $skill => $info_array) {
				$str .= "$skill,{$info_array['start']},{$info_array['duration']},{$info_array['type']},{$info_array['value']}";
				$i++;
				if($i < count($this->player1_buffs)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player2BuffsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player2_buffs as $skill => $info_array) {
				$str .= "$skill,{$info_array['start']},{$info_array['duration']},{$info_array['type']},{$info_array['value']}";
				$i++;
				if($i < count($this->player2_buffs)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player1ActiveOrbsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player1_active_orbs as $slot => $active) {
				$str .= "$slot,$active";
				$i++;
				if($i < count($this->player1_active_orbs)) $str .= "|";
			}
			
			return $str;
		}
		
		public function player2ActiveOrbsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player2_active_orbs as $slot => $active) {
				$str .= "$slot,$active";
				$i++;
				if($i < count($this->player2_active_orbs)) $str .= "|";
			}
			
			return $str;
		}
		
		public function setTurn($turn) {
			$this->turn = $turn;
		}
		
		public function setPlayer1Skills($skills_string) {
			$this->player1_skills = array();
			
			if($skills_string != "") {
				$player_skills_data = explode("|", $skills_string);
				foreach($player_skills_data as $skill_data) {
					$data = explode(",", $skill_data);
					$this->player1_skills[$data[0]] = $data[1];
				}
			}
		}
		
		public function setPlayer2Skills($skills_string) {
			$this->player2_skills = array();
			
			if($skills_string != "") {
				$player_skills_data = explode("|", $skills_string);
				foreach($player_skills_data as $skill_data) {
					$data = explode(",", $skill_data);
					$this->player2_skills[$data[0]] = $data[1];
				}
			}
		}
		
		public function setPlayer1ActiveSkills($skills_string) {
			$this->player1_active_skills = array();
			
			if($skills_string != "") {
				$player_skills_data = explode("|", $skills_string);
				foreach($player_skills_data as $skill_data) {
					$data = explode(",", $skill_data);
					$this->player1_active_skills[$data[0]] = array( 'start'	=> $data[1], 'duration'	=> $data[2],);
				}
			}
		}
		
		public function setPlayer2ActiveSkills($skills_string) {
			$this->player2_active_skills = array();
			
			if($skills_string != "") {
				$player_skills_data = explode("|", $skills_string);
				foreach($player_skills_data as $skill_data) {
					$data = explode(",", $skill_data);
					$this->player2_active_skills[$data[0]] = array( 'start'	=> $data[1], 'duration'	=> $data[2],);
				}
			}
		}
		
		public function setPlayer1Buffs($buffs_string) {
			$this->player1_buffs = array();
			
			if($buffs_string != "") {
				$player_buffs_data = explode("|", $buffs_string);
				foreach($player_buffs_data as $buffs_data) {
					$data = explode(",", $buffs_data);
					$this->player1_buffs[$data[0]] = array( 'start'	=> $data[1], 'duration'	=> $data[2], 'type' => $data[3], 'value' => $data[4],);
				}
			}
		}
		
		public function setPlayer2Buffs($buffs_string) {
			$this->player2_buffs = array();
			
			if($buffs_string != "") {
				$player_buffs_data = explode("|", $buffs_string);
				foreach($player_buffs_data as $buffs_data) {
					$data = explode(",", $buffs_data);
					$this->player2_buffs[$data[0]] = array( 'start'	=> $data[1], 'duration'	=> $data[2], 'type' => $data[3], 'value' => $data[4],);
				}
			}
		}
		
		public function setPlayer1ActiveOrbs($orbs_string) {
			$this->player1_active_orbs = array(1 => true, 2 => true, 3 => true, 4 => true,);
			
			if($orbs_string != "") {
				$player_orbs_data = explode("|", $orbs_string);
				foreach($player_orbs_data as $orbs_data) {
					$data = explode(",", $orbs_data);
					$this->player1_active_orbs[$data[0]] = $data[1];
				}
			}
		}
		
		public function setPlayer2ActiveOrbs($orbs_string) {
			$this->player2_active_orbs = array(1 => true, 2 => true, 3 => true, 4 => true,);
			
			if($orbs_string != "") {
				$player_orbs_data = explode("|", $orbs_string);
				foreach($player_orbs_data as $orbs_data) {
					$data = explode(",", $orbs_data);
					$this->player2_active_orbs[$data[0]] = $data[1];
				}
			}
		}
		
		public function setPlayer1ActiveOrb($slot, $active) {
			if($slot < 1 or $slot > 4) return false;
			
			$this->player1_active_orbs[$slot] = $active;
			
			return true;
		}
		
		public function setPlayer2ActiveOrb($slot, $active) {
			if($slot < 1 or $slot > 4) return false;
			
			$this->player2_active_orbs[$slot] = $active;
			
			return true;
		}
		
		public function addPlayer1Skill($type, $turn) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			
			$this->player1_skills[$type] = $turn;
			
			return true;
		}
		
		public function addPlayer2Skill($type, $turn) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			
			$this->player2_skills[$type] = $turn;
			
			return true;
		}
		
		public function addPlayer1ActiveSkill($type, $info_array) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!is_array($info_array)) return false;
			
			$this->player1_active_skills[$type] = $info_array;
			
			return true;
		}
		
		public function addPlayer2ActiveSkill($type, $info_array) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!is_array($info_array)) return false;
			
			$this->player2_active_skills[$type] = $info_array;
			
			return true;
		}
		
		public function addPlayer1Buff($skill, $info_array) {
			global $_SKILLS_DATA, $_BUFFS;
			
			if(!array_key_exists($skill, $_SKILLS_DATA)) return false;
			if(!is_array($info_array)) return false;
			if(!in_array($info_array['type'], $_BUFFS)) return false;
			
			$this->player1_buffs[$skill] = $info_array;
			
			return true;
		}
		
		public function addPlayer2Buff($skill, $info_array) {
			global $_SKILLS_DATA, $_BUFFS;
			
			if(!array_key_exists($skill, $_SKILLS_DATA)) return false;
			if(!is_array($info_array)) return false;
			if(!in_array($info_array['type'], $_BUFFS)) return false;
			
			$this->player2_buffs[$skill] = $info_array;
			
			return true;
		}
		
		public function removePlayer1Skill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player1_skills)) return false;
			
			unset($this->player1_skills[$type]);
		}
		
		public function removePlayer2Skill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player2_skills)) return false;
			
			unset($this->player2_skills[$type]);
		}
		
		public function removePlayer1ActiveSkill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player1_active_skills)) return false;
			
			unset($this->player1_active_skills[$type]);
		}
		
		public function removePlayer2ActiveSkill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player2_active_skills)) return false;
			
			unset($this->player2_active_skills[$type]);
		} 
		
		public function resetPlayer1ActiveSkills() {
			$this->player1_active_skills = array();
		}
		
		public function resetPlayer2ActiveSkills() {
			$this->player2_active_skills = array();
		}
	}
	
	class EventBattle extends AbstractBattle {
		private $monster_hp;
		private $monster_fp;
		private $in_event;
		private $is_dead;
		private $damage_given;
		private $damage_received;
		private $forum_id;
		private $topic_id;
		
		public function __construct($battle_data) {
			parent::__construct($battle_data['token'], $battle_data['player_id'], $battle_data['monster_id'], $battle_data['turn'], $battle_data['bgm'], $battle_data['background'], $battle_data['player_active_skills'], $battle_data['monster_active_skills'], $battle_data['player_buffs'], $battle_data['monster_buffs'], $battle_data['player_active_orbs'], $battle_data['monster_active_orbs']);
		
			$this->setPlayer1Skills($battle_data['player_skills']);
			$this->setPlayer2Skills($battle_data['monster_skills']);
			
			$this->monster_hp = $battle_data['monster_hp'];
			$this->monster_fp = $battle_data['monster_fp'];
			
			$this->in_event = $battle_data['in_event'];
			$this->is_dead = $battle_data['is_dead'];
			
			$this->damage_given = $battle_data['total_damage_given'];
			$this->damage_received = $battle_data['total_damage_received'];
			
			$this->forum_id = $battle_data['forum_id'];
			$this->topic_id = $battle_data['topic_id'];
		}
		
		public function getMonsterHP() {
			return $this->monster_hp;
		}
		
		public function getMonsterFP() {
			return $this->monster_fp;
		}
		
		public function playerIsInEvent() {
			return $this->in_event;
		}
		
		public function playerIsDead() {
			return $this->is_dead;
		}
		
		public function getDamageGiven() {
			return $this->damage_given;
		}
		
		public function getDamageReceived() {
			return $this->damage_received;
		}
		
		public function getForumId() {
			return $this->forum_id;
		}
		
		public function getTopicId() {
			return $this->topic_id;
		}
		
		public function setMonsterHP($hp) {
			if($hp < 0) return;
			
			$this->monster_hp = $hp;
		}
		
		public function setMonsterFP($fp) {
			if($fp < 0) $fp = 0;
			
			$this->monster_fp = $fp;
		}
		
		public function setPlayerInEvent($b) {
			$this->in_event = $b;
		}
		
		public function setPlayerIsDead($b) {
			$this->is_dead = $b;
		}
		
		public function incrementDamageGiven($damage) {
			$this->damage_given += $damage;
		}
		public function incrementDamageReceived($damage) {
			$this->damage_received += $damage;
		}
	}
	
	class QuestBattle extends AbstractBattle {
		private $monster_hp;
		private $monster_fp;
		private $in_battle;
		private $is_dead;
		private $forum_id;
		private $topic_id;
		private $is_over;
		
		public function __construct($battle_data) {
			parent::__construct($battle_data['token'], $battle_data['player_id'], $battle_data['monster_id'], $battle_data['turn'], $battle_data['bgm'], $battle_data['background'], $battle_data['player_active_skills'], $battle_data['monster_active_skills'], $battle_data['player_buffs'], $battle_data['monster_buffs'], $battle_data['player_active_orbs'], $battle_data['monster_active_orbs']);
		
			$this->setPlayer1Skills($battle_data['player_skills']);
			$this->setPlayer2Skills($battle_data['monster_skills']);
			
			$this->monster_hp = $battle_data['monster_hp'];
			$this->monster_fp = $battle_data['monster_fp'];
			
			$this->in_battle = $battle_data['in_battle'];
			$this->is_dead = $battle_data['is_dead'];
			
			$this->forum_id = $battle_data['forum_id'];
			$this->topic_id = $battle_data['topic_id'];
			
			$this->is_over = $battle_data['is_over'];
		}
		
		public function getMonsterHP() {
			return $this->monster_hp;
		}
		
		public function getMonsterFP() {
			return $this->monster_fp;
		}
		
		public function playerIsInBattle() {
			return $this->in_battle;
		}
		
		public function playerIsDead() {
			return $this->is_dead;
		}
		
		public function getForumId() {
			return $this->forum_id;
		}
		
		public function getTopicId() {
			return $this->topic_id;
		}
		
		public function isOver() {
			return $this->is_over;
		}
		
		public function setMonsterHP($hp) {
			if($hp < 0) return;
			
			$this->monster_hp = $hp;
		}
		
		public function setMonsterFP($fp) {
			if($fp < 0) $fp = 0;
			
			$this->monster_fp = $fp;
		}
		
		public function setPlayerInBattle($b) {
			$this->in_battle = $b;
		}
		
		public function setPlayerIsDead($b) {
			$this->is_dead = $b;
		}
		
		public function setBattleIsOver($b) {
			$this->is_over = $b;
		}
	}
	
	class PVPBattle extends AbstractBattle {
		private $player1_hp;
		private $player2_hp;
		private $player1_fp;
		private $player2_fp;
		private $player1_in_battle;
		private $player2_in_battle;
		private $player1_bgm;
		private $player2_bgm;
		private $is_started;
		private $is_over;
		private $turn_time;
		private $player1_last_active;
		private $player2_last_active;
		
		public function __construct($battle_data) {
			parent::__construct($battle_data['token'], $battle_data['player1_id'], $battle_data['player2_id'], $battle_data['turn'], '', '', $battle_data['player1_active_skills'], $battle_data['player2_active_skills'], $battle_data['player1_buffs'], $battle_data['player2_buffs'], $battle_data['player1_active_orbs'], $battle_data['player2_active_orbs']);
			
			$this->player1_hp = $battle_data['player1_hp'];
			$this->player2_hp = $battle_data['player2_hp'];
			$this->player1_fp = $battle_data['player1_fp'];
			$this->player2_fp = $battle_data['player2_fp'];
			
			$this->setPlayer1Skills($battle_data['player1_skills']);
			$this->setPlayer2Skills($battle_data['player2_skills']);
			
			$this->player1_in_battle = $battle_data['player1_in_battle'];
			$this->player2_in_battle = $battle_data['player2_in_battle'];
			
			$this->player1_bgm = $battle_data['player1_bgm'];
			$this->player2_bgm = $battle_data['player2_bgm'];
			
			$this->is_started = $battle_data['is_started'];
			$this->is_over = $battle_data['is_over'];
			$this->turn_time = $battle_data['turn_time'];
			
			$this->player1_last_active = $battle_data['player1_last_active'];
			$this->player2_last_active = $battle_data['player2_last_active'];
		}
		
		public function getPlayer1HP() {
			return $this->player1_hp;
		}
		
		public function getPlayer2HP() {
			return $this->player2_hp;
		}
		
		public function getPlayer1FP() {
			return $this->player1_fp;
		}
		
		public function getPlayer2FP() {
			return $this->player2_fp;
		}
		
		public function player1InBattle() {
			return $this->player1_in_battle;
		}
		
		public function player2InBattle() {
			return $this->player2_in_battle;
		}
		
		public function getPlayer1BGM() {
			return $this->player1_bgm;
		}
		
		public function getPlayer2BGM() {
			return $this->player2_bgm;
		}
		
		public function isStarted() {
			return $this->is_started;
		}
		
		public function isOver() {
			return $this->is_over;
		}
		
		public function getTurnTime() {
			return $this->turn_time;
		}
		
		public function getPlayer1LastActiveTurn() {
			return $this->player1_last_active;
		}
		
		public function getPlayer2LastActiveTurn() {
			return $this->player2_last_active;
		}
	}
	
	class PVEBattle extends AbstractBattle {
	
		private $monster_hp;
		private $monster_fp;
		private $player_skills;
		private $monster_skills;
		private $area_part_id;
		
		public function __construct($battle_data) {
			parent::__construct($battle_data['token'], $battle_data['player_id'], $battle_data['monster_id'], $battle_data['turn'], $battle_data['bgm'], $battle_data['background'], $battle_data['player_active_skills'], $battle_data['monster_active_skills'], $battle_data['player_buffs'], $battle_data['monster_buffs'], $battle_data['player_active_orbs'], $battle_data['monster_active_orbs']);
			
			$this->monster_hp = $battle_data['monster_hp'];
			$this->monster_fp = $battle_data['monster_fp'];
			
			$this->setPlayer1Skills($battle_data['player_skills']);
			$this->setPlayer2Skills($battle_data['monster_skills']);
			
			$this->area_part_id = $battle_data['area_part_id'];
			
			//$this->setPlayerSkills($battle_data['player_skills']);
			//$this->setMonsterSkills($battle_data['monster_skills']);
		}
		
		public function getMonsterHP() {
			return $this->monster_hp;
		}
		
		public function getMonsterFP() {
			return $this->monster_fp;
		}
		
		public function getPlayerSkills() {
			return $this->player_skills;
		}
		
		public function getMonsterSkills() {
			return $this->monster_skills;
		}
		
		public function getAreaPartId() {
			return $this->area_part_id;
		}
		
		/*public function getLastTurnOfPlayerSkill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player_skills)) return 0;
			
			return $this->player_skills[$type];
		}
		
		public function getLastTurnOfMonsterSkill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->monster_skills)) return 0;
			
			return $this->monster_skills[$type];
		}
		
		public function playerSkillsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->player_skills as $type => $turn) {
				$str .= "$type,$turn";
				$i++;
				if($i < count($this->player_skills)) $str .= "|";
			}
			
			return $str;
		}
		
		public function monsterSkillsToString() {
			$str = '';
			
			$i = 0;
			foreach($this->monster_skills as $type => $turn) {
				$str .= "$type,$turn";
				$i++;
				if($i < count($this->monster_skills)) $str .= "|";
			}
			
			return $str;
		}*/
		
		public function setMonsterHP($hp) {
			if($hp < 0) return;
			
			$this->monster_hp = $hp;
		}
		
		public function setMonsterFP($fp) {
			if($fp < 0) $fp = 0;
			
			$this->monster_fp = $fp;
		}
		
		/*public function setPlayerSkills($skills_string) {
			$this->player_skills = array();
			
			if($skills_string != "") {
				$player_skills_data = explode("|", $skills_string);
				foreach($player_skills_data as $skill_data) {
					$data = explode(",", $skill_data);
					$this->player_skills[$data[0]] = $data[1];
				}
			}
		}
		
		public function setMonsterSkills($skills_string) {
			$this->monster_skills = array();
			
			if($skills_string != "") {
				$monster_skills_data = explode("|", $skills_string);
				foreach($monster_skills_data as $skill_data) {
					$data = explode(",", $skill_data);
					$this->monster_skills[$data[0]] = $data[1];
				}
			}
		}
		
		public function addPlayerSkill($type, $turn) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			
			$this->player_skills[$type] = $turn;
			
			return true;
		}
		
		public function addMonsterSkill($type, $turn) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			
			$this->monster_skills[$type] = $turn;
			
			return true;
		}
		
		public function removePlayerSkill($type) {
			global $_SKILLS_DATA;
			
			if(!array_key_exists($type, $_SKILLS_DATA)) return false;
			if(!array_key_exists($type, $this->player_skills)) return false;
			
			unset($this->player_skills[$type]);
		}*/
	}
?>