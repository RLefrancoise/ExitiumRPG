<?php

	include_once("./AbstractBattleView.class.php");
	
	public class PVEBattleView implements AbstractBattleView {
		
		private $player_id;
		private $monster_id;
		
		public __construct($pid, $mid) {
			$this->player_id = $pid;
			$this->monster_id = $mid;
		}
		
		public display() {
		
		}
	}
	
?>