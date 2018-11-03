<?php
 
header("Content-Type: text/plain");

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./rpg/database/RPGWeapons.class.php');
include_once('./rpg/database/RPGBlackMarket.class.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');
include_once('./rpg/database/RPGPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}

$s = (isset($_GET["s"])) ? $_GET["s"] : NULL;

if ( $s !== null ) {
	$upgrade = RPGBlackMarket::getItemByCategoryAndPlace(CATEGORY_UPGRADES, $s);
	$grade = $upgrade->getGrade();
	
	if(!$player->getWeapon()->isUnderGrade($grade)) { echo 'error'; die(); } //no downgrades
	//if($player->getWeapon()->getGrade() >= $grade){ echo 'error'; die(); } //no downgrades
	
	$success_rate = $upgrade->getSuccessRate();
	$price = $upgrade->getPrice();
	
	//test enough money here
	if($player->getRalz() < $price) {
		echo 'no_money';
		die();
	}
	
	$db->sql_transaction('begin');
	
	//deplete money
	$ralz = $player->getRalz();
	RPGPlayers::setRalzByPlayer($player, $ralz - $price);
	
	//success ?
	$random = mt_rand(0, 100);
	if( ($random != 0) and ($random <= $success_rate) ){ //upgrade success
		//verify the grade value
		switch($grade) {
			case WEAPON_GRADE_C:
			case WEAPON_GRADE_B:
			case WEAPON_GRADE_A:
			case WEAPON_GRADE_S:
			case WEAPON_GRADE_SS:
				RPGWeapons::setWeaponGradeByPlayer($player, $grade);
				break;
			default:
				break;
		}
		echo 'upgrade_success';
	} else {
		echo 'upgrade_failure';
	}
	$db->sql_transaction('commit');
	
} else {
    echo "error";
}
 
?>