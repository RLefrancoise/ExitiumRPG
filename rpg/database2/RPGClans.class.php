<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	include_once(__DIR__ . "/RPGPlayers.class.php");
	include_once(__DIR__ . "/../classes/Clan.class.php");
	
	class RPGClans {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getClans() {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT id FROM rpg_clans ORDER BY name');
			$req->execute(array($id));
			
			$clans = array();
			
			while($info = $req->fetch()) {
				$clans[] = RPGClans::getClan($info['id']);
			}
			
			$req->closeCursor();
			
			return $clans;
		}
		
		public static function getClan($id){
			$bdd = &Database::getBDD();
			$sql = 'SELECT COUNT(*) FROM rpg_clans WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			
			$req = $bdd->prepare('SELECT * FROM rpg_clans WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$c = new Clan($info, RPGClans::getMembersOfClan($info['id']));
			return $c;
		}
		
		public static function getMembersOfClan($clan_id) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT member_id FROM rpg_clans_members WHERE clan_id = ?');
			$req->execute(array($clan_id));
			
			$members = array();
			
			while($info = $req->fetch()) {
				$members[] = RPGUsersPlayers::getPlayerByUserId($info['member_id']);
			}
			
			$req->closeCursor();
			
			return $members;
		}
		
		public static function getMessagesOfClan($clan_id) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT * FROM rpg_clans_messages WHERE clan_id = ? ORDER BY date');
			$req->execute(array($clan_id));
			
			$messages = array();
			
			while($info = $req->fetch()) {
				$info['user_name'] = RPGUsersPlayers::getPlayerByUserId($info['user_id'])->getName();
				$messages[] = $info;
			}
			
			$req->closeCursor();
			
			return $messages;
		}
		
		public static function getClanByUserId($user_id) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT clan_id FROM rpg_clans_members WHERE member_id = ?');
			$req->execute(array($user_id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			return RPGClans::getClan($info['clan_id']);
		}
		
		
		
		public static function postChatBoxMessage($clan_id, $user_id, $message) {
			if($message == "") return;
			
			date_default_timezone_set('Europe/Paris');
			$date = new DateTime('now', new DateTimeZone('Europe/Paris'));
			
			$bdd = &Database::getBDD();
			$req = $bdd->prepare("INSERT INTO rpg_clans_messages VALUES ('', ?, ?, ?, ?)");
			$req->execute(array($clan_id, $user_id, $date->getTimestamp(), $message));
		}
		
		public static function isClanMember($user_id, $clan_id) {
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_clans_members WHERE member_id = ' . intval($user_id) . ' AND clan_id = ' . intval($clan_id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return false;
			
			return true;
		}
		
		public static function isClanLeader($user_id, $clan_id) {
			$leader = false;
			
			$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT leader_id FROM rpg_clans WHERE id = ?');
			$req->execute(array($clan_id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			if(intval($info['leader_id']) == $user_id) $leader = true;
			
			return $leader;
		}
		
		
		public static function nameExists($name) {
			$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_clans WHERE name = ' . $name;
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return false;
			
			return true;
		}
		
		/*
		* Create a clan.
		* leader_id : user_id in phpbb_users DB
		*/
		public static function createClan($name, $desc, $img, $leader_id) {
			if(RPGClans::nameExists($name)) return;
			
			$bdd = &Database::getBDD();
			//create clan
			$req = $bdd->prepare("INSERT INTO rpg_clans VALUES ('', ?, ?, ?, ?)");
			$req->execute(array($name, $desc, $img, $leader_id));
			
			//set clan_id in rpg_players DB
			$req = $bdd->prepare('SELECT id FROM rpg_clans WHERE leader_id = ?');
			$req->execute(array($leader_id));
			
			$info = $req->fetch();
			$req->closeCursor();
			
			$clan_id = $info['id'];
			
			$player = RPGUsersPlayers::getPlayerByUserId($leader_id);
			RPGPlayers::setClanIdByPlayer($player, $clan_id);
			
			//add leader to clan members
			$req = $bdd->prepare("INSERT INTO rpg_clans_members VALUES (?, ?)");
			$req->execute(array($clan_id, $leader_id));
		}
		
		/*
		* Delete a clan according to its id.
		* NOTE : foreign keys are automaticely deleted or set to NULL
		*/
		public static function deleteClan($clan_id) {
			$bdd = &Database::getBDD();
			$req = $bdd->prepare("DELETE FROM rpg_clans WHERE id = ?");
			$req->execute(array($clan_id));
		}
	}
?>