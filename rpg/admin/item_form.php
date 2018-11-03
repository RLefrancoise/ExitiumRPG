<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGSyringes.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGClothes.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGLeggings.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGGloves.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGShoes.class.php');

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

$type = request_var('type', '');
if($type == '') { echo 'type invalide'; return; }


$t = new CustomTemplate($phpbb_root_path . 'rpg/admin/tpl');

switch($type) {

	case 'syringe':
		$t->set_filenames(array('item_form' => 'syringe_form.tpl'));
		break;
	case 'clothes':
	case 'leggings':
	case 'gloves':
	case 'shoes':
		$t->set_filenames(array('item_form' => 'equip_form.tpl'));
		break;
	case 'specials':
		$t->set_filenames(array('item_form' => 'specials_form.tpl'));
		break;
	case 'orbs':
		$t->set_filenames(array('item_form' => 'orb_form.tpl'));
		break;
	case 'sets':
		$t->set_filenames(array('item_form' => 'set_form.tpl'));
		break;
	default:
		echo 'type invalide';
		return;
}

$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$mode = request_var('mode', '');
if($mode == '') {
	echo 'mode invalide';
	return;
}

$t->assign_vars(array(
	'MODE'	=> $mode,
));

if($type == 'clothes' or $type == 'leggings' or $type == 'gloves' or $type == 'shoes') {
	$t->assign_vars(array(
		'TYPE'		=>		$type,
	));
}

if($mode == 'edit') {

	switch($type) {
		case 'syringe':
			{
				$id = request_var('id', -1);
				if($id == -1) { echo 'ID invalide.'; return; }
				$s = RPGSyringes::getSyringe($id);
				
				$t->assign_vars(array(
					'ID'		=>		$s->getId(),
					'NAME'	 	=>	 	$s->getName(),
					'DESC'	 	=>		$s->getDescription(),
					'IMG'	 	=>		$s->getIcon(),
					'PRICE'	 	=>		$s->getPrice(),
					'PV'	 	=>		$s->getPV(),
					'MAX_PV' 	=>		$s->getMaxPV(),
					'PF'	 	=>		$s->getPF(),
					'MAX_PF' 	=>		$s->getMaxPF(),
					'ATTACK' 	=>		$s->getAttack(),
					'DEFENSE'	=>		$s->getDefense(),
					'SPEED'		=>		$s->getSpeed(),
					'FLUX'		=>		$s->getFlux(),
					'RESISTANCE'=>		$s->getResistance(),
					'OUTSIDE_CHECKED' =>	$s->isUsableOutsideBattle() ? 'checked' : '',
				));
			
			}
			break;
		
		case 'clothes':
		case 'leggings':
		case 'gloves':
		case 'shoes':
			{
				$id = request_var('id', -1);
				if($id == -1) { echo 'ID invalide.'; return; }
				
				if($type == 'clothes')
					$e = new SetPart(RPGClothes::getCloth($id), ARMOR_CLOTH);
				else if($type == 'leggings')
					$e = new SetPart(RPGLeggings::getLegging($id), ARMOR_LEGGINGS);
				else if($type == 'gloves')
					$e = new SetPart(RPGGloves::getGlove($id), ARMOR_GLOVES);
				else
					$e = new SetPart(RPGShoes::getShoe($id), ARMOR_SHOES);
					
				$t->assign_vars(array(
					'ID'		=>		$e->getId(),
					'NAME'	 	=>	 	$e->getName(),
					'DESC'	 	=>		$e->getDescription(),
					'IMG'	 	=>		$e->getIcon(),
					'PRICE'	 	=>		$e->getPrice(),
					'PV'	 	=>		$e->getPV(),
					'PF'	 	=>		$e->getPF(),
					'ATTACK' 	=>		$e->getAtk(),
					'DEFENSE'	=>		$e->getDef(),
					'SPEED'		=>		$e->getVit(),
					'FLUX'		=>		$e->getFlux(),
					'RESISTANCE'=>		$e->getRes(),
					'LEVEL' 	=>		$e->getRequiredLevel(),
				));
			}
			break;
			
		case 'specials':
			{
				$id = request_var('id', -1);
				if($id == -1) { echo 'ID invalide.'; return; }
				$s = RPGSpecials::getSpecial($id);
				
				$t->assign_vars(array(
					'ID'		=>		$s->getId(),
					'NAME'	 	=>	 	$s->getName(),
					'DESC'	 	=>		$s->getDescription(),
					'IMG'	 	=>		$s->getIcon(),
					'PRICE'	 	=>		$s->getPrice(),
					'EFFECT'	=>		$s->getEffect(),
				));
			}
			break;
			
		case 'orbs':
			{
				$id = request_var('id', -1);
				if($id == -1) { echo 'ID invalide.'; return; }
				$o = RPGOrbs::getOrb($id);
				
				$t->assign_vars(array(
					'ID'		=>		$o->getId(),
					'NAME'	 	=>	 	$o->getName(),
					'DESC'	 	=>		$o->getDescription(),
					'IMG'	 	=>		$o->getIcon(),
					'PRICE'	 	=>		$o->getPrice(),
					'PV'	 	=>		$o->getPV(),
					'PF'	 	=>		$o->getPF(),
					'ATTACK' 	=>		$o->getAttack(),
					'DEFENSE'	=>		$o->getDefense(),
					'SPEED'		=>		$o->getSpeed(),
					'FLUX'		=>		$o->getFlux(),
					'RESISTANCE'=>		$o->getResistance(),
					'EFFECT'	=>		$o->getEffect(),
					'TRIGGER'	=>		$o->getEffectTrigger(),
					'SLOT'		=>		$o->getSize(),
				));
			
			}
			break;
		case 'sets':
			{
				$id = request_var('id', -1);
				if($id == -1) { echo 'ID invalide.'; return; }
				$s = RPGSets::getSet($id);
				
				$t->assign_vars(array(
					'ID'		=>		$s->getId(),
					'NAME'	 	=>	 	$s->getName(),
					'DESC'	 	=>		$s->getDescription(),
					'PRICE'	 	=>		$s->getPrice(),
					'CLOTHES'	=>		$s->getCloth()->getId(),
					'LEGGINGS'	=>		$s->getLeggings()->getId(),
					'GLOVES'	=>		$s->getGloves()->getId(),
					'SHOES'		=>		$s->getShoes()->getId(),
					'PV'	 	=>		$s->getPV(),
					'PF'	 	=>		$s->getPF(),
					'ATTACK' 	=>		$s->getAtk(),
					'DEFENSE'	=>		$s->getDef(),
					'SPEED'		=>		$s->getVit(),
					'FLUX'		=>		$s->getFlux(),
					'RESISTANCE'=>		$s->getResistance(),
				));
			}
			break;
	}
}


$t->pparse('item_form');

?>