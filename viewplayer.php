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
$t->set_filenames(array('viewplayer' => 'viewplayer.tpl'));

$id = request_var('id', -1);
if($id == -1) { echo 'error'; return; }

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);

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



$to_display = RPGUsersPlayers::getPlayerByUserId($id);
if($to_display == null) { echo 'Aucun joueur trouvé.'; return; }

$to_display_data = RPGUsersPlayers::getUserData($id);


//---user---
$regdate	= phpbb_gmgetdate($to_display_data['user_regdate']);
if($regdate == 0) $regdate = '-';
else {
	$rd_day = (strlen($regdate['mday']) < 2) ? '0' . $regdate['mday'] : $regdate['mday'];
	$rd_mon = (strlen($regdate['mon']) < 2) ? '0' . $regdate['mon'] : $regdate['mon'];
	$regdate = $rd_day.'/'.$rd_mon.'/'.$regdate['year'];
}

$lastvisit	= phpbb_gmgetdate($to_display_data['user_lastvisit']);
if($lastvisit == 0) $lastvisit = '-';
else {
	if($to_display_data['user_lastvisit'] > $to_display_data['user_regdate']) {
		$lv_day = (strlen($lastvisit['mday']) < 2) ? '0' . $lastvisit['mday'] : $lastvisit['mday'];
		$lv_mon = (strlen($lastvisit['mon']) < 2) ? '0' . $lastvisit['mon'] : $lastvisit['mon'];
		$lastvisit = $lv_day.'/'.$lv_mon.'/'.$lastvisit['year'];

	}
	else $lastvisit = $regdate;
}

$t->assign_vars(array(
	
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}memberlist.$phpEx", "mode=viewprofile&u=$id"),
	
	/* character info */
	'USER_STATUS' 	=> $to_display_data['username'],
	'USER_AVATAR' 	=> "./download/file.php?avatar=" . $to_display_data['user_avatar'],
	
	/*'USER_KARMA_BONUS' => $player->getKarma() * 10,*/
	
	'USER_LEVEL'	=> $to_display->getLevel(),
	
	/* user info */
	'USER_INTRO_LINK'	=> ($to_display->getIntroductionLink() != '') ? $to_display->getIntroductionLink() : '#',
	'USER_REGDATE'	=> $regdate,
	'USER_LASTVISIT'=> $lastvisit,
	'USER_MSG_NB'	=> $to_display_data['user_posts'],
));

//---user---
$age		= '-';

if ($config['allow_birthdays'] && $to_display_data['user_birthday'])
{
	list($bday_day, $bday_month, $bday_year) = array_map('intval', explode('-', $to_display_data['user_birthday']));

	if ($bday_year)
	{
		$now = phpbb_gmgetdate(time() + $user->timezone + $user->dst);

		$diff = $now['mon'] - $bday_month;
		if ($diff == 0)
		{
			$diff = ($now['mday'] - $bday_day < 0) ? 1 : 0;
		}
		else
		{
			$diff = ($diff < 0) ? 1 : 0;
		}

		$age = max(0, (int) ($now['year'] - $bday_year - $diff));
	}
}

$t->assign_vars(array(
	/* user info */
	'USERNAME'				=> $to_display_data['username'],
	'USER_AGE'				=> $age,
	'USER_GENDER'			=> $to_display->getGender(),
	'USER_FROM'				=> $to_display_data['user_from'],
	'USER_ORGANISATION'		=> ($to_display->getOrganisation() !== null) ? $to_display->getOrganisation()->getName() : '',
	'USER_RANK'				=> RPGUsersPlayers::getUserRank($to_display_data['user_id']),
));


//equipment

// WEAPON
if($to_display->getWeapon() !== null) {
	$weapon = $to_display->getWeapon()->getName();
}

// CLOTH
$cloth = $to_display->getArmorPartName(ARMOR_CLOTH);

// LEGGINGS
$leggings = $to_display->getArmorPartName(ARMOR_LEGGINGS);

// GLOVES
$gloves = $to_display->getArmorPartName(ARMOR_GLOVES);

// SHOES
$shoes = $to_display->getArmorPartName(ARMOR_SHOES);

$t->assign_vars(array(
	/* character info */
	'USER_WEAPON' 	=> $weapon,
	'USER_CLOTH'  	=> $cloth,
	'USER_LEGGINGS' => $leggings,
	'USER_GLOVES'   => $gloves,
	'USER_SHOES'	=> $shoes,
));



//karma
/*$karma = $player->getKarma();
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
}*/

$t->pparse('viewplayer');
?>