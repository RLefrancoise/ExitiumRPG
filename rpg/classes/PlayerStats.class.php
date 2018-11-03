<?php

class PlayerStats {
	protected $stats;
	
	//general
	public $max_ralz_own;
	public $max_ralz_buy;
	public $max_ralz_send;
	
	// pve battle
	public $pve_total_battles;
	public $pve_total_wins;
	public $pve_total_loses;
	public $pve_total_runs;
	
	// pvp battle
	public $pvp_total_battles;
	public $pvp_total_wins;
	public $pvp_total_loses;
	
	//war battle
	public $war_total_wins;
	public $war_total_loses;
	
	public function __construct($data) {
	
		$this->stats = $data;
		
		//general
		/*$this->max_ralz_own = $data['max_ralz_own'];
		$this->max_ralz_buy = $data['max_ralz_buy'];
		$this->max_ralz_send = $data['max_ralz_send'];
		
		//pve battle
		$this->pve_total_battles = $data['pve_total_battles'];
		$this->pve_total_wins = $data['pve_total_wins'];
		$this->pve_total_loses = $data['pve_total_loses'];
		$this->pve_total_runs = $data['pve_total_runs'];
		
		//pvp battle
		$this->pvp_total_battles = $data['pvp_total_battles'];
		$this->pvp_total_wins = $data['pvp_total_wins'];
		$this->pvp_total_loses = $data['pvp_total_loses'];
		
		//war battle
		$this->war_total_wins = $data['war_total_wins'];
		$this->war_total_loses = $data['war_total_loses'];*/
	}
	
	public function getStat($stat) {
		//if(!in_array($stat, $this->stats)) return false;
		if(!isset($this->stats[$stat])) return false;
		
		return $this->stats[$stat];
	}
	
	public function toHTMLString() {
		return "<strong>Argent</strong><br>
				Nombre maximum de Ralz possédés : {$this->stats['max_ralz_own']}<br>
				Nombre maximum de Ralz dépensés en une seule fois : {$this->stats['max_ralz_buy']}<br>
				Nombre maximum de Ralz envoyés à un joueur en une seule fois : {$this->stats['max_ralz_send']}<br>
				<br>
				<strong>PVE</strong><br>
				Nombre total de combats : {$this->stats['pve_total_battles']}<br>
				Nombre total de victoires : {$this->stats['pve_total_wins']}<br>
				Nombre total de défaites : {$this->stats['pve_total_loses']}<br>
				Nombre total de fuites : {$this->stats['pve_total_runs']}<br>
				<br>
				<strong>PVP</strong><br>
				Nombre total de combats : {$this->stats['pvp_total_battles']}<br>
				Nombre total de victoires : {$this->stats['pvp_total_wins']}<br>
				Nombre total de défaites : {$this->stats['pvp_total_loses']}<br>
				Nombre total de matchs nuls : {$this->stats['pvp_total_draws']}<br>
				<br>
				<strong>Bataille</strong><br>
				Nombre de batailles gagnées : {$this->stats['war_total_wins']}<br>
				Nombre de batailles perdues : {$this->stats['war_total_loses']}<br>
				<br>
				<strong>Système RPG</strong><br>
				Nombre de points de karma gagnés : {$this->stats['karma_points']}<br>
				Nombre de soins à l'hôtel : {$this->stats['inn_times']}<br>
				Nombre d'achats au marché noir : {$this->stats['buy_times']}<br>
				Nombre de quêtes réussies : {$this->stats['quests_number']}<br>
				Nombre de participations à un event : {$this->stats['event_times']}<br>
				Meilleure place en event : {$this->stats['event_best_rank']}<br>
				Nombre maximum de slots du casier utilisés : {$this->stats['warehouse_max_slots']}<br>";
	}
	
	public function setStat($stat, $value) {
		if(is_numeric($this->stats[$stat])) {
			if($this->stats[$stat] < $value)
				$this->stats[$stat] = $value;
		}
	}
}

class PlayerMonsterStats {
	private $monster_data;
	
	public function __construct($data) {
		$this->monster_data = $data;
	}
	
	public function getMonsterEncounter($monster_id) {
		return $this->monster_data[$monster_id]['encounter'];
	}
	
	public function getMonsterWins($monster_id) {
		return $this->monster_data[$monster_id]['wins'];
	}
	
	public function getMonsterLoses($monster_id) {
		return $this->monster_data[$monster_id]['loses'];
	}
	
	public function getMonsterRuns($monster_id) {
		return $this->monster_data[$monster_id]['runs'];
	}
}

?>
