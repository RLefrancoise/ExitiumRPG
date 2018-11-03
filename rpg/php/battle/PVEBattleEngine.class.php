<?php

include_once('./BattleEngine.class.php');

class PVEBattleEngine extends BattleEngine {

	public PVEBattleEngine(Player &$current_player, Creature &$player1, Creature &$player2, AbstractBattle &battle) {
		parent($current_player, $player1, $player2, $battle);
	}
	
	public function playTurn($player1_data, $player2_data) {
		$action1 = $player1_data['action'];
		$skill1 = $player1_data['skill'];
		$item1 = $player1_data['item'];
	}
}

?>
