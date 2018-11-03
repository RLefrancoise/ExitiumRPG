<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/../classes/Player.class.php");
	
	class RPGUsersPlayers {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getPlayerByUserId($user_id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_players, phpbb_users, rpg_users_players WHERE phpbb_users.user_id = ' . intval($user_id) . ' AND phpbb_users.user_id = rpg_users_players.user_id AND rpg_players.id = rpg_users_players.player_id';
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_players, phpbb_users, rpg_users_players WHERE phpbb_users.user_id = ? AND phpbb_users.user_id = rpg_users_players.user_id AND rpg_players.id = rpg_users_players.player_id');
			$req->execute(array($user_id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$player = new Player($info);
			return $player;
		}
		
		public static function getPlayerByPlayerId($player_id){
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_players, phpbb_users, rpg_users_players WHERE rpg_players.id = ' . intval($player_id) . ' AND phpbb_users.user_id = rpg_users_players.user_id AND rpg_players.id = rpg_users_players.player_id';
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			$req = $bdd->prepare('SELECT * FROM rpg_players, phpbb_users, rpg_users_players WHERE rpg_players.id = ? AND phpbb_users.user_id = rpg_users_players.user_id AND rpg_players.id = rpg_users_players.player_id');
			$req->execute(array($player_id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$player = new Player($info);
			return $player;
		}
		
		public static function associatePlayerToUser($user_id, $player_id) {
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_users_players WHERE user_id = ' . intval($user_id);
			$res = $bdd->query($sql);
			
			// user id not yet exists in table
			if($res->fetchColumn() == 0) {
				$bdd = &Database::getBDD();
				$req = $bdd->prepare("INSERT INTO rpg_users_players VALUES (?, ?)");
				$req->execute(array(intval($user_id), intval($player_id)));
			// user id exists, we update the player_id associated with it
			} else {
				$bdd = &Database::getBDD();
				$req = $bdd->prepare("UPDATE rpg_users_players SET player_id = ? WHERE user_id = ?");
				$req->execute(array($player_id, $user_id));
			}
			
			return ($req->rowCount() > 0);
		}
	}
?>