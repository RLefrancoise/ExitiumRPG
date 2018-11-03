<?php

header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');

include_once($phpbb_root_path . 'rpg/admin/php/file_functions.php');

include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
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

$directory = get_directory_files($phpbb_root_path . 'rpg/sound/mp3');
if(!$directory) { echo 'error'; return; }

$html = '<option value=""></option>' . PHP_EOL;

foreach($directory as $file) {
	if($file == "." or $file == "..") continue;
	if(strripos($file, ".mp3") === false) continue;
	$file = substr($file, 0, strlen($file) - 4);
	$f = utf8_encode($file);
	$html .= "<option value=\"$f\">$f</option>" . PHP_EOL;
}

echo $html;

?>