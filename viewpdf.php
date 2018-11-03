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

$mode = request_var('mode', '');

if($mode === 'getchapters') {

	if($user->data['username'] == "Anonymous") {
		echo "not_connected";
		exit;
	}

	$res = array();

	$pdf_folder = $phpbb_root_path . 'files/';

	$chapter_num = 1;

	$files = scandir($pdf_folder);

	foreach($files as $file) {

		//ignore subdirs if any
		if(is_dir($file)) continue;

		$_ = explode(".", $file);

		$ext = $_[count($_) - 1];

		$file_noext = '';


		for($i = 0 ; $i < count($_) - 1; $i++) {
			$file_noext .= $_[$i];
			if($i < count($_) - 2) $file_noext .= '.';
		}

		$_[0] = $file_noext;

		if(strcmp($ext, 'pdf') === 0) {

			//$file_noext = $_[0];

			$_ = explode("-", $_[0]);

			$res[/*$file_noext*/ utf8_encode($file_noext)] = array(
				'chapter'	=>	$_[0], //utf8_encode($_[0]),
				'title'		=>	$_[1], //utf8_encode($_[1]),
			);

		}

		//if filename matches the pattern    <text> - <text>.pdf

		/*if(preg_match('/^(?<chapter>\S?\s)-\s(?<title>\S?)\.pdf$/', $file, $matches )){

			$res[$file] = array(
				'chapter'	=>	$matches['chapter'],
				'title'		=>	$matches['title'],
			);
		}*/
	}

	header('Content-Type: application/json');
	echo json_encode($res, JSON_FORCE_OBJECT);
	exit;
}




if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewpdf' => 'viewpdf.tpl'));

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL);

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//session
$t->assign_vars(array(
	'URL'	=>	SITE_URL,
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));

$t->pparse('viewpdf');


?>