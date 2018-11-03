<?php

class MonsterBook {
	private $player_id;
	private $data;
	
	public function __construct($player_id, $data) {
		$this->player_id = $player_id;
		
		$this->data = $data;
	}
	
	public function addEntry($monster_id, $part_id, $data) {
		$this->data[$monster_id] = array( $part_id	=> array(
			'encounters'	=>	$data['encounters'],
			'wins'			=>	$data['wins'],
			'loses'			=>	$data['loses'],
		));
	}
	
	public function getPlayerId() {
		return $this->player_id;
	}
	
	public function getMonsterStats($monster_id, $part_id) {
		if(!$this->hasMonsterStats($monster_id, $part_id)) return false;
		
		return $this->data[$monster_id][$part_id];
	}
	
	public function hasMonsterStats($monster_id, $part_id) {
		return array_key_exists($monster_id, $this->data) and array_key_exists($part_id, $this->data[$monster_id]);
	}
}

?>