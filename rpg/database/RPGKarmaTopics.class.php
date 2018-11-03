<?php

include_once(__DIR__ . '/../../common.php');

class RPGKarmaTopics {

	public static function userHasEndRP($forum_id, $topic_id, $user_id) {
		global $db;
		
		$sql = 'SELECT DISTINCT *
				FROM rpg_karma_topics
				WHERE user_id = ' . (int) $db->sql_escape($user_id) . '
				AND forum_id = '  . (int) $db->sql_escape($forum_id) . '
				AND topic_id = '  . (int) $db->sql_escape($topic_id);
		$result = $db->sql_query($sql);
		$info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		if(!$info) return false;
		else return true;
	}
	
	public static function getUsersIDSOfPosts($forum_id, $topic_id) {
		global $db;
		
		$sql = 'SELECT DISTINCT poster_id
				FROM ' . POSTS_TABLE . '
				WHERE forum_id = ' . (int) $db->sql_escape($forum_id) . '
				AND topic_id = ' . (int) $db->sql_escape($topic_id);
		$result = $db->sql_query($sql);
		
		$users = array();
		
		while($info = $db->sql_fetchrow($result)) {
			$users[] = (int) $info['poster_id'];
		}
		
		$db->sql_freeresult($result);
		
		return $users;
	}
	
	public static function getNumberOfPostsInTopic($forum_id, $topic_id, $user_id) {
		global $db;
		
		$sql = 'SELECT *
				FROM ' . POSTS_TABLE . '
				WHERE poster_id = ' . (int) $db->sql_escape($user_id) . '
				AND topic_id = ' . (int) $db->sql_escape($topic_id) . '
				AND forum_id = ' . (int) $db->sql_escape($forum_id);
				
		//echo $sql;
		
		$result = $db->sql_query($sql);
		
		$count = 0;
		
		while($db->sql_fetchrow($result)) { $count++; }
		//$count = (int) $db->sql_fetchfield('post_counter');
		
		$db->sql_freeresult($result);
		
		//echo "count : $count";
		
		return $count;
	}
	
	public static function endRP($forum_id, $topic_id, $user_id) {
		global $db;
		
		$data_array = array(
				'forum_id'			=> (int) $forum_id,
				'topic_id'			=> (int) $topic_id,
				'user_id'			=> (int) $user_id,
			);
			
		$sql = 'INSERT INTO rpg_karma_topics ' . $db->sql_build_array('INSERT', $data_array);
		$db->sql_query($sql);
		
		return ($db->sql_affectedrows() > 0);
	}
}

?>