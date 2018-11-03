<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGClothes.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGSyringes.class.php');

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

$t = new CustomTemplate($phpbb_root_path . 'rpg/admin/tpl');
$t->set_filenames(array('items_list' => 'items_list.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$syringes = RPGSyringes::getSyringes();

foreach($syringes as $syringe) {
	
	$t->assign_block_vars('syringe_bloc', array(
		'ID'		=> $syringe->getId(),
		'NAME'		=> $syringe->getName(),
		'DESC'		=> $syringe->getDescription(),
		'PRICE'		=> $syringe->getPrice(),
		'IMG'		=> $syringe->getIcon(),
		'USABLE_OUTSIDE_BATTLE'	=> $syringe->IsUsableOutsideBattle() ? 'oui' : 'non',
		'PV'		=> $syringe->getPV(),
		'MAX_PV'	=> $syringe->getMaxPV(),
		'PF'		=> $syringe->getPF(),
		'MAX_PF'	=> $syringe->getMaxPF(),
		'ATTACK'	=> $syringe->getAttack(),
		'DEFENSE'	=> $syringe->getDefense(),
		'SPEED'		=> $syringe->getSpeed(),
		'FLUX'		=> $syringe->getFlux(),
		'RESISTANCE' => $syringe->getResistance(),
	));
}

$clothes = RPGClothes::getClothes();

foreach($clothes as $c) {
	$t->assign_block_vars('clothes_bloc', array(
		'ID'		=> $c->getId(),
		'NAME'		=> $c->getName(),
		'DESC'		=> $c->getDescription(),
		'PRICE'		=> $c->getPrice(),
		'IMG'		=> $c->getIcon(),
		'PV'		=> $c->getPV(),
		'PF'		=> $c->getPF(),
		'ATTACK'	=> $c->getAtk(),
		'DEFENSE'	=> $c->getDef(),
		'SPEED'		=> $c->getVit(),
		'FLUX'		=> $c->getFlux(),
		'RESISTANCE' => $c->getRes(),
		'LEVEL'		=> $c->getRequiredLevel(),
	));
}

$leggings = RPGLeggings::getLeggings();

foreach($leggings as $l) {
	$t->assign_block_vars('leggings_bloc', array(
		'ID'		=> $l->getId(),
		'NAME'		=> $l->getName(),
		'DESC'		=> $l->getDescription(),
		'PRICE'		=> $l->getPrice(),
		'IMG'		=> $l->getIcon(),
		'PV'		=> $l->getPV(),
		'PF'		=> $l->getPF(),
		'ATTACK'	=> $l->getAtk(),
		'DEFENSE'	=> $l->getDef(),
		'SPEED'		=> $l->getVit(),
		'FLUX'		=> $l->getFlux(),
		'RESISTANCE' => $l->getRes(),
		'LEVEL'		=> $l->getRequiredLevel(),
	));
}

$gloves = RPGGloves::getGloves();

foreach($gloves as $g) {
	$t->assign_block_vars('gloves_bloc', array(
		'ID'		=> $g->getId(),
		'NAME'		=> $g->getName(),
		'DESC'		=> $g->getDescription(),
		'PRICE'		=> $g->getPrice(),
		'IMG'		=> $g->getIcon(),
		'PV'		=> $g->getPV(),
		'PF'		=> $g->getPF(),
		'ATTACK'	=> $g->getAtk(),
		'DEFENSE'	=> $g->getDef(),
		'SPEED'		=> $g->getVit(),
		'FLUX'		=> $g->getFlux(),
		'RESISTANCE' => $g->getRes(),
		'LEVEL'		=> $g->getRequiredLevel(),
	));
}

$shoes = RPGShoes::getShoes();

foreach($shoes as $s) {
	$t->assign_block_vars('shoes_bloc', array(
		'ID'		=> $s->getId(),
		'NAME'		=> $s->getName(),
		'DESC'		=> $s->getDescription(),
		'PRICE'		=> $s->getPrice(),
		'IMG'		=> $s->getIcon(),
		'PV'		=> $s->getPV(),
		'PF'		=> $s->getPF(),
		'ATTACK'	=> $s->getAtk(),
		'DEFENSE'	=> $s->getDef(),
		'SPEED'		=> $s->getVit(),
		'FLUX'		=> $s->getFlux(),
		'RESISTANCE' => $s->getRes(),
		'LEVEL'		=> $s->getRequiredLevel(),
	));
}

$specials = RPGSpecials::getSpecials();

foreach($specials as $s) {
	$t->assign_block_vars('specials_bloc', array(
		'ID'		=> $s->getId(),
		'NAME'		=> $s->getName(),
		'DESC'		=> $s->getDescription(),
		'PRICE'		=> $s->getPrice(),
		'IMG'		=> $s->getIcon(),
		'EFFECT'	=> $s->getEffect(),
	));
}

$sets = RPGSets::getSets();

foreach($sets as $s) {
	$t->assign_block_vars('sets_bloc', array(
		'ID'		=> $s->getId(),
		'NAME'		=> $s->getName(),
		'DESC'		=> $s->getDescription(),
		'PRICE'		=> $s->getPrice(),
		'CLOTH'		=> $s->getCloth()->getName(),
		'LEGGINGS'	=> $s->getLeggings()->getName(),
		'GLOVES'	=> $s->getGloves()->getName(),
		'SHOES'		=> $s->getShoes()->getName(),
		'PV'		=> $s->getPV(),
		'PF'		=> $s->getPF(),
		'ATTACK'	=> $s->getAtk(),
		'DEFENSE'	=> $s->getDef(),
		'SPEED'		=> $s->getVit(),
		'FLUX'		=> $s->getFlux(),
		'RESISTANCE' => $s->getResistance(),
	));
}

$orbs = RPGOrbs::getOrbs();

foreach($orbs as $o) {

	$t->assign_block_vars('orbs_bloc', array(
		'ID'		=> $o->getId(),
		'NAME'		=> $o->getName(),
		'DESC'		=> $o->getDescription(),
		'IMG'		=> $o->getIcon(),
		'PRICE'		=> $o->getPrice(),
		'ATTACK'	=> $o->getAttack(),
		'DEFENSE'	=> $o->getDefense(),
		'SPEED'		=> $o->getSpeed(),
		'FLUX'		=> $o->getFlux(),
		'RESISTANCE' => $o->getResistance(),
		'PV'		=> $o->getPV(),
		'PF'		=> $o->getPF(),
		'EFFECT'	=> $o->getEffect(),
		'TRIGGER'	=> $o->getEffectTrigger(),
		'SLOTS'		=> $o->getSize(),
	));
}


$t->pparse('items_list');

?>