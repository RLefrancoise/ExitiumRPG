<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGXP.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewstatus' => 'viewstatus.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//play BGM ?
if($player->soundEnabled()) {
	$t->assign_block_vars('background_music', array());
}

//---user---
$regdate	= phpbb_gmgetdate($user->data['user_regdate']);
if($regdate == 0) $regdate = '-';
else {
	$rd_day = (strlen($regdate['mday']) < 2) ? '0' . $regdate['mday'] : $regdate['mday'];
	$rd_mon = (strlen($regdate['mon']) < 2) ? '0' . $regdate['mon'] : $regdate['mon'];
	$regdate = $rd_day.'/'.$rd_mon.'/'.$regdate['year'];
}

$lastvisit	= phpbb_gmgetdate($user->data['user_lastvisit']);
if($lastvisit == 0) $lastvisit = '-';
else {
	if($user->data['user_lastvisit'] > $user->data['user_regdate']) {
		$lv_day = (strlen($lastvisit['mday']) < 2) ? '0' . $lastvisit['mday'] : $lastvisit['mday'];
		$lv_mon = (strlen($lastvisit['mon']) < 2) ? '0' . $lastvisit['mon'] : $lastvisit['mon'];
		$lastvisit = $lv_day.'/'.$lv_mon.'/'.$lastvisit['year'];

	}
	else $lastvisit = $regdate;
}

$t->assign_vars(array(
	
	/* character info */
	'USER_STATUS' 	=> $user->data['username'],
	//'USER_AVATAR' 	=> "./images/avatars/upload/".get_avatar_filename($user->data['user_avatar']),
	'USER_AVATAR' 	=> "./download/file.php?avatar=" . $user->data['user_avatar'],
	'AVATAR_INFO'	=> "<strong>Informations diverses</strong><br>Energie : {$player->getEnergy()}/". MAX_ENERGY ."<br>Honneur : {$player->getHonor()} points<br>Salaire : {$player->getSalary()} Ralz<br><br>" . $player->getPlayerStats()->toHTMLString(),
	
	'USER_KARMA_BONUS' => $player->getKarma() * 10,
	
	'USER_LEVEL'	=> $player->getLevel(),
	
	/* user info */
	'USER_REGDATE'	=> $regdate,
	'USER_LASTVISIT'=> $lastvisit,
	'USER_MSG_NB'	=> $user->data['user_posts'],
));

//karma
$karma = $player->getKarma();
$i = 0;
while($i < $karma) {
	$t->assign_block_vars('karma_bloc', array(
		'KARMA_IMAGE'	=> 'Karma',
	));
	$i++;
}

while(5 - $i > 0) {
	$t->assign_block_vars('karma_bloc', array(
		'KARMA_IMAGE'	=> 'karma_empty',
	));
	$i++;
}

$t->pparse('viewstatus');

?>