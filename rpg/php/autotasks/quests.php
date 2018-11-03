<?php

define('IN_PHPBB', true);
$phpbb_root_path = '../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGQuests.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.php');
include_once($phpbb_root_path . 'rpg/php/post_functions.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup();

// get active quests
$quests_data = RPGQuests::getActiveQuestsData();

foreach($quests_data as $data) {
	//look if required messages number has been reached
	$quest = RPGQuests::getQuest($data['quest_id']);
	
	$topic_id = $data['topic_id'];
	
	$sql = 'SELECT *
			FROM ' . POSTS_TABLE . '
			WHERE topic_id = ' . $topic_id . '
			AND forum_id = ' . $quest->getForumId() . '
			AND poster_id != ' . RPG_POST_USER_ID;
	
	$result = $db->sql_query($sql);
	
	$count = 0;
	
	while($db->sql_fetchrow($result)) { $count++; }
	
	$db->sql_freeresult($result);
	
	if($count >= $quest->getRequiredPosts()) {
		switch($quest->getType()) {
			case QUEST_TYPE_RIDDLE:
				{
					//if no riddle, choose one
					if( ($data['riddle_id'] !== 0) and (!$data['riddle_id']) ) {
						$riddle = $quest->chooseRiddle();
						if(!$riddle) {
							echo "[ERROR] No riddle for quest {$quest->getId()}" . PHP_EOL; continue;
						}
						
						if(!RPGQuests::setActiveQuestRiddle($topic_id, $riddle->getId())) {
							echo "[ERROR] Failed to set riddle to quest {$quest->getId()}" . PHP_EOL; continue;
						}
						
						//post message on topic
						$text = "Un évènement de quête vient de se déclencher." . PHP_EOL . PHP_EOL;
						$text .= "Une énigme vous est imposée. Saurez-vous trouver la réponse ?" . PHP_EOL . PHP_EOL;
						$text .= "[riddle]{$riddle->getId()}[/riddle]";
						rpg_post("Evènement de quête", $text, 'reply', $quest->getForumId(), $topic_id);
					}
				}
				break;
			case QUEST_TYPE_BATTLE:
				//if no battle, create one
				if( ($data['battle_token'] !== 0) and (!$data['battle_token']) ) {
				
					//$db->sql_transaction('begin');
					
					//get monster data
					$monster = RPGMonsters::getMonster($quest->getMonsterId());
					if(!$monster) { echo "[ERROR] No monster data for quest {$quest->getId()}" . PHP_EOL; continue; }
					
					$battle_token = RPGQuests::createBattle($monster->getId(), $monster->getPV(), $monster->getPF(), $quest->getBGM(), $quest->getBackground(), $quest->getForumId(), $topic_id);
					if(!$battle_token) { echo "[ERROR] Failed to create battle for quest {$quest->getId()}" . PHP_EOL; continue; }
					
					if(!RPGQuests::setActiveQuestBattleToken($topic_id, $battle_token)) {
						echo "[ERROR] Failed to set battle token for active quest on topic {$data['topic_id']}" . PHP_EOL; continue;
					}
					
					//$db->sql_transaction('commit');
				}
				//if battle, check if it is over or not
				if($data['battle_token']) {
					//if over, end quest
					$battle = RPGQuests::getQuestBattleByToken($data['battle_token']);
					if($battle->isOver()) {
						//give rewards & end quest
						if(!RPGQuests::manageQuestEnding($data['topic_id'])) {
							echo "[ERROR] Failed to manage quest ending with topic_id = $topic_id" . PHP_EOL;
							continue;
						}
						if(!RPGQuests::endQuest($data['topic_id'])) {
							echo "[ERROR] Failed to end quest ending with topic_id = $topic_id" . PHP_EOL;
							continue;
						}
					}
				}
				break;
			case QUEST_TYPE_SURVIVAL:
				break;
		}
	}
}

echo "End of script";
?>