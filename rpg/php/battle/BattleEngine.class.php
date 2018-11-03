<?php

include_once('./rpg/classes/AbstractBattle.class.php');
include_once('./rpg/classes/Player.class.php');

abstract class BattleEngine {

	protected $current_player;
	protected $player1;
	protected $player2;
	protected $battle;
	
	protected BattleEngine(Player &$current_player, Creature &$player1, Creature &$player2, AbstractBattle &battle) {
		$this->current_player = $current_player;
		$this->player1 = $player1;
		$this->player2 = $player2;
		$this->battle = $battle;
	}
	
	public abstract function playTurn($player1_data, $player2_data);
	public abstract check_battle_effects($player_nb, Creature &$actor, Creature &$target, AbstractBattle &$battle);
	
	public function getCurrentPlayer() {
		return $this->current_player;
	}
	
	public function getPlayer1() {
		return $this->player1;
	}
	
	public function getPlayer2() {
		return $this->player2;
	}
	
	public function getBattle() {
		return $this->battle;
	}
}

?>