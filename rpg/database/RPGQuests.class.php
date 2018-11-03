<?php
	include_once(__DIR__ . "/Database.class.php");
	include_once(__DIR__ . "/RPGPlayers.class.php");
	include_once(__DIR__ . "/RPGUsersPlayers.class.php");
	include_once(__DIR__ . "/../classes/Quest.class.php");
	include_once(__DIR__ . '/../classes/rpgconfig.php');
	include_once(__DIR__ . '/../../common.php');
	include_once(__DIR__ . '/../php/player_functions.php');
	include_once(__DIR__ . '/../php/post_functions.php');

	class RPGQuests {
		private static $theInst;

		private function __construct() {
		}

		public static function getQuests() {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quests
					ORDER BY date DESC, forum_id ASC';

			$result = $db->sql_query($sql);

			$quests = array();

			while($info = $db->sql_fetchrow($result)) {
				$quest = RPGQuests::getQuest($info['id']);
				if(!$quest) continue;

				$quests[] = $quest;
			}

			$db->sql_freeresult($result);

			return $quests;
		}

		public static function getQuest($quest_id) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quests
					WHERE id = ' . $quest_id;

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);

			if(!$info) return false;

			$info['rewards'] = RPGQuests::getQuestRewards($info['id']);
			$quest = null;

			switch($info['type']) {
				case QUEST_TYPE_BATTLE:
					$quest = new BattleQuest($info);
					break;
				case QUEST_TYPE_SURVIVAL:
					$quest = new SurvivalQuest($info);
					break;
				case QUEST_TYPE_RIDDLE:
					$info['riddles'] = RPGQuests::getQuestRiddles($info['id']);
					$quest = new RiddleQuest($info);
					break;
			}

			$db->sql_freeresult($result);

			if($quest == null) return false;

			return $quest;
		}

		public static function getRiddle($riddle_id) {
			global $db;

			$sql = 'SELECT *
					FROM rpg_quests_riddles
					WHERE id = ' . $riddle_id;

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;

			return new QuestRiddle($info);
		}

		public static function questIsPlayed($quest_id) {
			global $db;

			$sql = 'SELECT *
					FROM rpg_active_quests
					WHERE quest_id = ' . $quest_id;

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;
			return true;
		}

		public static function canAnswerRiddle($player_id, $topic_id) {
			global $db;

			$sql = 'SELECT *
					FROM rpg_active_quests_members
					WHERE topic_id = ' . $topic_id . '
					AND member_id = ' . $player_id. '
					AND answered_riddle = 0';

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;

			return true;
		}

		public static function setAnsweredRiddleFlag($player_id, $topic_id) {
			global $db;

			$update_array = array(
				'answered_riddle' => true,
			);

			$sql = 'UPDATE rpg_active_quests_members
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE member_id = ' . $player_id;
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			return $update_success;
		}

		public static function setActiveQuestRiddle($topic_id, $riddle_id) {
			global $db;

			$update_array = array(
				'riddle_id' => $riddle_id,
			);

			$sql = 'UPDATE rpg_active_quests
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE topic_id = ' . $topic_id;
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			return $update_success;
		}

		public static function riddleQuestIsLost($topic_id) {
			global $db;

			$sql = 'SELECT *
					FROM rpg_active_quests_members
					WHERE topic_id = ' . $topic_id . '
					AND answered_riddle = 0';

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return true;

			return false;
		}

		public static function getQuestRiddles($quest_id) {
			global $db;

			$sql = 'SELECT qr.id, qr.name, qr.answer, qr.quest_id
					FROM rpg_quests as q, rpg_quests_riddles as qr
					WHERE qr.quest_id = q.id
					AND qr.quest_id = ' . $quest_id;

			$result = $db->sql_query($sql);

			$riddles = array();

			while($info = $db->sql_fetchrow($result)) {
				$riddles[] = new QuestRiddle($info);
			}

			$db->sql_freeresult($result);

			return $riddles;
		}

		public static function getQuestRewards($quest_id) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quests_rewards
					WHERE quest_id = ' . $quest_id;

			$result = $db->sql_query($sql);

			$rewards = array();

			while($info = $db->sql_fetchrow($result)) {
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
				$rewards[] = $item_data;
			}

			$db->sql_freeresult($result);

			return $rewards;
		}

		public static function getPlayerCurrentQuest($player_id) {
			global $db;

			$sql = 'SELECT q.quest_id
					FROM rpg_active_quests as q, rpg_active_quests_members as qm
					WHERE qm.member_id = ' . $player_id . '
					AND qm.topic_id = q.topic_id';

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;

			return RPGQuests::getQuest($info['quest_id']);
		}

		public static function getActiveQuestDataByTopicId($topic_id) {
			global $db;

			$sql = 'SELECT *
					FROM rpg_active_quests
					WHERE topic_id = ' . $topic_id;

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);

			if(!$info) return false;

			return $info;
		}

		public static function getActiveQuestsData() {
			global $db;

			$sql = 'SELECT *
					FROM rpg_active_quests';

			$result = $db->sql_query($sql);

			$quests = array();

			while($info = $db->sql_fetchrow($result)) {
				$quests[] = $info;
			}

			$db->sql_freeresult($result);

			return $quests;
		}

		public static function getPlayerActiveQuestData($player_id) {
			global $db;

			$sql = 'SELECT *
					FROM rpg_active_quests_members
					WHERE member_id = ' . $player_id;

			$result = $db->sql_query($sql);

			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;

			return $info;
		}

		public static function playerHasQuest($player_id) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_active_quests_members
					WHERE member_id = ' . $player_id;

			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);

			$db->sql_freeresult($result);

			if($info) return true;
			else return false;
		}

		public static function playerIsInQuest($player_id, $quest_id) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_active_quests_members as qm, rpg_active_quests as q
					WHERE qm.member_id = ' . $player_id . '
					AND q.quest_id = ' . $quest_id .'
					AND q.topic = qm.topic_id';

			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);

			$db->sql_freeresult($result);

			if($info) return true;
			else return false;
		}

		public static function getQuestsListTopicMessage() {
			global $db;

			$msg = 'Ici seront répertoriées toutes les quêtes :' . PHP_EOL . PHP_EOL;
			$current_forum_id = -1;

			$quests = RPGQuests::getQuests();

			// quests are already sorted by date and forum_id, but we need to write them according to their forum_id
			// so we have to group them
			$sorted_quests = array();
			foreach($quests as $quest) {
				if(!isset($sorted_quests[$quest->getForumId()])) {
					$sorted_quests[$quest->getForumId()] = array();
				}
				$sorted_quests[$quest->getForumId()][] = $quest;
			}

			ksort($sorted_quests, SORT_NUMERIC);

			//write topic message
			foreach($sorted_quests as $forum_id => $quest_list) {

				//get forum name
				$sql = 'SELECT forum_name
						FROM ' . FORUMS_TABLE . '
						WHERE forum_id = ' . $forum_id;

				$result = $db->sql_query($sql);
				$info = $db->sql_fetchrow($result);

				$db->sql_freeresult($result);

				if(!$info) continue; // looks like there's no such forum

				$msg .= PHP_EOL . "[size=120][color=#40FF00]{$info['forum_name']}[/color][/size]" . PHP_EOL . PHP_EOL; // write forum name

				foreach($quest_list as $quest) {

					//write quest : [date] name ([unique])
					$msg .= "[b][color=#FFFF00][" . date('d/m/y', $quest->getDate()) . "][/color] [quest={$quest->getId()}]{$quest->getName()}[/quest][/b]";
					if($quest->isUnique())
						$msg .= " [b][color=#FF0000][Quête unique][/color][/b]";
					$msg .= PHP_EOL . PHP_EOL . "[i]" . $quest->getDesc() . "[/i]" . PHP_EOL . PHP_EOL;

				}

			}

			return $msg;
		}

		public static function createQuest($name, $desc, $type, $available, $is_unique, $posts_number, $forum_id) {
			global $db;

			$insert_data = array(
				'name'			=> $db->sql_escape($name),
				'descr'			=> $db->sql_escape($desc),
				'type'			=> $db->sql_escape($type),
				'date'			=> time(),
				'available'		=> $available,
				'is_unique'		=> $is_unique,
				'posts_number'	=> $posts_number,
				'forum_id'		=> $forum_id,
			);

			$sql = 'INSERT INTO rpg_quests ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);

			$success = ($db->sql_affectedrows() > 0);

			if(!$success) return false;

			$qid = $db->sql_nextid();

			//post new quest on topic

			//quest topic is created ?
			$sql = 'SELECT *
				FROM ' . POSTS_TABLE . '
				WHERE forum_id = ' . QUEST_FORUM_ID . '
				AND topic_id = ' . QUEST_TOPIC_ID;

			$result = $db->sql_query($sql);

			//it is not created
			if(!$db->sql_fetchrow($result)) {
				$post_data = rpg_post("Quêtes", RPGQuests::getQuestsListTopicMessage(), 'post', QUEST_FORUM_ID);
				if(!$post_data) return false;
			}
			//else, update the topic
			else {
				$edit_data = rpg_post("Quêtes", RPGQuests::getQuestsListTopicMessage(), 'edit', QUEST_FORUM_ID, QUEST_TOPIC_ID, QUEST_POST_ID);
				if(!$edit_data) return false;
			}
			$db->sql_freeresult($result);

			return true;
		}

		public static function registerPlayerToQuest($topic_id, $player_id) {
			global $db;

			$insert_data = array(
				'topic_id'	=> $topic_id,
				'member_id'	=> $player_id,
			);

			$sql = 'INSERT INTO rpg_active_quests_members ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);

			$success = ($db->sql_affectedrows() > 0);

			if(!$success) return false;

			return true;
		}

		public static function closeActiveQuest($topic_id) {
			global $db;

			$update_array = array(
				'is_opened' => false,
			);

			$sql = 'UPDATE rpg_active_quests
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE topic_id = ' . $topic_id;
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			return $update_success;
		}

		public static function startQuest($quest_id, $player_id) {
			global $db;

			//post message on a new topic
			$result = array();

			$player = RPGUsersPlayers::getPlayerByPlayerId($player_id);
			if(!$player) return false;

			$quest = RPGQuests::getQuest($quest_id);
			if(!$quest) return false;

			$text = "Le joueur {$player->getName()} a lancé la quête \"{$quest->getName()}\"." . PHP_EOL . PHP_EOL;
			$text .= "Descriptif de la quête :" . PHP_EOL . PHP_EOL . $quest->getDesc() . PHP_EOL . PHP_EOL;

			$result = rpg_post("[Quête] {$quest->getName()} [{$player->getName()}]", $text, 'post', $quest->getForumId());

			//insert into active quests
			$insert_data = array(
				'quest_id' => $quest_id,
				'player_id' => $player_id,
				'forum_id'	=> $quest->getForumId(),
				'topic_id'	=> $result['topic_id'],
			);

			$sql = 'INSERT INTO rpg_active_quests ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);

			$insert_success = ($db->sql_affectedrows() > 0);

			if(!$insert_success) return false;

			//insert into active quests
			$insert_data = array(
				'topic_id' => $result['topic_id'],
				'member_id' => $player_id,
			);

			$sql = 'INSERT INTO rpg_active_quests_members ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);

			$insert_success = ($db->sql_affectedrows() > 0);

			if(!$insert_success) return false;

			$text = "Cliquer sur ce lien pour participer à cette quête : [registerquest]{$result['topic_id']}[/registerquest]" . PHP_EOL;
			$text .= "L'initiateur de la quête peut cliquer sur ce lien pour fermer les inscriptions : [closequest]{$result['topic_id']}[/closequest]";

			$result = rpg_post("Management de la quête", $text, 'reply', $quest->getForumId(), $result['topic_id']);

			return true;
		}

		public static function endQuest($topic_id) {
			global $db;

			$sql = 'DELETE
					FROM rpg_active_quests
					WHERE topic_id = ' . $topic_id;
			$db->sql_query($sql);

			return ($db->sql_affectedrows() > 0);
		}

		public static function manageQuestEnding($topic_id) {
			global $db;

			$db->sql_transaction('begin');

			$msg = RPGQuests::giveRewards($topic_id);

			if(!$msg) return false;

			//if unique quest, set available flag to false
			$sql = 'SELECT quest_id
					FROM rpg_active_quests
					WHERE topic_id = ' . $topic_id;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$quest = RPGQuests::getQuest($info['quest_id']);
			if(!$quest) return false;

			if($quest->isUnique()) {
				$update_array = array(
					'available' => false,
				);

				$sql = 'UPDATE rpg_quests
						SET ' . $db->sql_build_array('UPDATE', $update_array) . '
						WHERE id = ' . $info['quest_id'];
				$db->sql_query($sql);
				$update_success = ($db->sql_affectedrows() > 0);

				if(!$update_success) return false;
			}

			$db->sql_transaction('commit');

			return true;
		}

		public static function giveRewards($topic_id) {
			global $db;

			$msg = '';

			//get quest_id from topic_id
			$sql = 'SELECT DISTINCT aq.quest_id
					FROM rpg_active_quests as aq
					WHERE aq.topic_id = ' . $topic_id;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;

			$quest = RPGQuests::getQuest($info['quest_id']);
			if(!$quest) return false;

			//get rewards
			$rewards = RPGQuests::getQuestRewards($info['quest_id']);
			if(!$rewards) $rewards = array();

			// rewards : item + number

			//get players of quest
			$sql = 'SELECT DISTINCT member_id
					FROM rpg_active_quests_members
					WHERE topic_id = ' . $topic_id;
			$result = $db->sql_query($sql);

			$members = array();

			while($info = $db->sql_fetchrow($result)) {
				$members[] = $info['member_id'];
			}

			$db->sql_freeresult($result);

			//now we can give rewards to each member of the quest
			foreach($rewards as $reward) {

				//for each player
				for($i = 0 ; $i < count($members) ; $i++) {
					$player = RPGUsersPlayers::getPlayerByPlayerId($members[$i]);
					if(!$player) continue;

					//give correct number of item
					for($j = 0 ; $j < $reward['number'] ; $j++) {
						if(!RPGPlayers::giveItemToPlayer($player, $reward['item']))
							return false;
					}

					$msg .= "{$player->getName()} reçoit {$reward['item']->getName()} x{$reward['number']}" . PHP_EOL;
				}
			}

			$msg .= PHP_EOL;

			//give xp and ralz
			$xp = $quest->getXP();
			$ralz = $quest->getRalz();

			//for each player
			for($i = 0 ; $i < count($members) ; $i++) {
				$player = RPGUsersPlayers::getPlayerByPlayerId($members[$i]);
				if(!$player) continue;

				if(!player_give_exp($player, $xp))
					return false;
				if(!player_give_ralz($player, $ralz))
					return false;

				$msg .= "{$player->getName()} gagne $xp XP et $ralz Ralz." . PHP_EOL;
			}

			$text = "La quête {$quest->getName()} est terminée !" . PHP_EOL . $msg;

			rpg_post("Management de la quête", $text, 'reply', $quest->getForumId(), $topic_id);

			return $text;
		}

		public static function setActiveQuestBattleToken($topic_id, $token) {
			global $db;

			$update_array = array(
				'battle_token' => $token,
			);

			$sql = 'UPDATE rpg_active_quests
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE topic_id = ' . $topic_id;
			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			return $update_success;
		}


		// BATTLES
		public static function getQuestBattleByToken($token){
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quest_battles
					WHERE token = \'' . $token . '\''
					;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return null;

			$b = new QuestBattle($info);
			return $b;
		}

		public static function getQuestBattle($token, $player_id){
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quest_battles as qb, rpg_quest_battles_players as qbp
					WHERE qb.token = \'' . $token . '\'
					AND qbp.battle_token = \'' . $token . '\'
					AND player_id = ' . $player_id;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return null;

			$b = new QuestBattle($info);
			return $b;
		}

		public static function isInBattle($quest_token, $player_id) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quest_battles_players
					WHERE player_id = ' . $player_id . '
					AND battle_token = \'' . $quest_token . '\'
					AND in_battle = 1';

			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;
			return true;
		}

		public static function isInAnyBattle($player_id) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quest_battles_players
					WHERE player_id = ' . $player_id . '
					AND in_battle = 1';

			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) return false;
			return true;
		}

		public static function setPlayerInBattle(QuestBattle& $battle, $b) {
			global $db;

			$update_array = array(
				'in_battle' => (bool) $db->sql_escape($b),
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayerInBattle($b);

			return $update_success;
		}

		public static function setPlayerIsDead(QuestBattle& $battle, $b) {
			global $db;

			if($battle->playerIsDead() == $b) return true;

			$update_array = array(
				'is_dead' => (bool) $db->sql_escape($b),
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayerIsDead($b);

			return $update_success;
		}

		public static function setBattleIsOver(QuestBattle& $battle, $b) {
			global $db;

			if($battle->isOver() == $b) return true;

			$update_array = array(
				'is_over' => (bool) $db->sql_escape($b),
			);

			$sql = 'UPDATE rpg_quest_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'';

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setBattleIsOver($b);

			return $update_success;
		}

		public static function createBattle($monster_id, $monster_hp, $monster_fp, $bgm, $background, $forum_id, $topic_id) {
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

			$sql = 'INSERT INTO rpg_quest_battles ' . $db->sql_build_array('INSERT', $insert_data);
			$db->sql_query($sql);

			$insert_success = ($db->sql_affectedrows() > 0);
			if(!$insert_success) return false;

			//post message on topic
			$monster_name = RPGMonsters::getMonster($monster_id)->getName();

			$subject = "Apparition d'un ennemi.";
			$text = "L'ennemi \"{$monster_name}\" apparait !" . PHP_EOL . "Rejoignez le combat via ce lien : [questbattle]" . $token . "[/questbattle]";

			rpg_post($subject, $text, 'reply', $forum_id, $topic_id);

			return $token;
		}

		public static function putPlayerInBattle($player_id, $battle_token) {
			global $db;

			$sql = 'SELECT DISTINCT *
					FROM rpg_quest_battles_players
					WHERE player_id = ' . $player_id . '
					AND battle_token = \'' . $battle_token . '\'';

			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!$info) {

				$insert_data = array(
					'battle_token'	=> (string) $battle_token,
					'player_id'		=> (int) $db->sql_escape($player_id),
					'in_battle'		=> true,
					'turn'			=> 1,
				);

				$sql = 'INSERT INTO rpg_quest_battles_players ' . $db->sql_build_array('INSERT', $insert_data);
				$db->sql_query($sql);

				$insert_success = ($db->sql_affectedrows() > 0);

				if(!$insert_success) return false;

				return true;
			}
			else return RPGQuests::setPlayerInBattle(RPGQuests::getQuestBattle($battle_token, $player_id), true);

		}

		public static function incrementTurn(QuestBattle &$battle) {
			global $db;

			$update_array = array(
				'turn' => (int) $db->sql_escape($battle->getTurn() + 1),
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . (string) $db->sql_escape($battle->getToken()) . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setTurn($battle->getTurn() + 1);

			return $update_success;
		}

		public static function setMonsterHP(QuestBattle &$battle, $monster_hp) {
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

			$sql = 'UPDATE rpg_quest_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setMonsterHP($monster_hp);

			return $update_success;
		}

		public static function setMonsterFP(QuestBattle &$battle, $monster_fp) {
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

			$sql = 'UPDATE rpg_quest_battles
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE token = \'' . $battle->getToken() . '\'';

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setMonsterFP($monster_fp);

			return $update_success;
		}

		public static function setPlayerSkills(QuestBattle &$battle, $skills) {
			global $db;

			//if($battle->playerSkillsToString() === $skills) return true;

			$update_array = array(
				'player_skills' => $skills,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer1Skills($skills);

			return $update_success;
		}

		public static function setMonsterSkills(QuestBattle &$battle, $skills) {
			global $db;

			//if($battle->monsterSkillsToString() === $skills) return true;

			$update_array = array(
				'monster_skills' => $skills,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer2Skills($skills);

			return $update_success;
		}

		public static function setPlayerActiveSkills(QuestBattle &$battle, $skills) {
			global $db;

			$update_array = array(
				'player_active_skills' => $skills,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer1ActiveSkills($skills);

			return $update_success;
		}

		public static function setMonsterActiveSkills(QuestBattle &$battle, $skills) {
			global $db;

			$update_array = array(
				'monster_active_skills' => $skills,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer2ActiveSkills($skills);

			return $update_success;
		}

		public static function resetPlayerActiveSkills(QuestBattle &$battle) {
			global $db;

			if($battle->player1ActiveSkillsToString() == '') return true;

			$update_array = array(
				'player_active_skills' => '',
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->resetPlayer1ActiveSkills();

			return $update_success;
		}

		public static function resetMonsterActiveSkills(QuestBattle &$battle) {
			global $db;

			if($battle->player2ActiveSkillsToString() == '') return true;

			$update_array = array(
				'monster_active_skills' => '',
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->resetPlayer2ActiveSkills();

			return $update_success;
		}

		public static function setPlayerBuffs(QuestBattle &$battle, $buffs) {
			global $db;

			$update_array = array(
				'player_buffs' => $buffs,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer1Buffs($buffs);

			return $update_success;
		}

		public static function setMonsterBuffs(QuestBattle &$battle, $buffs) {
			global $db;

			$update_array = array(
				'monster_buffs' => $buffs,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer2Buffs($buffs);

			return $update_success;
		}

		public static function setPlayerActiveOrbs(QuestBattle &$battle, $orbs) {
			global $db;

			//if($battle->player1ActiveOrbsToString() == $orbs) return true;

			$update_array = array(
				'player_active_orbs' => $orbs,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer1ActiveOrbs($orbs);

			return $update_success;
		}

		public static function setMonsterActiveOrbs(QuestBattle &$battle, $orbs) {
			global $db;

			//if($battle->player2ActiveOrbsToString() == $orbs) return true;

			$update_array = array(
				'monster_active_orbs' => $orbs,
			);

			$sql = 'UPDATE rpg_quest_battles_players
					SET ' . $db->sql_build_array('UPDATE', $update_array) . '
					WHERE battle_token = \'' . $battle->getToken() . '\'
					AND player_id = ' . $battle->getPlayerId();

			$db->sql_query($sql);
			$update_success = ($db->sql_affectedrows() > 0);

			if($update_success) $battle->setPlayer2ActiveOrbs($orbs);

			return $update_success;
		}
	}


?>