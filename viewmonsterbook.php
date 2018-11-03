<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include_once($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGBattleAreas.class.php');
include_once('./rpg/database/RPGMonsterBooks.class.php');
include_once($phpbb_root_path . 'rpg/classes/rpgconfig.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "<p>Vous n'êtes pas connecté.</p>";
	die();
}

$t = new CustomTemplate('./rpg/tpl');
$t->set_filenames(array('viewmonsterbook' => 'viewmonsterbook.tpl'));

//session
$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'BACK_LINK'		=> append_sid("{$phpbb_root_path}index.$phpEx"),
));



$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id'], PLAYER_GENERAL);
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

$mb = RPGMonsterBooks::getMonsterBook($player->getId());

$areas = RPGBattleAreas::getAreas();

foreach($areas as $area) {
	$t->assign_block_vars('area_bloc', array(
		'AREA_NAME'	=>	$area->getName(),
	));
	
	$parts = $area->getAreaParts();
	foreach($parts as $part) {
		
		//iterate on every monsters of this part
		$monsters = $part->getMonsters();
		foreach($monsters as $monster) {
			$monster_stats = $mb->getMonsterStats($monster->getId(), $part->getId());
			if(!$monster_stats) {
				$t->assign_block_vars('area_bloc.unknown_monster_bloc', array());
			} else {
				$t->assign_block_vars('area_bloc.monster_bloc', array(
					'MONSTER_NAME'			=>	$monster->getName(),
					'MONSTER_LEVEL'			=>	$monster->getLevel(),
					'MONSTER_PV'			=>	$monster->getPV(),
					'MONSTER_PF'			=>	$monster->getPF(),
					'MONSTER_ATTACK'		=>	$monster->getAttack(),
					'MONSTER_DEFENSE'		=>	$monster->getDefense(),
					'MONSTER_SPEED'			=>	$monster->getSpeed(),
					'MONSTER_FLUX'			=>	$monster->getFlux(),
					'MONSTER_RESISTANCE'	=>	$monster->getResistance(),
					'MONSTER_EXP'			=>	$monster->getXP(),
					'MONSTER_RALZ'			=>	$monster->getRalz(),
					'MONSTER_AREA_PART'		=>	$part->getName(),
					'MONSTER_ENCOUNTERS'	=>	$monster_stats['encounters'],
					'MONSTER_WINS'			=>	$monster_stats['wins'],
					'MONSTER_LOSES'			=>	$monster_stats['loses'],
					'MONSTER_IMG'			=>	$monster->getImage(),
				));
			}
		}
	}
}
	
$t->pparse('viewmonsterbook');

?>