<?php

try {
	//include
	require(__DIR__ . "/../config.php");
	require(__DIR__ . "/../rpg/classes/rpgconfig.php");
	
	//connection
    $strConnection = "mysql:host=$dbhost;dbname=$dbname";
    $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
    $pdo = new PDO($strConnection, $dbuser, $dbpasswd, $arrExtraParam);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	
	
	
	//script
	
	//prepate energy update request for performance
	$energy_request = $pdo->prepare('UPDATE rpg_players SET energy = :energy WHERE id = :id');
	
	//for each player
	$sql = 'SELECT DISTINCT p.id, p.energy, p.max_energy_bonus, p.inc_energy_bonus
			FROM phpbb_users AS u, rpg_users_players AS up, rpg_players AS p
			WHERE u.user_id = up.user_id
			AND up.player_id = p.id';
	$query = $pdo->query($sql);
	
	while($info = $query->fetch()) {
		
		$energy = (int) $info['energy'];
		$max_energy = MAX_ENERGY + (int) $info['max_energy_bonus'];
		if($energy >= $max_energy) continue;
		
		$new_energy = $energy + 2 + $info['inc_energy_bonus'];
		if($new_energy > $max_energy) $new_energy = $max_energy;
		
		$energy_request->bindValue(':energy', $new_energy, PDO::PARAM_INT);
		$energy_request->bindValue(':id', $info['id'], PDO::PARAM_INT);
		$energy_request->execute();
		
		if($energy_request->rowCount() === 0) {
			echo "[Error] Ligne " . __LINE__ . " : failed to update energy with player id = {$info['id']} and energy = $new_energy" . PHP_EOL;
		}
		else {
			echo 'Update energy of player ' . $info['id'] . PHP_EOL;
		}
	}
	
	$query->closeCursor();
	
	echo 'End of script';
	
}
catch(PDOException $e) {
    $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
    die($msg);
}

?>