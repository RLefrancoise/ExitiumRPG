<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include($phpbb_root_path . 'template/template.php');
include_once($phpbb_root_path . 'rpg/database/RPGUsersPlayers.class.php');
include_once($phpbb_root_path . 'rpg/database/RPGBattleAreas.class.php');
include_once($phpbb_root_path . 'rpg/php/item_functions.php');

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
$t->set_filenames(array('area_monsters' => 'area_monsters.tpl'));


$t->assign_vars(array(
	'SID'	=> request_var('sid', ''),
	'ROOT'	=> $phpbb_root_path,
));

$areas = RPGBattleAreas::getAreas();

foreach($areas as $a) {
	
	$t->assign_block_vars('area_bloc', array(
		'ID'		=> $a->getId(),
		'NAME'		=> $a->getName(),
	));
	
	$parts = $a->getAreaParts();
	
	foreach($parts as $p) {
	
		$monsters = $p->getMonsters();
		$rates = $p->getEncounterRates();
		
		for($i = 0 ; $i < count($monsters) ; $i++) {
		
			$drops = $monsters[$i]->getDropsByAreaPart($p->getId());
			
			$drops_string = '';
			
			if($drops) {
				foreach($drops as $rate => $items) {
					foreach($items as $item_data) {
						$item = get_item($item_data['item_id'], $item_data['item_type']);
						$drops_string .= "{$item->getName()} [{$rate}%]<br>";
					}
					
				}
			}
			
			
			$t->assign_block_vars('area_bloc.part_bloc', array(
				'NAME'		=> $p->getName(),
				'MONSTER'	=> $monsters[$i]->getName(),
				'RATE'		=> $rates[$i],
				'DROPS'		=> $drops_string,
			));
		}
	}
}

$t->pparse('area_monsters');

?>