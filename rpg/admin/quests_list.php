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
$t->set_filenames(array('quests_list' => 'quests_list.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$quests = RPGQuests::getQuests();

global $db;

foreach($quests as $q) {
	
	$sql = 'SELECT forum_name
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . $q->getForumId();
	$result = $db->sql_query($sql);
	$info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	
	$forum_name = $info['forum_name'];
	
	$t->assign_block_vars('quest_bloc', array(
		'NAME'		=> $q->getName(),
		'DESC'		=> $q->getDesc(),
		'TYPE'		=> $q->getType(),
		'DATE'		=> date('d/m/y', $q->getDate()),
		'AVAILABLE'	=> $q->isAvailable() ? 'oui' : 'non',
		'UNIQUE'	=> $q->isUnique() ? 'oui' : 'non',
		'POSTS'		=> $q->getRequiredPosts(),
		'FORUM'		=> $forum_name,
		'MONSTER'	=> $q->getType() == QUEST_TYPE_BATTLE ? $q->getMonsterId() : '',
		'BGM'		=> $q->getBGM(),
		'BACKGROUND' => $q->getBackground(),
		'XP'		=> $q->getXP(),
		'RALZ'		=> $q->getRalz(),
	));
}

$t->pparse('quests_list');

?>