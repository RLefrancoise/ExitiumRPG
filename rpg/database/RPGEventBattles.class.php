<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	include_once(__DIR__ . "/RPGMonsters.class.php");
	include_once(__DIR__ . '/RPGClothes.class.php');
	include_once(__DIR__ . '/RPGLeggings.class.php');
	include_once(__DIR__ . '/RPGGloves.class.php');
	include_once(__DIR__ . '/RPGShoes.class.php');
	include_once(__DIR__ . '/RPGSyringes.class.php');
	include_once(__DIR__ . '/RPGSpecials.class.php');
	include_once(__DIR__ . '/RPGOrbs.class.php');
	include_once(__DIR__ . "/../classes/AbstractBattle.class.php");
	include_once(__DIR__ . '/../../common.php');
	include_once(__DIR__ . '/../php/post_functions.php');
	include_once(__DIR__ . '/../classes/rpgconfig.php');
	
	class RPGEventBattles {
		private static $theInst;

		private function __construct() {
		}
		
		public static function isRegisteredInEvent($event_token, $player_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_event_battles_registered_players
					WHERE token = \'' . $event_token . '\'
					AND player_id = ' . $player_id;
					
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return true;
		}
		
		public static function isInAnyEvent($player_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_event_battles_players
					WHERE player_id = ' . $player_id . '
					AND in_event = 1';
				
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return true;
		}
		
		public static function isInEvent($event_token, $player_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_event_battles_players
					WHERE player_id = ' . $player_id . '
					AND battle_token = \'' . $event_token . '\'
					AND in_event = 1';
				
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return true;
		}
		
		public static function setPlayerInEvent(EventBattle& $battle, $b) {
			global $db;
			
			$update_array = array(
				'in_event' => (bool) $db->sql_escape($b),
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayerInEvent($b);
				
			return $update_success;
		}
		
		public static function registerPlayerInEvent($event_token, $player_id) {
			global $db;
			
			$insert_array = array(
				'token' => $event_token,
				'player_id' => $player_id,
			);
			
			$sql = 'INSERT INTO rpg_event_battles_registered_players ' . $db->sql_build_array('INSERT', $insert_array);
					
			$db->sql_query($sql);
				
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function setPlayerIsDead(EventBattle& $battle, $b) {
			global $db;
			
			if($battle->playerIsDead() == $b) return true;
			
			$update_array = array(
				'is_dead' => (bool) $db->sql_escape($b),
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayerIsDead($b);
				
			return $update_success;
		}
		
		public static function eventExists($token) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_event_battles
					WHERE token = \'' . $db->sql_escape($token) . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return true;
		}
		
		public static function getEventGeneralData($token) {
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_event_battles 
					WHERE token = \'' . $token . '\'';
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return false;
			return $info;
		}
		
		public static function getEvent($token, $player_id){
			global $db;
			
			$sql = 'SELECT DISTINCT * 
					FROM rpg_event_battles, rpg_event_battles_players 
					WHERE token = \'' . $token . '\'
					AND token = battle_token
					AND player_id = ' . $db->sql_escape($player_id);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$b = new EventBattle($info);
			return $b;
		}
		
		public static function createEvent($monster_id, $monster_hp, $monster_fp, $bgm, $background, $forum_id, $topic_id, $items_data = array()) {
			global $db;
			
			$token = md5(uniqid());
			
			$insert_data = array(
				'token'			=> (string) $token,
				'monster_id'	=> (int) $db->sql_escape($monster_id),
				'monster_hp'	=> (int) $db->sql_escape($monster_hp),
				'monster_fp'	=> (int) $db->sql_escape($monster_fp),
				'bgm'			=> $db->sql_escape($bgm),
				'background'	=> $db->sql_escape($background),
				'forum_id'		=> (int) $db->sql_escape($forum_id),
				'topic_id'		=> (int) $db->sql_escape($topic_id),
			);
			
			$sql = 'INSERT INTO rpg_event_battles ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);
			
			$insert_success = ($db->sql_affectedrows() > 0);
			if(!$insert_success) return false;
			
			//put items in BDD
			foreach($items_data as $rank => $items) {
				
				foreach($items as $item) {
				
					$insert_data = array(
						'battle_token'	=> (string) $token,
						'rank'			=> (int) $rank,
						'item_type'		=> $item['item_type'],
						'item_id'		=> $item['item_id'],
						'number'		=> $item['number'],
					);
					
					$sql = 'INSERT INTO rpg_event_battles_items ' . $db->sql_build_array('INSERT', $insert_data);
					$db->sql_query($sql);
					
					if($db->sql_affectedrows() <= 0) return false;
				}
				
			}
			
			//post message on topic
			$monster_name = RPGMonsters::getMonster($monster_id)->getName();
			
			$subject = "Création d'un event.";
			$text = "Le world boss \"{$monster_name}\" apparait !" . PHP_EOL . "Inscrivez-vous ici : [registerevent]{$token}[/registerevent]" . PHP_EOL . "Rejoignez le combat via ce lien : [event]" . $token . "[/event]";
			
			//get items as a string
			$items = RPGEventBattles::getEventItems($token);
			$text .= (PHP_EOL . PHP_EOL . "Récompenses en fin d'event :" . PHP_EOL . PHP_EOL);
			
			foreach($items as $rank => $items_list) {
				$text .= "Rang $rank : ";
				for($i = 0 ; $i < count($items_list) ; $i++) {
					$item = $items_list[$i]['item'];
					$text .= $item->getName() . " x{$items_list[$i]['number']}";
					if($i < (count($items_list) - 1)) $text .= ", ";
				}
				$text .= PHP_EOL;
			}
			
			rpg_post($subject, $text, 'reply', $forum_id, $topic_id);
			
			return $token;
		}
		
		public static function putPlayerInEvent($player_id, $battle_token) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_event_battles_players
					WHERE player_id = ' . $player_id . '
					AND battle_token = \'' . $battle_token . '\'';
				
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) {
			
				$insert_data = array(
					'battle_token'	=> (string) $battle_token,
					'player_id'		=> (int) $db->sql_escape($player_id),
					'in_event'		=> true,
					'turn'			=> 1,
				);
				
				$sql = 'INSERT INTO rpg_event_battles_players ' . $db->sql_build_array('INSERT', $insert_data);
				$db->sql_query($sql);
				
				$insert_success = ($db->sql_affectedrows() > 0);
				
				if(!$insert_success) return false;
				
				return true;
			}
			else return RPGEventBattles::setPlayerInEvent(RPGEventBattles::getEvent($battle_token, $player_id), true);
			
		}
		
		public static function deleteEvent($token) {
			global $db;
			
			$sql = 'DELETE
					FROM rpg_event_battles
					WHERE token = \'' . (string) $db->sql_escape($token) . '\'';
			$db->sql_query($sql);
			
			return ($db->sql_affectedrows() > 0);
		}
		
		public static function incrementTurn(EventBattle &$battle) {
			global $db;
			
			$update_array = array(
				'turn' => (int) $db->sql_escape($battle->getTurn() + 1),
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setTurn($battle->getTurn() + 1);
				
			return $update_success;
		}
		
		public static function setMonsterHP(EventBattle &$battle, $monster_hp) {
			global $db;
			
			if($monster_hp < 0) {
				$update_array = array(
					'monster_hp' => 0,
				);
			}
			else {
				$update_array = array(
					'monster_hp' => (int) $db->sql_escape($monster_hp),
				);
			}
			
			$sql = 'UPDATE rpg_event_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setMonsterHP($monster_hp);
				
			return $update_success;
		}
		
		public static function setMonsterFP(EventBattle &$battle, $monster_fp) {
			global $db;
			
			if($monster_hp < 0) {
				$update_array = array(
					'monster_fp' => 0,
				);
			}
			else {
				$update_array = array(
					'monster_fp' => (int) $db->sql_escape($monster_fp),
				);
			}
			
			$sql = 'UPDATE rpg_event_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setMonsterFP($monster_fp);
				
			return $update_success;
		}
		
		public static function setPlayerSkills(EventBattle &$battle, $skills) {
			global $db;
			
			//if($battle->playerSkillsToString() === $skills) return true;
			
			$update_array = array(
				'player_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1Skills($skills);
				
			return $update_success;
		}
		
		public static function setMonsterSkills(EventBattle &$battle, $skills) {
			global $db;
			
			//if($battle->monsterSkillsToString() === $skills) return true;
			
			$update_array = array(
				'monster_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2Skills($skills);
				
			return $update_success;
		}
		
		public static function setPlayerActiveSkills(EventBattle &$battle, $skills) {
			global $db;
			
			$update_array = array(
				'player_active_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1ActiveSkills($skills);
				
			return $update_success;
		}
		
		public static function setMonsterActiveSkills(EventBattle &$battle, $skills) {
			global $db;
			
			$update_array = array(
				'monster_active_skills' => $skills,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2ActiveSkills($skills);
				
			return $update_success;
		}
		
		public static function resetPlayerActiveSkills(EventBattle &$battle) {
			global $db;
			
			if($battle->player1ActiveSkillsToString() == '') return true;
			
			$update_array = array(
				'player_active_skills' => '',
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->resetPlayer1ActiveSkills();
				
			return $update_success;
		}
		
		public static function resetMonsterActiveSkills(EventBattle &$battle) {
			global $db;
			
			if($battle->player2ActiveSkillsToString() == '') return true;
			
			$update_array = array(
				'monster_active_skills' => '',
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->resetPlayer2ActiveSkills();
				
			return $update_success;
		}
		
		public static function setPlayerBuffs(EventBattle &$battle, $buffs) {
			global $db;
			
			$update_array = array(
				'player_buffs' => $buffs,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1Buffs($buffs);
				
			return $update_success;
		}
		
		public static function setMonsterBuffs(EventBattle &$battle, $buffs) {
			global $db;
			
			$update_array = array(
				'monster_buffs' => $buffs,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2Buffs($buffs);
				
			return $update_success;
		}
		
		public static function setPlayerActiveOrbs(EventBattle &$battle, $orbs) {
			global $db;
			
			//if($battle->player1ActiveOrbsToString() == $orbs) return true;
			
			$update_array = array(
				'player_active_orbs' => $orbs,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer1ActiveOrbs($orbs);
				
			return $update_success;
		}
		
		public static function setMonsterActiveOrbs(EventBattle &$battle, $orbs) {
			global $db;
			
			//if($battle->player2ActiveOrbsToString() == $orbs) return true;
			
			$update_array = array(
				'monster_active_orbs' => $orbs,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->setPlayer2ActiveOrbs($orbs);
				
			return $update_success;
		}
		
		public static function incrementTotalDamageGiven(EventBattle &$battle, $damage) {
			global $db;
			
			if($damage == 0) return true;
			
			$update_array = array(
				'total_damage_given' => $battle->getDamageGiven() + (int) $damage,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->incrementDamageGiven($damage);
				
			return $update_success;
		}
		
		public static function incrementTotalDamageReceived(EventBattle &$battle, $damage) {
			global $db;
			
			if($damage == 0) return true;
			
			$update_array = array(
				'total_damage_received' => $battle->getDamageReceived() + (int) $damage,
			);
			
			$sql = 'UPDATE rpg_event_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();
					
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);
			
			if($update_success) $battle->incrementDamageReceived($damage);
				
			return $update_success;
		}
		
		public static function getEventItems($event_token) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_event_battles_items
					WHERE battle_token = \'' . $event_token . '\'
					ORDER BY rank ASC';
			$result = $db->sql_query($sql);
			
			$items = array();
			
			while($info = $db->sql_fetchrow($result)) {
				if(!isset($items[$info['rank']])) $items[$info['rank']] = array();
				
				$item_data = array();
				$item = null;
				
				switch($info['item_type']) {
					case 'syringe':
						$item = RPGSyringes::getSyringe($info['item_id']);
						break;
					case 'orb':
						$item = RPGOrbs::getOrb($info['item_id']);
						break;
					case 'clothes':
						$item = new SetPart(RPGClothes::getCloth($info['item_id']), ARMOR_CLOTH);
						break;
					case 'leggings':
						$item = new SetPart(RPGLeggings::getLegging($info['item_id']), ARMOR_LEGGINGS);
						break;
					case 'gloves':
						$item = new SetPart(RPGGloves::getGlove($info['item_id']), ARMOR_GLOVES);
						break;
					case 'shoes':
						$item = new SetPart(RPGShoes::getShoe($info['item_id']), ARMOR_SHOES);
						break;
					case 'special':
						$item = RPGSpecials::getSpecial($info['item_id']);
						break;
				}
				
				if($item == null) continue;
				
				$item_data['item'] = $item;
				$item_data['number'] = $info['number'];
				$items[$info['rank']][] = $item_data;
			}
			
			$db->sql_freeresult($result);
			
			return $items;
		}
		
		public static function giveEventItems($event_token) {
			global $db;
			
			$msg  = ('Attribution des récompenses :' . PHP_EOL . PHP_EOL);
			
			$items = RPGEventBattles::getEventItems($event_token);
			
			$given_ranking = RPGEventBattles::getDamageGivenRanking($event_token);
			$received_ranking = RPGEventBattles::getDamageReceivedRanking($event_token);
			
			foreach($items as $rank => $items_data) {
				//total damage given ranking
				if(array_key_exists($rank, $given_ranking)) {
				
					foreach($given_ranking[$rank] as $player_data) {
						//$player_data = $given_ranking[$rank];
						
						$player = RPGUsersPlayers::getPlayerByPlayerId($player_data['player_id']);
						foreach($items_data as $item_info) {
							for($i = 0 ; $i < $item_info['number'] ; $i++) {
								RPGPlayers::giveItemToPlayer($player, $item_info['item']);
							}
							$msg .= "{$player->getName()} reçoit {$item_info['item']->getName()} x {$item_info['number']}" . PHP_EOL;
						}
					}
				}
				
				//total damage received ranking
				if(array_key_exists($rank, $received_ranking)) {
					
					foreach($received_ranking[$rank] as $player_data) {
						//$player_data = $received_ranking[$rank];
						
						$player = RPGUsersPlayers::getPlayerByPlayerId($player_data['player_id']);
						foreach($items_data as $item_info) {
							for($i = 0 ; $i < $item_info['number'] ; $i++) {
								RPGPlayers::giveItemToPlayer($player, $item_info['item']);
							}
							$msg .= "{$player->getName()} reçoit {$item_info['item']->getName()} x {$item_info['number']}" . PHP_EOL;
						}
					}
				}
			}
			
			return $msg;
		}
		
		public static function getDamageGivenRanking($event_token) {
			global $db;
			
			$data = RPGEventBattles::getEventGeneralData($event_token);
			$monster_level = RPGMonsters::getMonster($data['monster_id'])->getLevel();
			
			$sql = 'SELECT DISTINCT ebp.player_id, u.username, p.level, ebp.total_damage_given
					FROM rpg_event_battles as eb, rpg_event_battles_players as ebp, phpbb_users as u, rpg_players as p, rpg_users_players as up
					WHERE ebp.battle_token = \'' . $event_token . '\'
					AND ebp.battle_token = eb.token
					AND p.id = ebp.player_id
					AND u.user_id = up.user_id
					AND p.id = up.player_id
					ORDER BY ebp.total_damage_given DESC';
			$result = $db->sql_query($sql);
			
			$damages = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$coef = (1 + 0.1 * ($monster_level - $info['level']));
				if($coef <= 0) $coef = 0.1;
				$info['total_damage_given'] *= $coef;
				
				$info['total_damage_given'] = (int) floor($info['total_damage_given']);
				
				if(!is_array($damages[$info['total_damage_given']]))
					$damages[$info['total_damage_given']] = array();
					
				$damages[$info['total_damage_given']][] = $info;
			}
			
			$db->sql_freeresult($result);
			
			krsort($damages, SORT_NUMERIC);
			
			$ranking = array();
			$rank = 1;
			foreach($damages as $d => $data) {
				foreach($data as $player_data) {
					if(!is_array($ranking[$rank])) $ranking[$rank] = array();
					
					$ranking[$rank][] = $player_data;
				}
				//$ranking[$rank] = $data;
				$rank += 1;
			}
			
			if(count($ranking) == 0) return false;
			
			return $ranking;
		}
		
		public static function getDamageReceivedRanking($event_token) {
			global $db;
			
			$data = RPGEventBattles::getEventGeneralData($event_token);
			$monster_level = RPGMonsters::getMonster($data['monster_id'])->getLevel();
			
			$sql = 'SELECT DISTINCT ebp.player_id, u.username, p.level, ebp.total_damage_received
					FROM rpg_event_battles as eb, rpg_event_battles_players as ebp, phpbb_users as u, rpg_players as p, rpg_users_players as up
					WHERE ebp.battle_token = \'' . $event_token . '\'
					AND ebp.battle_token = eb.token
					AND p.id = ebp.player_id
					AND u.user_id = up.user_id
					AND p.id = up.player_id
					ORDER BY ebp.total_damage_received ASC';
			$result = $db->sql_query($sql);
			
			$damages = array();
			
			while($info = $db->sql_fetchrow($result)) {
				$coef = (1 + 0.1 * ($monster_level - $info['level']));
				if($coef <= 0) $coef = 0.1;
				$info['total_damage_received'] /= $coef;
				
				$info['total_damage_received'] = (int) floor($info['total_damage_received']);
				
				if(!is_array($damages[$info['total_damage_received']]))
					$damages[$info['total_damage_received']] = array();
					
				$damages[$info['total_damage_received']][] = $info;
			}
			
			$db->sql_freeresult($result);
			
			ksort($damages, SORT_NUMERIC);
			
			$ranking = array();
			$rank = 1;
			foreach($damages as $d => $data) {
				foreach($data as $player_data) {
					if(!is_array($ranking[$rank])) $ranking[$rank] = array();
					
					$ranking[$rank][] = $player_data;
				}
				//$ranking[$rank] = $data;
				$rank += 1;
			}
			
			if(count($ranking) == 0) return false;
			
			return $ranking;
		}
		
		public static function manageEventEnding($event_token, $forum_id, $topic_id) {
			//damage given ranking
			$dg_ranking = RPGEventBattles::getDamageGivenRanking($event_token);
			if(!$dg_ranking) { // no ranking
				return false;
			}
			
			//damage received ranking
			$dr_ranking = RPGEventBattles::getDamageReceivedRanking($event_token);
			if(!$dr_ranking) { // no ranking
				return false;
			}
			
			$dg_text = 'Classement en fonction des dégats infligés :' . PHP_EOL;
			foreach($dg_ranking as $rank => $data) {
				$players_list = '';
				$damage = 0;
				
				foreach($data as $player_data) {
					$players_list .= ($player_data['username'] . ', ');
					$damage = $player_data['total_damage_given'];
				}
				$players_list = substr($players_list, 0, strlen($players_list) - 2);
				$dg_text .= ("Rang $rank : $players_list avec $damage" . PHP_EOL);
			}
			
			$dr_text = 'Classement en fonction des dégats subits :' . PHP_EOL;
			foreach($dr_ranking as $rank => $data) {
				$players_list = '';
				$damage = 0;
				
				foreach($data as $player_data) {
					$players_list .= ($player_data['username'] . ', ');
					$damage = $player_data['total_damage_received'];
				}
				$players_list = substr($players_list, 0, strlen($players_list) - 2);
				$dr_text .= ("Rang $rank : $players_list avec $damage" . PHP_EOL);
				//$dr_text .= ("Rang $rank : {$data['username']} avec {$data['total_damage_received']}" . PHP_EOL);
			}
			
			//give event items
			$items_text = RPGEventBattles::giveEventItems($event_token);
			if($items_text === false) return false;
			
			//post message on topic
			$event_data = RPGEventBattles::getEventGeneralData($event_token);
			$monster_name = RPGMonsters::getMonster($event_data['monster_id'])->getName();
			
			$text = 'Le world boss "' . $monster_name . '" a été vaincu !' . PHP_EOL . PHP_EOL . $dg_text . PHP_EOL . $dr_text . PHP_EOL . $items_text;
			
			rpg_post("Fin de l'event", $text, 'reply', $forum_id, $topic_id);
			
			return true;
		}
	}
	
?>