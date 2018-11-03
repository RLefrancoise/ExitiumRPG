<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');

include_once($phpbb_root_path . 'rpg/admin/php/file_functions.php');

include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGSyringes.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGOrbs.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGClothes.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGLeggings.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGGloves.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGShoes.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGSpecials.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGSets.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}

// Is user any type of admin? No, then stop here, each script needs to
// check specific permissions but this is a catchall
if (!$auth->acl_get('a_'))
{
	trigger_error('NO_ADMIN');
}

$mode = request_var('mode', '');
if($mode == '') { echo 'Mode invalide'; return; }
if($mode != 'add' and $mode != 'edit' and $mode != 'delete') { echo 'Mode invalide'; return; }

$type = request_var('type', '');
if($type == '') { echo 'Type invalide'; return; }

$ERROR = false;

switch($type) {

	case 'syringe':
		{
			$name = request_var('name', '', true);
			$desc = request_var('desc', '', true);
			$img = request_var('item_images', '', true);
			$price = request_var('price', 0);
		
			$pv = request_var('pv', 0);
			$max_pv = request_var('max_pv', 0);
			$pf = request_var('pf', 0);
			$max_pf = request_var('max_pf', 0);
			$atk = request_var('atk', 0);
			$def = request_var('def', 0);
			$spd = request_var('spd', 0);
			$flux = request_var('flux', 0);
			$res = request_var('res', 0);
			
			$outside = request_var('outside', '');
			$outside = (($outside == 'usable') ? true : false);
			
			$data = array(
				'name'	=>	$name,
				'descr'	=>	$desc,
				'price'	=>	$price,
				'img'	=>	$img,
				'usable_outside_battle'	=> $outside,
				'pv'	=>	$pv,
				'max_pv'	=>	$max_pv,
				'pf'	=>	$pf,
				'max_pf'	=>	$max_pf,
				'atk'	=>	$atk,
				'def'	=>	$def,
				'vit'	=>	$spd,
				'flux'	=>	$flux,
				'res'	=>	$res,
			);
				
			if($mode == 'add') {
				
				if(!RPGSyringes::createSyringe($data)) {
					$ERROR = true;
				}
			}
			else if($mode == 'edit') {
				$id = request_var('item_id', -1);
				
				if(!RPGSyringes::updateSyringe($id, $data)) $ERROR = true;
			}
			else if($mode == 'delete') {
				$id = request_var('id', -1);
				
				if(!RPGSyringes::deleteSyringe($id)) {
					echo 'error';
					return;
				} else {
					echo 'delete_ok';
					return;
				}
			}
		}
		break;
		
	case 'clothes':
	case 'leggings':
	case 'gloves':
	case 'shoes':
		{
			$name = request_var('name', '', true);
			$desc = request_var('desc', '', true);
			$img = request_var('items_images', '', true);
			$price = request_var('price', 0);
		
			$pv = request_var('pv', 0.0);
			$pf = request_var('pf', 0.0);
			$atk = request_var('atk', 0);
			$def = request_var('def', 0);
			$spd = request_var('spd', 0);
			$flux = request_var('flux', 0);
			$res = request_var('res', 0);
			
			$level = request_var('level', 1);
			
			$data = array(
				'name'			=>	$name,
				'descr'			=>	$desc,
				'price'			=>	$price,
				'req_lvl'		=> 	$level,
				'img'			=>	$img,
				'atk'			=>	$atk,
				'def'			=>	$def,
				'vit'			=>	$spd,
				'flux'			=>	$flux,
				'res'			=>	$res,
				'pv'			=>	$pv,
				'pf'			=>	$pf,
			);
			
			if($mode == 'add') {
				if($type == 'clothes' and !RPGClothes::createCloth($data))
					$ERROR = true;
				else if($type == 'leggings' and !RPGLeggings::createLegging($data))
					$ERROR = true;
				else if($type == 'gloves' and !RPGGloves::createGlove($data))
					$ERROR = true;
				else if($type == 'shoes' and !RPGShoes::createShoe($data))
					$ERROR = true;
			}
			else if($mode == 'edit') {
				$id = request_var('item_id', -1);
				
				if($type == 'clothes' and !RPGClothes::updateCloth($id, $data))
					$ERROR = true;
				else if($type == 'leggings' and !RPGLeggings::updateLegging($id, $data))
					$ERROR = true;
				else if($type == 'gloves' and !RPGGloves::updateGlove($id, $data))
					$ERROR = true;
				else if($type == 'shoes' and !RPGShoes::updateShoe($id, $data))
					$ERROR = true;
			}
			else if($mode == 'delete') {
				$id = request_var('id', -1);
				
				if($type == 'clothes' and !RPGClothes::deleteCloth($id)) {
					echo 'error';
					return;
				}
				else if($type == 'leggings' and !RPGLeggings::deleteLegging($id)) {
					echo 'error';
					return;
				}
				else if($type == 'gloves' and !RPGGloves::deleteGlove($id)) {
					echo 'error';
					return;
				}
				else if($type == 'shoes' and !RPGShoes::deleteShoe($id)) {
					echo 'error';
					return;
				}
				
				echo 'delete_ok';
				return;
			}
		}
		break;
	case 'orbs':
		{
			$name = request_var('name', '', true);
			$desc = request_var('desc', '', true);
			$img = request_var('items_images', '', true);
			$price = request_var('price', 0);
		
			$pv = request_var('pv', 0.0);
			$pf = request_var('pf', 0.0);
			$atk = request_var('atk', 0);
			$def = request_var('def', 0);
			$spd = request_var('spd', 0);
			$flux = request_var('flux', 0);
			$res = request_var('res', 0);
			
			$effect = request_var('effects', '');
			$trigger = request_var('triggers', '');
			$slot = request_var('slot', 1);
			
			$data = array(
				'name'			=>	$name,
				'descr'			=>	$desc,
				'price'			=>	$price,
				'level'			=> 	1,
				'type'			=> 	0,
				'img'			=>	$img,
				'attack'		=>	$atk,
				'defense'		=>	$def,
				'speed'			=>	$spd,
				'flux'			=>	$flux,
				'resistance'	=>	$res,
				'pv'			=>	$pv,
				'pf'			=>	$pf,
				'effect'		=> 	$effect,
				'trig'			=> 	$trigger,
				'size'			=> 	$slot,
			);
			
			if($mode == 'add') {
				if(!RPGOrbs::createOrb($data))
					$ERROR = true;
			}
			else if($mode == 'edit') {
				$id = request_var('item_id', -1);
				
				if(!RPGOrbs::updateOrb($id, $data)) $ERROR = true;
			}
			else if($mode == 'delete') {
				$id = request_var('id', -1);
				
				if(!RPGOrbs::deleteOrb($id)) {
					echo 'error';
					return;
				} else {
					echo 'delete_ok';
					return;
				}
			}
		}
		break;
	case 'specials':
		{
			$name = request_var('name', '', true);
			$desc = request_var('desc', '', true);
			$img = request_var('items_images', '', true);
			$price = request_var('price', 0);
			$effect = request_var('effects', '');
			
			$data = array(
				'name'			=>	$name,
				'descr'			=>	$desc,
				'img'			=>	$img,
				'price'			=>	$price,
				'effect'		=>	$effect,
			);
			
			if($mode == 'add') {
				if(!RPGSpecials::createSpecial($data))
					$ERROR = true;
			}
			else if($mode == 'edit') {
				$id = request_var('item_id', -1);
				
				if(!RPGSpecials::updateSpecial($id, $data))
					$ERROR = true;
			}
			else if($mode == 'delete') {
				$id = request_var('id', -1);
				
				if(!RPGSpecials::deleteSpecial($id)) {
					echo 'error';
					return;
				} else {
					echo 'delete_ok';
					return;
				}
			}
		}
		break;
		
	case 'sets':
		{
			$name = request_var('name', '', true);
			$desc = request_var('desc', '', true);
			$price = request_var('price', 0);
		
			$pv = request_var('pv', 0);
			$pf = request_var('pf', 0);
			$atk = request_var('atk', 0);
			$def = request_var('def', 0);
			$spd = request_var('spd', 0);
			$flux = request_var('flux', 0);
			$res = request_var('res', 0);
			
			$clothes = request_var('clothes', -1);
			$leggings = request_var('leggings', -1);
			$gloves = request_var('gloves', -1);
			$shoes = request_var('shoes', -1);
			
			$data = array(
				'name'			=>	$name,
				'descr'			=>	$desc,
				'price'			=>	$price,
				'atk'			=>	$atk,
				'def'			=>	$def,
				'vit'			=>	$spd,
				'flux'			=>	$flux,
				'res'			=>	$res,
				'pv'			=>	$pv,
				'pf'			=>	$pf,
				'cloth_id'		=>	$clothes,
				'leggings_id'	=>	$leggings,
				'gloves_id'		=>	$gloves,
				'shoes_id'		=>	$shoes,
			);
			
			if($mode == 'add') {
				if(!RPGSets::createSet($data))
					$ERROR = true;
			}
			else if($mode == 'edit') {
				$id = request_var('item_id', -1);
				
				if(!RPGSets::updateSet($id, $data)) $ERROR = true;
			}
			else if($mode == 'delete') {
				$id = request_var('id', -1);
				
				if(!RPGSets::deleteSet($id)) {
					echo 'error';
					return;
				} else {
					echo 'delete_ok';
					return;
				}
			}
		}
		break;
	default:
		$ERROR = true;
		break;
}

if($ERROR) {
	echo 'Une erreur est survenue.';
}
else {
	echo 'Action effectuée.';
}



?>