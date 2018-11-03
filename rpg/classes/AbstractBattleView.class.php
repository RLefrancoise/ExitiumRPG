<?php

	class AbstractBattle {
	
		private $token;
		private $bgm;
		
		protected function __construct($token, $bgm) {
			$this->token = $token;
			$this->bgm = $bgm;
		}
	}
	
	class PVEBattle extends AbstractBattle {
	
		private $player_id;
		private $monster_id;
		
		public function __construct($battle_data) {
			parent::__construct($battle_data['token'], $battle_data['bgm']);
			$this->player_id = $battle_data['player_id'];
			$this->monster_id = $battle_data['monster_id'];
		}
	}
?>