<?php

define('IN_PHPBB', true);
$phpbb_root_path = '../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGRPForums.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.php');
include_once($phpbb_root_path . 'rpg/php/post_functions.php');

$user->session_begin();
$auth->acl($user->data);
$user->setup();

//for every forums
$sql = 'SELECT *
		FROM ' . FORUMS_TABLE;
$result = $db->sql_query($sql);

while($info = $db->sql_fetchrow($result)) {
	$forum_id = $info['forum_id'];
	
	//if not RP forum, ignore it
	if(!RPGRPForums::forumIsRP($forum_id)) continue;
	
	//get topics in current forum
	$sql = 'SELECT DISTINCT *
			FROM ' . FORUMS_TABLE . ' AS f, ' . TOPICS_TABLE . ' AS t
			WHERE f.forum_id = ' . $forum_id . '
			AND t.forum_id = f.forum_id';
	$topics = $db->sql_query($sql);
	
	//for each topic found
	while($topic = $db->sql_fetchrow($result)) {
		
		//if topic is locked, no need to check rp turns
		if($topic['topic_status'] == ITEM_LOCKED) continue;
		
		//get posts of topic
		$sql = 'SELECT DISTINCT *
				FROM ' . POSTS_TABLE . ' as p, ' . TOPICS_TABLE . ' AS t
				WHERE p.topic_id = t.topic_id
				AND p.topic = ' . $topic['topic_id'] . '
				ORDER BY post_time';
		$posts = $db->sql_query($sql);
		
		$posters = array();
		$posters_order = array();
		
		//for each post
		while($post = $db->sql_fetchrow($posts)) {
			$posters[] = $post;
			
			if(!in_array($post['poster_id'], $posters_order)) $posters_order[] = array('username' => $post['poster_username'], 'id' => $post['poster_id']);
		}
		
		//skip turn if only 2 users or less is useless
		if(count($posters_order) <= 2) continue;
		
		$auto_posts = 0;
		$last_poster_id = -1;
		
		$i = count($posters) - 1;
		
		for($i = count($posters) - 1; $i >= 0 ; $i--) {
			if($posters[$i]['id'] == RPG_POST_USER_ID) {
				$auto_posts++;
				continue;
			}
			
			//break on the last poster which is not an automatic poster
			break;
		}
		
		//if next poster still has time to answer, continue
		if(time() - $posters[$i]['post_time'] < 60 * 60 * 24 * 2) {
			continue;
		}
		
		//else, compute next poster username and post automessage
		$last_poster_id = $posters[$i]['id'];
		
		$j = 0;
		
		for($j = 0 ; $j < count($poster_order) - 1 ; $j++) {
			if($poster_order[$j] == $last_poster_id) break;
		}
		
		$next_poster_name = $poster_order[(($j + $auto_posts + 1) % count($poster_order))]['username'];
		
		rpg_post('Temps de réponse expiré', "{$posters[$i]['username']} passe son tour. C'est au tour de $next_poster_name de répondre.", 'reply', $forum_id, $topic['topic_id']);
	}
}

?>