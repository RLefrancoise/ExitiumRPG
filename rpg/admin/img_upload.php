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

$img_dir = '';

switch($mode) {
	case 'icon':
		$width = 42;
		$height = 42;
		$img_dir = $phpbb_root_path . 'images/rpg/icons/';
		break;
	case 'monster':
		$width = 200;
		$height = 300;
		$img_dir = $phpbb_root_path . 'images/rpg/battle/monsters/';
		break;
	default:
		break;
}


$name = request_var('img', '', true);

$upload_state = upload_image('img', $img_dir, $width, $height, 500000, $name, $_FILES['img']['name']);

echo "Statut de l'upload : " . $upload_state;

if($upload_state == 'upload_ok')
	echo "<br>Nom final : $name";
		
?>