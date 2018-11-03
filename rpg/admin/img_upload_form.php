<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/php/upload_functions.php');

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

$mode = request_var('mode', '');
if($mode == '') { echo 'Mode invalide.'; return; }
if($mode != 'icon' and $mode != 'monster') { echo 'Mode invalide.'; return; }

if($mode == 'icon') $info = "42x42 maximum pour les icones d'objet, 500ko maximum.";
else if($mode == 'monster') $info = "200x300 maximum pour les images de monstre. 500ko maximum.";

$t = new CustomTemplate($phpbb_root_path . 'rpg/admin/tpl');
$t->set_filenames(array('img_upload' => 'img_upload.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'MODE'	=> $mode,
	'INFO'	=> $info,
));

$t->pparse('img_upload');

?>