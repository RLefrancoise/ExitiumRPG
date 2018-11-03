<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo 'error';
	die();
}

//$fileName = request_var('f', '');
$fileName = urldecode($_GET['f']);
if($fileName === '') { echo 'error'; die(); }

$pdf = file_get_contents($phpbb_root_path . 'files/' . $fileName . '.pdf');
if($pdf === FALSE) { echo 'error'; die(); }

header('Content-Type: application/pdf');
//header("Location: http://mozilla.github.io/pdf.js/web/viewer.html?file=" . SITE_URL . "files/$fileName.pdf#zoom=page-fit");

echo $pdf;