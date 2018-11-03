<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGQuests.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

// Have they authenticated (again) as an admin for this session?
/*if (!isset($user->data['session_admin']) || !$user->data['session_admin'])
{
	login_box('', $user->lang['LOGIN_ADMIN_CONFIRM'], $user->lang['LOGIN_ADMIN_SUCCESS'], true, false);
}*/

// Is user any type of admin? No, then stop here, each script needs to
// check specific permissions but this is a catchall
if (!$auth->acl_get('a_'))
{
	trigger_error('NO_ADMIN');
}

$t = new CustomTemplate($phpbb_root_path . 'rpg/admin/tpl');
$t->set_filenames(array('active_quests_list' => 'active_quests_list.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$data = RPGQuests::getActiveQuestsData();

global $db;

foreach($data as $q) {
	
	$quest = RPGQuests::getQuest($q['quest_id']);
	$leader = RPGUsersPlayers::getPlayerByPlayerId($q['player_id']);
	
	$sql = 'SELECT forum_name
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . $q['forum_id'];
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$forum_name = $info['forum_name'];
	
	$sql = 'SELECT topic_title
			FROM ' . TOPICS_TABLE . '
			WHERE topic_id = ' . $q['topic_id'];
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$topic_name = $info['topic_title'];
	
	$sql = 'SELECT u.username
			FROM rpg_active_quests_members AS qm, rpg_users_players AS p, ' . USERS_TABLE . ' AS u
			WHERE qm.topic_id = ' . $q['topic_id'] . '
			AND p.player_id = qm.member_id
			AND u.user_id = p.user_id
			ORDER BY u.username';
	$result = $db->sql_query($sql);
	
	$members = '';
	
	while($info = $db->sql_fetchrow($result)) {
		$members .= $info['username'] . '<br>';
	}
	
	$db->sql_freeresult($result);
	
	//number of posts
	$sql = 'SELECT *
			FROM ' . POSTS_TABLE . '
			WHERE topic_id = ' . $q['topic_id'] . '
			AND forum_id = ' . $q['forum_id'] . '
			AND poster_id != ' . RPG_POST_USER_ID;
	
	$result = $db->sql_query($sql);
	
	$count = 0;
	
	while($db->sql_fetchrow($result)) { $count++; }
	
	$db->sql_freeresult($result);
	
	
	
	$t->assign_block_vars('quest_bloc', array(
		'ID'		=> $q['quest_id'],
		'NAME'		=> $quest->getName(),
		'LEADER'	=> $leader->getName(),
		'MEMBERS'	=> $members,
		'FORUM'		=> $forum_name,
		'TOPIC'		=> $topic_name,
		'STARTED'	=> $q['is_started'] ? 'oui' : 'non',
		'OPENED'	=> $q['is_opened'] ? 'oui' : 'non',
		'POSTS'		=> $count,
		'REQUIRED_POSTS'	=> $quest->getRequiredPosts(),
		'RIDDLE_ID'	=> $q['riddle_id'],
		'BATTLE_TOKEN'	=> $q['battle_token'],
	));
}

$t->pparse('active_quests_list');

?>