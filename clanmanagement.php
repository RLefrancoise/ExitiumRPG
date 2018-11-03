<?php

//header('Content: text/plain');

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_privmsgs.' . $phpEx);

include('./template/template.php');
include_once('./rpg/classes/rpgconfig.php');
include_once('./rpg/classes/RPGConfig.class.php');
include_once('./rpg/database/RPGClans.class.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

if($user->data['username'] == "Anonymous") {
	echo "not_connected";
	die();
}
$mode = request_var('mode', '');

if($mode == '') {
	echo 'error';
}
else {
	$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
	if($player->isInBattle()) {
		echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
		die();
	}
	
	switch($mode) {
		/* Join clan */
		case 'join':
			$id = request_var('id', '');
			if($id === '') { echo 'error'; return; }
			
			// check user and clan ids
			$clan = RPGClans::getClan($id);
			if($clan === null) { echo 'error'; return; }
			$leader = $clan->getLeader();
			if($leader === null) { echo 'error'; return; }
			$leader_id = $leader->getUserId();
			
			// if player doesn't have a clan yet
			if($player->getClan() === null) {
				// store join request and get its token
				$token = RPGClans::storeJoinRequest($user->data['user_id'], $clan->getId());
				if(!$token) { echo 'error'; return; }
				
				// write private message and send it to clan's leader
				$poll = $uid = $bitfield = $options = ''; 
				
				$text = 'Le membre ' . $user->data['username'] . ' souhaite rejoindre votre clan.
						Pour accepter veuillez cliquer sur ce lien : ' . SITE_URL . 'clanmanagement.php?mode=validate_join&t=' . $token . '
						Pour refuser veuillez cliquer sur ce lien : ' . SITE_URL . 'clanmanagement.php?mode=unvalidate_join&t=' . $token;
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Demande d\'admission à votre clan');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$pm_data = array(
					'from_user_id'            	=> $user->data['user_id'],
					'icon_id'               	=> 0,
					'from_user_ip'             	=> $user->data['user_ip'],
					'from_username'            	=> $user->data['username'],
					'enable_sig'             	=> false,
					'enable_bbcode'           	=> true,
					'enable_smilies'          	=> true,
					'enable_urls'             	=> true,
					'bbcode_bitfield'         	=> $bitfield,
					'bbcode_uid'             	=> $uid,
					'message'                	=> $text,
					'message_attachment'    	=> 0,
					'address_list'        		=> array('u' => array($leader_id => 'to')),
				);
				
				if(submit_pm('post', $subject, $pm_data, false)) echo 'join_ok';
				else echo 'error';
			}
			else {
				echo 'already_has_clan';
			}
			break;
			
		/* Quit clan */
		case 'quit':
			$id = request_var('id', '');
			if($id === '') { echo 'error'; return; }
			
			$clan = $player->getClan();
			
			if( ($clan !== null) and (RPGClans::isClanMember($user->data['user_id'], $id)) and (!RPGClans::isClanLeader($user->data['user_id'], $id)) ) {
				
				$poll = $uid = $bitfield = $options = ''; 
			
				$text = 'Le membre "' . $player->getName() . '" a quitté votre clan.';
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Départ d\'un membre du clan');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$pm_data = array(
					'from_user_id'            	=> $user->data['user_id'],
					'icon_id'               	=> 0,
					'from_user_ip'             	=> $user->data['user_ip'],
					'from_username'            	=> $user->data['username'],
					'enable_sig'             	=> false,
					'enable_bbcode'           	=> true,
					'enable_smilies'          	=> true,
					'enable_urls'             	=> true,
					'bbcode_bitfield'         	=> $bitfield,
					'bbcode_uid'             	=> $uid,
					'message'                	=> $text,
					'message_attachment'    	=> 0,
					'address_list'        		=> array('u' => array( $clan->getLeader()->getUserId() => 'to')),
				);
				
				if(RPGClans::removeClanMember($player->getUserId(), $id) and submit_pm('post', $subject, $pm_data, false))
					echo 'quit_ok';
				else
					echo 'error';
			}
			else {
				echo 'is_not_member';
			}
			break;
		
		/* Delete clan */
		case 'delete':
			{	
				$id = request_var('id', '');
				if($id === '') { echo 'error'; return; }
				
				$clan = RPGClans::getClan($id);
				if($clan === null) { echo 'error'; return; }
				if(!RPGClans::isClanLeader($user->data['user_id'], $id)) { echo 'is_not_leader'; return; }
				
				$poll = $uid = $bitfield = $options = ''; 
			
				$text = 'Votre clan "' . $clan->getName() . '" a été supprimé par le chef du clan.';
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Suppression du clan');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$to_send = array();
				$clan_members = $clan->getMembers();
				foreach($clan_members as $member) {
					if($member->getUserId() !== $clan->getLeader()->getUserId())
						$to_send[$member->getUserId()] = 'to';
				}
				
				if(count($to_send) > 0) {
					$pm_data = array(
						'from_user_id'            	=> $user->data['user_id'],
						'icon_id'               	=> 0,
						'from_user_ip'             	=> $user->data['user_ip'],
						'from_username'            	=> $user->data['username'],
						'enable_sig'             	=> false,
						'enable_bbcode'           	=> true,
						'enable_smilies'          	=> true,
						'enable_urls'             	=> true,
						'bbcode_bitfield'         	=> $bitfield,
						'bbcode_uid'             	=> $uid,
						'message'                	=> $text,
						'message_attachment'    	=> 0,
						'address_list'        		=> array('u' => $to_send),
					);
					
					
					submit_pm('post', $subject, $pm_data, false);

				}
				
				if(RPGClans::deleteClan($id) === false) { echo 'error'; return; }
				
				echo 'delete_ok';
			}
			break;
			
		/* Validate join*/
		case 'validate_join':
			{
				$token = request_var('t', '');
				if($token === '') { echo 'error'; return; }
				
				$request = RPGClans::getJoinRequest($token);
				
				//check if this request is in DB
				if(!$request) { echo 'Cette demande d\'admission n\'existe pas.'; return; }
				
				$clan = RPGClans::getClan($request['clan_id']);
				
				//check if current player is clan leader
				if($user->data['user_id'] !== $clan->getLeader()->getUserId()) {
					echo 'Vous n\'êtes pas le chef du clan associé à cette demande d\'admission.';
					return;
				}
				
				//check if the player to add already has a clan
				$to_add = RPGUsersPlayers::getPlayerByUserId($request['user_id']);
				if($to_add->getClan() !== null) {
					echo 'Ce joueur possède déjà un clan. Cette demande d\'admission va être annulée.';
					$remove_success = RPGClans::deleteJoinRequest($token);
					if(!$remove_success) echo 'Erreur : impossible de supprimer cette requête.'; 
					return;
				}
				
				//valide join and send pm to added player
				$db->sql_transaction('begin');
				$add_success = RPGClans::addClanMember($request['user_id'], $request['clan_id']);
				$remove_success = RPGClans::deleteJoinRequest($token);
				$db->sql_transaction('commit');
				
				//write PM
				$poll = $uid = $bitfield = $options = ''; 
			
				$text = 'Votre demande d\'adhésion au clan "' . $clan->getName() . '" a été acceptée par le chef du clan.';
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Adhésion à un clan');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$pm_data = array(
					'from_user_id'            	=> $user->data['user_id'],
					'icon_id'               	=> 0,
					'from_user_ip'             	=> $user->data['user_ip'],
					'from_username'            	=> $user->data['username'],
					'enable_sig'             	=> false,
					'enable_bbcode'           	=> true,
					'enable_smilies'          	=> true,
					'enable_urls'             	=> true,
					'bbcode_bitfield'         	=> $bitfield,
					'bbcode_uid'             	=> $uid,
					'message'                	=> $text,
					'message_attachment'    	=> 0,
					'address_list'        		=> array('u' => array($request['user_id'] => 'to')),
				);
				
				if(submit_pm('post', $subject, $pm_data, false) and $add_success and $remove_success) echo 'Le membre ' . $to_add->getName() . ' a été ajouté à votre clan.';
				else echo 'Une erreur est survenue lors du traitement de la requête. Merci de signaler cette erreur à un administrateur.';
			}
			break;
			
		/* Unvalidate join*/
		case 'unvalidate_join':
			{
				$token = request_var('t', '');
				if($token === '') { echo 'error'; return; }
				
				$request = RPGClans::getJoinRequest($token);
				
				//check if this request is in DB
				if(!$request) { echo 'Cette demande d\'admission n\'existe pas.'; return; }
				
				$clan = RPGClans::getClan($request['clan_id']);
				
				//check if current player is clan leader
				if($user->data['user_id'] !== $clan->getLeader()->getUserId()) {
					echo 'Vous n\'êtes pas le chef du clan associé à cette demande d\'admission.';
					return;
				}
				
				//check if the player to refuse already has a clan
				$to_add = RPGUsersPlayers::getPlayerByUserId($request['user_id']);
				if($to_add->getClan() !== null) {
					echo 'Ce joueur possède déjà un clan. Cette annulation d\'admission va être annulée.';
					$remove_success = RPGClans::deleteJoinRequest($token);
					if(!$remove_success) echo 'Erreur : impossible de supprimer cette requête.'; 
					return;
				}
				
				//unvalide join and send pm to refused player
				$db->sql_transaction('begin');
				$remove_success = RPGClans::deleteJoinRequest($token);
				$db->sql_transaction('commit');
				
				//write PM
				$poll = $uid = $bitfield = $options = ''; 
			
				$text = 'Votre demande d\'adhésion au clan "' . $clan->getName() . '" a été refusée par le chef du clan.';
				$text = utf8_normalize_nfc($text);
				
				$subject = utf8_normalize_nfc('Adhésion à un clan');
				
				generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
				generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
				
				$pm_data = array(
					'from_user_id'            	=> $user->data['user_id'],
					'icon_id'               	=> 0,
					'from_user_ip'             	=> $user->data['user_ip'],
					'from_username'            	=> $user->data['username'],
					'enable_sig'             	=> false,
					'enable_bbcode'           	=> true,
					'enable_smilies'          	=> true,
					'enable_urls'             	=> true,
					'bbcode_bitfield'         	=> $bitfield,
					'bbcode_uid'             	=> $uid,
					'message'                	=> $text,
					'message_attachment'    	=> 0,
					'address_list'        		=> array('u' => array($request['user_id'] => 'to')),
				);
				
				if(submit_pm('post', $subject, $pm_data, false) and $remove_success) echo 'La demande d\'adhésion du membre ' . $to_add->getName() . ' a été refusée.';
				else echo 'Une erreur est survenue lors du traitement de la requête. Merci de signaler cette erreur à un administrateur.';
			}
			break;
			
		/* Ban member from clan */
		case 'ban_member':
			{
				$id = request_var('id', -1);
				if($id == -1) { echo 'error'; return; }
				$member_id = request_var('m', -1);
				if($member_id == -1) { echo 'error'; return; }
				
				$clan = RPGClans::getClan($id);
				
				if( ($clan !== null) and (RPGClans::isClanLeader($user->data['user_id'], $id)) ) {
					
					$poll = $uid = $bitfield = $options = ''; 
			
					$text = 'Le chef du clan "' . $clan->getName() . '" vous a exclu du clan.';
					$text = utf8_normalize_nfc($text);
					
					$subject = utf8_normalize_nfc('Exclusion du clan');
					
					generate_text_for_storage($subject, $uid, $bitfield, $options, false, false, false);
					generate_text_for_storage($text, $uid, $bitfield, $options, true, true, true);
					
					$pm_data = array(
						'from_user_id'            	=> $user->data['user_id'],
						'icon_id'               	=> 0,
						'from_user_ip'             	=> $user->data['user_ip'],
						'from_username'            	=> $user->data['username'],
						'enable_sig'             	=> false,
						'enable_bbcode'           	=> true,
						'enable_smilies'          	=> true,
						'enable_urls'             	=> true,
						'bbcode_bitfield'         	=> $bitfield,
						'bbcode_uid'             	=> $uid,
						'message'                	=> $text,
						'message_attachment'    	=> 0,
						'address_list'        		=> array('u' => array( $member_id => 'to')),
					);
				
					if(RPGClans::removeClanMember($member_id, $id) and submit_pm('post', $subject, $pm_data, false))
						echo 'ban_ok';
					else
						echo 'error';
					
				} else
					echo 'error';
			}
			break;
			
		case 'use_pi':
			{
				$id = request_var('id', -1);
				if($id === -1) { echo 'error'; return; }
				$type = request_var('type', '');
				if($type != 'atk' and $type != 'def' and $type != 'spd' and $type != 'flux' and $type != 'res' and $type != 'pv' and $type != 'pf') { echo 'error'; return; }
				
				$clan = RPGClans::getClan($id);
				
				if( ($clan !== null) and (RPGClans::isClanLeader($user->data['user_id'], $id)) ) {
				
					$stat = '';
					
					// $type string is the same as STAT enum in rpgconfig, so we can use it directly
					$level = $clan->getStatLevel($type);
					if($level >= 6) {
						echo 'buff_max';
						return;
					}
					
					//enough PI to buy buff ?
					$pi = $clan->getPI();
					
					if($pi < RPGConfig::$_CLAN_PI_RALZ[$level + 1]) {
						echo 'no_ralz';
						return;
					}
					
					$db->sql_transaction('begin');
					if(!RPGClans::updateStatLevel($clan, $type, $level + 1) or !RPGClans::updatePI($clan, $pi - RPGConfig::$_CLAN_PI_RALZ[$level + 1])) {
						$db->sql_transaction('cancel');
						echo 'error';
						return;
					} else {
						$db->sql_transaction('commit');
						echo 'pi_ok';
					}
				}
			}
			break;
		case 'pi_menu':
			{
				$id = request_var('id', -1);
				if($id === -1) { echo 'error'; return; }
				
				$clan = RPGClans::getClan($id);
				
				if( ($clan !== null) and (RPGClans::isClanLeader($user->data['user_id'], $id)) ) {
					
					$t = new CustomTemplate('./rpg/tpl');
					$t->set_filenames(array('clanpage_pi_menu' => 'clanpage_pi_menu.tpl'));
					
					$t->assign_vars(array(
						'CLAN_PI'		=>	$clan->getPI(),
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
						'PF_BONUS'		=>	RPGConfig::$_CLAN_STAT_BONUS[$clan->getPFLevel() + 1][STAT_PF],
					));
		
					$t->pparse('clanpage_pi_menu');
				} else
					echo 'error';
				
			}
			break;
		default:
			echo 'error';
			break;
	}
}

?>