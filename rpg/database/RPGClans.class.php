<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	include_once(__DIR__ . "/RPGPlayers.class.php");
	include_once(__DIR__ . "/../classes/Clan.class.php");
	
	class RPGClans {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getClans() {
			global $db;
			
			$sql = 'SELECT DISTINCT id 
					FROM rpg_clans
					ORDER BY name';
			$result = $db->sql_query($sql);
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT id FROM rpg_clans ORDER BY name');
			$req->execute(array($id));*/
			
			$clans = array();
			
			//while($info = $req->fetch()) {
			while($info = $db->sql_fetchrow($result)) {
				$clans[] = RPGClans::getClan($info['id']);
			}
			
			//$req->closeCursor();
			$db->sql_freeresult($result);
			
			return $clans;
		}
		
		public static function getClan($id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_clans
					WHERE id = ' . (int) $db->sql_escape($id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			$sql = 'SELECT COUNT(*) FROM rpg_clans WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			
			$req = $bdd->prepare('SELECT * FROM rpg_clans WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$c = new Clan($info, RPGClans::getMembersOfClan($info['id']));
			return $c;
		}
		
		public static function getClanByLeaderId($leader_id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_clans
					WHERE leader_id = ' . (int) $db->sql_escape($leader_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			/*$bdd = &Database::getBDD();
			$sql = 'SELECT COUNT(*) FROM rpg_clans WHERE id = ' . intval($id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return null;
			
			
			$req = $bdd->prepare('SELECT * FROM rpg_clans WHERE id = ?');
			$req->execute(array($id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			$c = new Clan($info, RPGClans::getMembersOfClan($info['id']));
			return $c;
		}
		
		public static function getMembersOfClan($clan_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT member_id 
					FROM rpg_clans_members
					WHERE clan_id = ' . (int) $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT member_id FROM rpg_clans_members WHERE clan_id = ?');
			$req->execute(array($clan_id));*/
			
			$members = array();
			
			//while($info = $req->fetch()) {
			while($info = $db->sql_fetchrow($result)) {
				$members[] = RPGUsersPlayers::getPlayerByUserId($info['member_id']);
			}
			
			//$req->closeCursor();
			$db->sql_freeresult($result);
			
			return $members;
		}
		
		public static function getMessagesOfClan($clan_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_clans_messages
					WHERE clan_id = ' . (int) $db->sql_escape($clan_id) .
					' ORDER BY date';
			$result = $db->sql_query($sql);
			
			$messages = array();
			
			while($info = $db->sql_fetchrow($result)) {
				if(RPGUsersPlayers::getPlayerByUserId($info['user_id']) === null) continue;
				
				$info['user_name'] = RPGUsersPlayers::getPlayerByUserId($info['user_id'])->getName();
				/*$info['text'] = preg_replace("/\\\\\\\\\\\\\\\\/", '\\', $info['text']); // pour byethost qui écrit \\\\ au lieu de \ dans la BDD
				$info['text'] = str_replace("\\\\", '\\', $info['text']); // changer \\ en \ lorsque l'on saisit \ dans la chatbox
				$info['text'] = str_replace("\'", "'", $info['text']); // transformer \' en ' pour l'affichage
				//$info['text'] = preg_replace("/\\\\+\'/", "", $info['text']); // transformer \' en ' pour l'affichage
				$info['text'] = preg_replace("/\\\\+&quot;/", "", $info['text']); // transformer \&quot; en " pour l'affichage*/
				$messages[] = $info;
			}
			
			$db->sql_freeresult($result);
			
			return $messages;
		}
		
		public static function getClanByUserId($user_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT clan_id 
					FROM rpg_clans_members
					WHERE member_id = ' . (int) $db->sql_escape($user_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			return RPGClans::getClan($info['clan_id']);
		}
		
		
		
		public static function postChatBoxMessage($clan_id, $user_id, $message) {
			if($message == "") return;
			
			global $db;
			
			$messages = RPGClans::getMessagesOfClan($clan_id);
			
			//if chatbox messages are more than allowed
			if(count($messages) > 0 and count($messages) >= MAX_CHATBOX_MSG) {
				if(!RPGClans::deleteChatBoxMessage($messages[0]['id'])) return false;
				return RPGClans::postChatBoxMessage($clan_id, $user_id, $message);
			}
			
			$insert_data = array(
				'clan_id'	=> (int) $db->sql_escape($clan_id),
				'user_id'	=> (int) $db->sql_escape($user_id),
				'date'		=> time(),
				//'text'		=> $db->sql_escape($message),
				'text'		=> $message,
			);
			
			$sql = 'INSERT INTO rpg_clans_messages ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function deleteChatBoxMessage($msg_id) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_clans_messages
					WHERE id = ' . (int) $db->sql_escape($msg_id);
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function isClanMember($user_id, $clan_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_clans_members
					WHERE member_id = ' . (int) $db->sql_escape($user_id) . '
					AND clan_id = ' . (int) $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			else return true;
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_clans_members WHERE member_id = ' . intval($user_id) . ' AND clan_id = ' . intval($clan_id);
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return false;
			
			return true;*/
		}
		
		public static function isClanLeader($user_id, $clan_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT leader_id
					FROM rpg_clans
					WHERE id = ' . (int) $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			
			
			$leader = false;
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT leader_id FROM rpg_clans WHERE id = ?');
			$req->execute(array($clan_id));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			if(intval($info['leader_id']) == $user_id) $leader = true;
			
			return $leader;
		}
		
		
		public static function nameExists($name) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_clans
					WHERE name = \'' . $name . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return true;
			
			
			/*$bdd = &Database::getBDD();
			
			$sql = 'SELECT COUNT(*) FROM rpg_clans WHERE name = ' . $name;
			$res = $bdd->query($sql);
			if($res->fetchColumn() == 0) return false;
			
			return true;*/
		}
		
		public static function joinRequestExists($user_id, $clan_id) {
			global $db;
			
			if(!RPGUsersPlayers::getPlayerByUserId($user_id)) return false;
			if(!RPGClans::getClan($clan_id)) return false;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_clans_join_requests
					WHERE user_id = ' . $db->sql_escape($user_id) . ' AND clan_id = ' . $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return true;
		}
		
		public static function getJoinRequest($token) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_clans_join_requests
					WHERE token = \'' . $db->sql_escape($token) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return $info;
		}
		
		/*
		* Store into BDD players clan's joining requets
		* user_id : user_id of player
		* clan_id : clan_id of the clan to join
		*
		* returns request's token if storing goes well, false otherwise.
		*/
		public static function storeJoinRequest($user_id, $clan_id) {
			global $db;
			
			if(RPGClans::joinRequestExists($user_id, $clan_id)) return false;
			
			$token = md5(uniqid());
			
			$insert_array = array(
					'id' 		=> NULL,
					'token'		=> $token,
					'user_id'	=> (int) $user_id,
					'clan_id'	=> (int) $clan_id,
				);
				
			$sql = 'INSERT INTO rpg_clans_join_requests ' . $db->sql_build_array('INSERT', $insert_array);
			$db->sql_query($sql);
			
			if($db->sql_affectedrows() <= 0) return false;
			
			return $token;
		}
		
		public static function deleteJoinRequest($token) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_clans_join_requests
					WHERE token = \'' . $db->sql_escape($token) . '\'';
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		/*
		* Create a clan.
		* leader_id : user_id in phpbb_users DB
		*/
		public static function createClan($name, $desc, $img, $leader_id) {
			if(RPGClans::nameExists($name)) return false;
			
			global $db;
			
			//create clan
			$sql = "INSERT INTO rpg_clans VALUES (NULL, '" . $name . "', '" . $desc . "', '$img', $leader_id)";
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);
			
			$db->sql_transaction('begin');
			
			//set clan_id in rpg_players DB
			$clan = RPGClans::getClanByLeaderId($leader_id);
			if($clan === null) return false;
			
			//$clan_id = $db->sql_nextid();
			$clan_id = $clan->getId();
			
			
			$player = RPGUsersPlayers::getPlayerByUserId($leader_id);
			$set_success = RPGPlayers::setClanIdByPlayer($player, $clan_id);
			
			//add leader to clan members
			
			$insert_data = array(
				'clan_id'		=> $clan_id,
				'member_id'		=> (int) $db->sql_escape($leader_id),
			);
			
			$sql = 'INSERT INTO rpg_clans_members ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			$add_success = ($db->sql_affectedrows() > 0);
			
			$db->sql_transaction('commit');
			
			return ($insert_success and $set_success and $add_success);
		}
		
		/*
		* Delete a clan according to its id.
		* NOTE : foreign keys are automaticely deleted or set to NULL
		*/
		public static function deleteClan($clan_id) {
			global $db;
			
			RPGClans::purgeClanMessages($clan_id);
			RPGClans::deleteClanMembers($clan_id);
			
			$sql = 'SELECT DISTINCT img
					FROM rpg_clans
					WHERE id = ' . (int) $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			$unlink_success = true;
			if($info) $unlink_success = @unlink(__DIR__ . '/../../images/rpg/clans/see/clan_images/' . $info['img']);
			
			$sql = 'DELETE
					FROM rpg_clans
					WHERE id = ' . (int) $db->sql_escape($clan_id);
			$db->sql_query($sql);
			
			return $unlink_success and ($db->sql_affectedrows() > 0);
		}
		
		/*
		* Add a member to a clan
		*
		* user_id : the user_id of the member
		* clan_id : the id of the clan
		*
		* Return true if operation was successful, false otherwise.
		*/
		public static function addClanMember($user_id, $clan_id) {
			global $db;
			
			$player = RPGUsersPlayers::getPlayerByUserId($user_id);
			if($player === null) return false;
			if(RPGClans::getClan($clan_id) === null) return false;
			
			$db->sql_transaction('begin');
			
			//insert member in clan members table
			$sql = 'INSERT INTO rpg_clans_members VALUES (' . (int) $clan_id . ', ' . (int) $user_id . ')';
			$db->sql_query($sql);
			$insert_success = ($db->sql_affectedrows() > 0);
			
			//update player table
			$sql = 'UPDATE rpg_players
					SET clan_id = ' . (int) $clan_id . '
					WHERE id = ' . (int) $player->getId();
			$update_success = ($db->sql_affectedrows() > 0);
			
			
			$db->sql_transaction('commit');
			
			return $insert_success and $update_success;
		}
		
		/*
		* Remove member of a clan.
		* member_id : the user_id of the member
		* clan_id : the id of the clan
		*
		* Return true if operation was successful, false otherwise.
		*/
		public static function removeClanMember($member_id, $clan_id) {
			global $db;
			
			$db->sql_transaction('begin');
			
			$sql = 'DELETE
					FROM rpg_clans_members
					WHERE clan_id = ' . (int) $clan_id .'
					AND member_id = ' . (int) $member_id;
			$db->sql_query($sql);
			
			$delete_success = ($db->sql_affectedrows() > 0);
			
			$db->sql_transaction('commit');
			
			return $delete_success;
		}
		
		public static function searchClans($name) {
			global $db;
			
			$n = "'%" . strtolower($name) . "%'";
			
			$sql = 'SELECT * FROM rpg_clans
					WHERE LOWER(name) LIKE ' . $n;
			
			$result = $db->sql_query($sql);
			
			$clans = array();
			
			//while($info = $req->fetch()) {
			while($info = $db->sql_fetchrow($result)) {
				$clans[] = RPGClans::getClan($info['id']);
			}
			
			//$req->closeCursor();
			$db->sql_freeresult($result);
			
			return $clans;
		}
		
		public static function purgeClanMessages($clan_id) {
			global $db;
			
			$clan = RPGClans::getClan($clan_id);
			if(!$clan) return true;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_clans_messages
					WHERE clan_id = ' . (int) $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return true;
			
			$sql = 'DELETE
					FROM rpg_clans_messages
					WHERE clan_id = ' . (int) $clan_id;
			$db->sql_query($sql);
			
			/*$delete_success = ($db->sql_affectedrows() > 0);
			
			return $delete_success;*/
			return true;
		}
		
		private static function deleteClanMembers($clan_id) {
			global $db;
			
			$clan = RPGClans::getClan($clan_id);
			if(!$clan) return true;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_clans_members
					WHERE clan_id = ' . (int) $db->sql_escape($clan_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return true;
			
			$sql = 'DELETE
					FROM rpg_clans_members
					WHERE clan_id = ' . (int) $clan_id;
			$db->sql_query($sql);
			
			$delete_success = ($db->sql_affectedrows() > 0);
			
			return $delete_success;
		}
		
		public static function updatePI(Clan &$clan, $pi) {
			global $db;
			
			if($pi < 0) return false;
			if($clan->getPI() == $pi) return true;
			
			$data = array(
				'pi'	=>	$pi,
			);
			
			$sql = 'UPDATE rpg_clans
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . $clan->getId();
			$db->sql_query($sql);
			
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $clan->setPI($pi);
			
			return $update_success;
		}
		
		public static function getStatLevel($clan_id, $stat) {
			global $db;
			
			$stat_field = '';
			
			switch($stat) {
				case STAT_ATTACK:
					$stat_field = 'atk_level';
					break;
				case STAT_DEFENSE:
					$stat_field = 'def_level';
					break;
				case STAT_SPEED:
					$stat_field = 'spd_level';
					break;
				case STAT_FLUX:
					$stat_field = 'flux_level';
					break;
				case STAT_RESISTANCE:
					$stat_field = 'res_level';
					break;
				case STAT_PV:
					$stat_field = 'pv_level';
					break;
				case STAT_PF:
					$stat_field = 'pf_level';
					break;
			}
			
			if($stat_field == '') return 0;
			
			$sql = "SELECT $stat_field
					FROM rpg_clans
					WHERE id = $clan_id";
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return 0;
			
			return $info[$stat_field];
		}
		
		public static function updateStatLevel(Clan &$clan, $stat, $level) {
			global $db;
			
			$stat_field = '';
			
			switch($stat) {
				case STAT_ATTACK:
					$stat_field = 'atk_level';
					break;
				case STAT_DEFENSE:
					$stat_field = 'def_level';
					break;
				case STAT_SPEED:
					$stat_field = 'spd_level';
					break;
				case STAT_FLUX:
					$stat_field = 'flux_level';
					break;
				case STAT_RESISTANCE:
					$stat_field = 'res_level';
					break;
				case STAT_PV:
					$stat_field = 'pv_level';
					break;
				case STAT_PF:
					$stat_field = 'pf_level';
					break;
			}
			
			if($stat_field == '') return false;
			
			$data = array(
				$stat_field	=>	$level,
			);
			
			$sql = 'UPDATE rpg_clans
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . (int) $clan->getId();
			$db->sql_query($sql);
			
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $clan->setStatLevel($stat, $level);
			
			return $update_success;
		}
	}
?>