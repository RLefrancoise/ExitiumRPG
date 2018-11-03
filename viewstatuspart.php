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
	echo "not_connected";
	die();
}

$mode = request_var('mode', '');

if($mode == '') { echo 'error'; die(); }

$t = new CustomTemplate('./rpg/tpl');

//---player---
$player 	= RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
if($player->isInBattle()) {
	echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
	die();
}
			
switch($mode) {

	/* Display equipment */
	case 'equipment':
		{
			$t->set_filenames(array('viewstatusequipment' => 'viewstatusequipment.tpl'));

			//equipment
			$stuff_info = '<strong>Informations sur l\'équipement</strong><br>';
			// WEAPON
			if($player->getWeapon() !== null) {
				$weapon = $player->getWeapon()->getName() . ' [' . $player->getWeapon()->getGrade() . ']';
				$stuff_info .= 'Arme : Puissance : ' . $player->getWeapon()->getAttack() . ', Bonus de précision : ' . $player->getWeapon()->getAccuracy() . '%, Bonus de critique : ' . $player->getWeapon()->getCritical() . '%<br>';
			}
			// SET
			if(($set = $player->getSet()) != null) {
				$stuff_info .= '<strong>' . $set->getName() . ' : ' . $set->getDescription() . '</strong><br>';
			}
			// CLOTH
			$cloth = $player->getArmorPartName(ARMOR_CLOTH);
			if($player->getEquipment(ARMOR_CLOTH) !== null) {
				$cloth .= ' [E]';
				$stuff_info .= 'Haut : ' . $player->getEquipment(ARMOR_CLOTH)->getPartName() . '[' . $player->getEquipment(ARMOR_CLOTH)->getDescription() . ']<br>';
			}
			// LEGGINGS
			$leggings = $player->getArmorPartName(ARMOR_LEGGINGS);
			if($player->getEquipment(ARMOR_LEGGINGS) !== null) {
				$leggings .= ' [E]';
				$stuff_info .= 'Bas : ' . $player->getEquipment(ARMOR_LEGGINGS)->getPartName() . '[' . $player->getEquipment(ARMOR_LEGGINGS)->getDescription() . ']<br>';
			}
			// GLOVES
			$gloves = $player->getArmorPartName(ARMOR_GLOVES);
			if($player->getEquipment(ARMOR_GLOVES) !== null) {
				$gloves .= ' [E]';
				$stuff_info .= 'Gants : ' . $player->getEquipment(ARMOR_GLOVES)->getPartName() . '[' . $player->getEquipment(ARMOR_GLOVES)->getDescription() . ']<br>';
			}
			// SHOES
			$shoes = $player->getArmorPartName(ARMOR_SHOES);
			if($player->getEquipment(ARMOR_SHOES) !== null) {
				$shoes .= ' [E]';
				$stuff_info .= 'Bottes : ' . $player->getEquipment(ARMOR_SHOES)->getPartName() . '[' . $player->getEquipment(ARMOR_SHOES)->getDescription() . ']';
			}
			
			//BATTLES STATS
			$stuff_info .= '<br><br><strong>Statistiques de combat</strong><br>';
			$stuff_info .= "Dégâts de base : {$player->getBattleDamage()}<br>";
			$stuff_info .= "Dégats magiques de base : {$player->getBattleMagicDamage()}<br>";
			$stuff_info .= "Réduction des dégâts : {$player->getBattleDefense()}<br>";
			$stuff_info .= "Réduction des dégâts magiques : {$player->getBattleMagicDefense()}<br>";
			$stuff_info .= "Précision : {$player->getBattleAccuracy()}<br>";
			$stuff_info .= "Précision magique : {$player->getBattleMagicAccuracy()}<br>";
			$stuff_info .= "Critique : {$player->getBattleCritical()}<br>";
			$stuff_info .= "Esquive : {$player->getBattleEvade()}<br>";
			$stuff_info .= "Esquive critique : {$player->getBattleDodge()}";
			
			
			$t->assign_vars(array(
				/* stuff info */
				'STUFF_INFO'	=> $stuff_info,
				
				/* character info */
				'USER_WEAPON' 	=> $weapon,
				'USER_CLOTH'  	=> $cloth,
				'USER_LEGGINGS' => $leggings,
				'USER_GLOVES'   => $gloves,
				'USER_SHOES'	=> $shoes,
			));

			//HD
			$t->assign_vars(array(
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));

			$t->pparse('viewstatusequipment');

		}
		break;
		
	/* Display Inventory */
	case 'inventory':
		{
			$t->set_filenames(array('viewstatusinventory' => 'viewstatusinventory.tpl'));
			
			$positions = array(
				0	=> array(59,46),
				1	=> array(123,46),
				2	=> array(187,46),
				3	=> array(254,46),
				4	=> array(322,46),
				5	=> array(388,46),
				6	=> array(452,46),
				7	=> array(516,46),
				8	=> array(59,95),
				9	=> array(123,95),
				10	=> array(187,95),
				11	=> array(254,95),
				12	=> array(322,95),
				13	=> array(388,95),
				14	=> array(452,95),
				15	=> array(516,95),
			);

			$inventory = $player->getInventory();

			for($i = 0 ; $i < INVENTORY_SIZE ; $i++) {
			
				$item = $inventory->getItem($i);
				if(!$item) continue;
				
				$t->assign_block_vars('inventory_bloc', array(
					'ITEM_SLOT'	=> ($i + 1),
					'ITEM_DESC'	=> $item->isOnePerSlot() ? $item->getToolTipText() : $item->getToolTipText() . '<br>Quantité : ' . $inventory->getQuantityOfItem($i),
					'ITEM_X'	=> $positions[$i][0],
					'ITEM_Y'	=> $positions[$i][1],
					'ITEM_ICON'	=> $item->getIcon(),
				));
			}
			
			//HD
			$t->assign_vars(array(
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));
			
			$t->pparse('viewstatusinventory');
		}
		break;
		
	/* Display stats */
	case 'stats' :
		{
			$t->set_filenames(array('viewstatusstats' => 'viewstatusstats.tpl'));
			
			$t->assign_vars(array(
				'USER_ATTACK'	=> $player->getAttack(),
				'USER_DEFENSE'	=> $player->getDefense(),
				'USER_SPEED'	=> $player->getSpeed(),
				'USER_FLUX'		=> $player->getFlux(),
				'USER_RESISTANCE'	=> $player->getResistance(),
				'USER_POINTS'	=> $player->getPoints(),
			));
			
			$t->pparse('viewstatusstats');
		}
		break;
		
	/* Display state (PV, PF, XP) */
	case 'state' :
		{
			$t->set_filenames(array('viewstatusstate' => 'viewstatusstate.tpl'));
			
			$t->assign_vars(array(
				'USER_HP'		=> $player->getPV(),
				'USER_MAX_HP'	=> $player->getMaxPV(), // max pv + bonus
				'USER_FP'		=> $player->getPF(),
				'USER_MAX_FP'	=> $player->getMaxPF(), // max pf + bonus
				'USER_XP'		=> ($player->getLevel() < MAX_LEVEL ? $player->getXP() : '---'),
				'USER_MAX_XP'	=> ($player->getLevel() < MAX_LEVEL ? RPGXP::getXPByLvl($player->getLevel()) : '---'),
			));
			
			//HD
			$t->assign_vars(array(
				'SD_DIR'	=> $player->hdEnabled() ? '' : 'sd/',
				'SD_EXT'	=> $player->hdEnabled() ? 'png' : 'gif',
			));
			
			$t->pparse('viewstatusstate');
		}
		break;
		
	/* Display orbs */
	case 'orbs':
		{
			$t->set_filenames(array('viewstatusorbs' => 'viewstatusorbs.tpl'));
			
			for($i = 1 ; $i <= 4 ; $i++) {
				$orb = $player->getOrb($i);
				if($orb != null) {
					$orb_desc = $orb->getToolTipText();
					$tooltip = 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="'.$orb_desc.'"';
					$orb_img = "images/rpg/icons/" . $orb->getIcon();
					
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> $tooltip,
						'ORB_ONCLICK'	=> 'onclick="javascript:open_orb_menu(' . $i . ', event)"',
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> $orb_img,
					));
				} else {
					$t->assign_block_vars('orbs_bloc', array(
						'ORB_TOOLTIP'	=> '',
						'ORB_ONCLICK'	=> '',
						'ORB_NB'		=> $i,
						'ORB_IMG'		=> 'images/rpg/icons/OrbeVIDE.png',
					));
				}
				
			}
			
			$t->pparse('viewstatusorbs');
		}
		break;
		
	/* Display skills */
	case 'skills':
		{
			$t->set_filenames(array('viewstatusskills' => 'viewstatusskills.tpl'));
			
			global $_SKILLS_REQUIRED_LEVELS;
			
			for( $i = 1 ; $i <= 4 ; $i++ ) {
				$skill = $player->getSkill($i);
				$skill_name = ($skill != null ? $player->getSkillName($i) : "");
				
				if($_SKILLS_REQUIRED_LEVELS[$i] > $player->getLevel()) continue;
				
				$t->assign_block_vars('skills_bloc', array(
					'SKILL_NAME'	=> ( $skill_name != "" ? $skill_name : ($skill != null ? $skill->getName() : "") ),
					'SKILL_NB'		=> $i,
					'SKILL_ONCLICK'	=> ($skill != null ? 'onclick="javascript:open_skill_actions('.$i.', event)"' : 'onclick="javascript:open_skill_menu('.$i.', event)"'),
					'SKILL_TOOLTIP'	=> ($skill != null ? 'onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="' . $skill->getFullDescription() . '"' : ""),
				));
			}
			
			$t->pparse('viewstatusskills');
		}
		break;
		
	case 'user_info':
		{
			$t->set_filenames(array('viewstatususerinfo' => 'viewstatususerinfo.tpl'));
			
			//---user---
			$age		= '-';

			if ($config['allow_birthdays'] && $user->data['user_birthday'])
			{
				list($bday_day, $bday_month, $bday_year) = array_map('intval', explode('-', $user->data['user_birthday']));

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
				'USERNAME'		=> $user->data['username'],
				'USER_AGE'		=> $age,
				'USER_GENDER'	=> $player->getGender(),
				'USER_FROM'		=> $user->data['user_from'],
				'USER_MAIL'		=> $user->data['user_email'],
				'USER_ORGANISATION'	=> ($player->getOrganisation() !== null) ? $player->getOrganisation()->getName() : '',
				'USER_CLAN'		=> ($player->getClan() !== null) ? $player->getClan()->getName() : '',
			));
			
			$t->pparse('viewstatususerinfo');
		}
		break;
	
	case 'options':
		{
			$t->set_filenames(array('viewstatusoptions' => 'viewstatusoptions.tpl'));
			
			$sound_enabled = $player->soundEnabled();
			$animations_enabled = $player->animationsEnabled();
			$alpha_enabled = $player->alphaEnabled();
			$hd_enabled = $player->hdEnabled();
			
			if($sound_enabled) {
				$t->assign_block_vars('sound_on_unclickable', array());
				$t->assign_block_vars('sound_off_clickable', array());
			} else {
				$t->assign_block_vars('sound_on_clickable', array());
				$t->assign_block_vars('sound_off_unclickable', array());
			}
			
			if($animations_enabled) {
				$t->assign_block_vars('animations_on_unclickable', array());
				$t->assign_block_vars('animations_off_clickable', array());
			} else {
				$t->assign_block_vars('animations_on_clickable', array());
				$t->assign_block_vars('animations_off_unclickable', array());
			}
			
			if($alpha_enabled) {
				$t->assign_block_vars('alpha_on_unclickable', array());
				$t->assign_block_vars('alpha_off_clickable', array());
			} else {
				$t->assign_block_vars('alpha_on_clickable', array());
				$t->assign_block_vars('alpha_off_unclickable', array());
			}
			
			if($hd_enabled) {
				$t->assign_block_vars('hd_on_unclickable', array());
				$t->assign_block_vars('hd_off_clickable', array());
			} else {
				$t->assign_block_vars('hd_on_clickable', array());
				$t->assign_block_vars('hd_off_unclickable', array());
			}
			
			
			$t->pparse('viewstatusoptions');
		}
		break;
	default:
		break;
}

?>