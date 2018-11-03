<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include('./template/template.php');
include_once('./rpg/database/RPGClans.class.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewclanpage' => 'viewclanpage.tpl'));

//HD
$t->assign_vars(array(
	'SD_CSS'	=> $player->hdEnabled() ? '' : '_sd',
	'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
	'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}viewclan.$phpEx"),
));

$clan_id = request_var('id', '');
if($clan_id == '') {
	die();
}

$clan = RPGClans::getClan($clan_id);
if(!$clan) {
	echo "Aucun clan trouvé.";
	return;
}

// check viewing mode
if(RPGClans::isClanLeader($user->data['user_id'], $clan_id)) {
	$mode = 'leader';
	$t->assign_block_vars('clan_button_bloc', array(
		'BUTTON_MODE'	=> 'delete_button',
	));
	
	$t->assign_block_vars('pi_link', array());
	
	$t->assign_block_vars('pi_menu', array(
		/*'CLAN_PI'		=>	$clan->getPI(),
		'ATK_LEVEL'		=>	$clan->getAttackLevel() < 6 	? $clan->getAttackLevel() + 1 : '--',
		'DEF_LEVEL'		=>	$clan->getDefenseLevel() < 6 	? $clan->getDefenseLevel() + 1 : '--',
		'SPD_LEVEL'		=>	$clan->getSpeedLevel() < 6 		? $clan->getSpeedLevel() + 1 : '--',
		'FLUX_LEVEL'	=>	$clan->getFluxLevel() < 6 		? $clan->getFluxLevel() + 1 : '--',
		'RES_LEVEL'		=>	$clan->getResistanceLevel() < 6 ? $clan->getResistanceLevel() + 1 : '--',
		'PV_LEVEL'		=>	$clan->getPVLevel() < 6 		? $clan->getPVLevel() + 1 : '--',
		'PF_LEVEL'		=>	$clan->getPFLevel() < 6 		? $clan->getPFLevel() + 1 : '--',
		
		'ATK_PI'		=>	$clan->getAttackLevel() < 6		? RPGConfig::$_CLAN_PI_RALZ[$clan->getAttackLevel() + 1] : '--',
		'DEF_PI'		=>	$clan->getDefenseLevel() < 6	? RPGConfig::$_CLAN_PI_RALZ[$clan->getDefenseLevel() + 1] : '--',
		'SPD_PI'		=>	$clan->getSpeedLevel() < 6		? RPGConfig::$_CLAN_PI_RALZ[$clan->getSpeedLevel() + 1] : '--',
		'FLUX_PI'		=>	$clan->getFluxLevel() < 6		? RPGConfig::$_CLAN_PI_RALZ[$clan->getFluxLevel() + 1] : '--',
		'RES_PI'		=>	$clan->getResistanceLevel() < 6	? RPGConfig::$_CLAN_PI_RALZ[$clan->getResistanceLevel() + 1] : '--',
		'PV_PI'			=>	$clan->getPVLevel() < 6			? RPGConfig::$_CLAN_PI_RALZ[$clan->getPVLevel() + 1] : '--',
		'PF_PI'			=>	$clan->getPFLevel() < 6			? RPGConfig::$_CLAN_PI_RALZ[$clan->getPFLevel() + 1] : '--',
		
		'ATK_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getAttackLevel() + 1][STAT_ATTACK],
		'DEF_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getDefenseLevel() + 1][STAT_DEFENSE],
		'SPD_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getSpeedLevel() + 1][STAT_SPEED],
		'FLUX_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getFluxLevel() + 1][STAT_FLUX],
		'RES_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getResistanceLevel() + 1][STAT_RESISTANCE],
		'PV_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getPVLevel() + 1][STAT_PV],
		'PF_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getPFLevel() + 1][STAT_PF],*/
	));
}
else if(RPGClans::isClanMember($user->data['user_id'], $clan_id)) {
	$mode = 'member';
	$t->assign_block_vars('clan_button_bloc', array(
		'BUTTON_MODE'	=> 'quit_button',
	));
	
	$t->assign_block_vars('no_pi_link', array());
}
else {
	$mode = 'newcommer';
	$t->assign_block_vars('clan_button_bloc', array(
		'BUTTON_MODE'	=> 'join_button',
	));
	
	$t->assign_block_vars('no_pi_link', array());
}


$clan_info =  "<strong>Informations diverses</strong><br>";
$clan_info .= "Honneur : " . $clan->getHonor() . " points<br>";
$clan_info .= "Points d'influence : " . $clan->getPI() . " PI<br>";

if($clan->hasAnyStatBonus()) {

	$atk_bonus = $clan->getStatBonus(STAT_ATTACK);
	$def_bonus = $clan->getStatBonus(STAT_DEFENSE);
	$spd_bonus = $clan->getStatBonus(STAT_SPEED);
	$flux_bonus = $clan->getStatBonus(STAT_FLUX);
	$res_bonus = $clan->getStatBonus(STAT_RESISTANCE);
	$pv_bonus = $clan->getStatBonus(STAT_PV);
	$pf_bonus = $clan->getStatBonus(STAT_PF);
	
	$clan_info .= "<br><strong>Buffs de clan</strong><br>";
	if($atk_bonus) 	$clan_info .= "Attaque +{$atk_bonus}<br>";
	if($def_bonus) 	$clan_info .= "Defense +{$def_bonus}<br>";
	if($spd_bonus) 	$clan_info .= "Vitesse +{$spd_bonus}<br>";
	if($flux_bonus) $clan_info .= "Flux +{$flux_bonus}<br>";
	if($res_bonus) 	$clan_info .= "Résistance +{$res_bonus}<br>";
	if($pv_bonus) 	$clan_info .= "PV +{$pv_bonus}<br>";
	if($pf_bonus) 	$clan_info .= "PF +{$pf_bonus}";
}

$t->assign_vars(array(
	'CLAN_MODE'	=> $mode,
	'CLAN_ID'	=> $clan_id,
	'CLAN_NAME'	=> $clan->getName(),
	'CLAN_INFO'	=> $clan_info,
	'CLAN_IMAGE'	=> 'images/rpg/clans/see/clan_images/' . $clan->getImage(),
	'CLAN_LEADER'	=> $clan->getLeader()->getName(),
	'CLAN_LEVEL'	=> $clan->getLevel(),
	'CLAN_MEMBERS'	=> $clan->getMembersNumber(),
	'CLAN_RALZ_BONUS'	=> ($clan->getRalzBonus() > 0) ? '(+' . $clan->getRalzBonus() . '% RALZ)' : '',
	'CLAN_XP_BONUS'	=> ($clan->getXpBonus() > 0) ? '(+' . $clan->getXpBonus() . '% XP)' : '',
	'CLAN_DESC'		=> $clan->getDescription(),
));

if($mode != 'newcommer') {
	$t->assign_block_vars('members_display', array());
	
	$members = $clan->getMembers();

	foreach($members as $member) {
		$leader_menu = 'style="cursor:pointer" onclick="javascript:open_leader_menu(' . $member->getUserId() . ', event)"';
		$display_menu = false;
		if(RPGClans::isClanLeader($user->data['user_id'], $clan_id) and ($user->data['user_id'] != $member->getUserId())) $display_menu = true;
		
		$t->assign_block_vars('members_display.members_bloc', array(
			'MEMBER_NAME'	=> $member->getName(),
			'MEMBER_LEVEL'	=> $member->getLevel(),
			'LEADER_MENU'	=> ($display_menu ? $leader_menu : ''),
		));
	}
}

$t->pparse('viewclanpage');

?>