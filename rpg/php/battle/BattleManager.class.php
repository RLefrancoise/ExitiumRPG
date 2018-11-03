<?php

include_once(__DIR__ . '/../../database/RPGPlayers.class.php');
include_once(__DIR__ . '/../../database/RPGMonsters.class.php');
include_once(__DIR__ . '/../../database/RPGPVEBattles.class.php');
include_once(__DIR__ . '/../../database/RPGPVPBattles.class.php');
include_once(__DIR__ . '/../../database/RPGEventBattles.class.php');
include_once(__DIR__ . '/../../database/RPGQuests.class.php');
include_once(__DIR__ . '/../../database/RPGUsersPlayers.class.php');

class BattleManager {

	private $battle;
	private $mode;
	
	public function __construct(AbstractBattle &$battle, $mode) {
		$this->battle = &$battle;
		$this->mode = $mode;
	}
	
	//BATTLE
	public function before_battle(Creature &$player1, Creature &$player2, $battle_data = false) {
		$msg = '';
		$m = '';
		
		//check player1 effects
		$m = $this->before_battle_check_player_effects(1, $player1, $player2, $battle_data);
		if($m === false) return false;
		else $msg .= $m;
		
		//check player2 effects
		$m = $this->before_battle_check_player_effects(2, $player2, $player1, $battle_data);
		if($m === false) return false;
		else $msg .= $m;
		
		return $msg;
	}
	
	public function after_battle(Creature &$player1, Creature &$player2, $battle_data = false) {
		$msg = '';
		$m = '';
		
		//check player1 effects
		$m = $this->after_battle_check_player_effects(1, $player1, $player2, $battle_data);
		if($m === false) return false;
		else $msg .= $m;
		
		//check player2 effects
		$m = $this->after_battle_check_player_effects(2, $player2, $player1, $battle_data);
		if($m === false) return false;
		else $msg .= $m;
		
		//update event data
		if($this->mode == 'event') {
			if(!RPGEventBattles::incrementTotalDamageGiven($this->battle, $battle_data['player1_total_damage_given']))
				return false;
			if(!RPGEventBattles::incrementTotalDamageReceived($this->battle, $battle_data['player1_total_damage_received']))
				return false;
		}
		
		return $msg;
	}
	
	protected function before_battle_check_player_effects($player_nb, Creature &$actor, Creature &$opponent, $battle_data = false) {
		global $_ORB_EFFECTS_AVAILABILITY;
		
		$msg = '';
		$m = '';
		
		// ORBS EFFECTS
		$orb_effects = $this->get_player_orbs_effects($player_nb, $actor, $opponent);
		
		foreach($orb_effects as $effect => $data) {
			if($_ORB_EFFECTS_AVAILABILITY[$effect] != 'before') continue;
			
			if($data['value']) {
				$m = $this->apply_orb_effect($player_nb, $actor, $opponent, $effect, $data['slot'], $battle_data);
				if($m === false) return false;
				else $msg .= $m;
			}
		}
		
		return $msg;
	}
	
	protected function after_battle_check_player_effects($player_nb, Creature &$actor, Creature &$opponent, $battle_data = false) {
		global $_ORB_EFFECTS_AVAILABILITY;
		
		$msg = '';
		$m = '';
		
		// ORBS EFFECTS
		$orb_effects = $this->get_player_orbs_effects($player_nb, $actor, $opponent, 'after');
		
		foreach($orb_effects as $effect => $data) {
			if($_ORB_EFFECTS_AVAILABILITY[$effect] != 'after') continue;
			
			if($data['value']) {
				$m = $this->apply_orb_effect($player_nb, $actor, $opponent, $effect, $data['slot'], $battle_data);
				if($m === false) return false;
				else $msg .= $m;
			}
		}
		
		return $msg;
	}
	
	public function apply_orb_effect($player_nb, Player &$actor, Creature &$opponent, $orb_effect, $orb_slot, $battle_data = false) {
		$msg = '';
		
		$orb = $actor->getOrb($orb_slot);
		
		$msg .= "L'orbe {$orb->getName()} de {$actor->getName()} s'active.<br>";
		
		switch($orb_effect) {
			/* NO CRITICAL */
			case ORB_EFFECT_NO_CRITICAL:
				{
					$msg .= "{$actor->getName()} est immunisé contre les coups critiques !<br>";
				}
				break;
			/* REBIRTH */
			case ORB_EFFECT_REBIRTH:
				{
					$damage = ($player_nb == 1 ? $battle_data['player1_last_damage'] : $battle_data['player2_last_damage']);
					if(!$this->give_hp($player_nb, $actor, $damage)) return false;
					$msg .= "{$actor->getName()} revient à la vie avec $damage PV !<br>";
					
					if(!$this->set_active_orb($player_nb, $orb_slot, false)) return false;
				}
				break;
			/* KILL */
			case ORB_EFFECT_KILL:
				{
					if(!$this->set_hp($player_nb == 1 ? 2 : 1, $opponent, 0)) return false;
					$msg .= "{$opponent->getName()} est tué instantanément !<br>";
					
				}
				break;
			/* BERSERK */
			case ORB_EFFECT_BERSERK:
				{
					$critical_bonus = $this->get_orb_effect_stat_bonus($orb_effect, $player_nb, $actor, $opponent);
					
					$msg .= "Le taux de critique de {$actor->getName()} augmente de {$critical_bonus}% !<br>";
				}
				break;
			/* ATTACK + */
			case ORB_EFFECT_ATTACK_PLUS:
				{
					$bonus = $this->get_orb_effect_stat_bonus($orb_effect, $player_nb, $actor, $opponent);
					
					$msg .= "L'attaque de {$actor->getName()} augmente de {$bonus} !<br>";
				}
				break;
			/* DEFENSE + */
			case ORB_EFFECT_DEFENSE_PLUS:
				{
					$bonus = $this->get_orb_effect_stat_bonus($orb_effect, $player_nb, $actor, $opponent);
					
					$msg .= "La défense de {$actor->getName()} augmente de {$bonus} !<br>";
				}
				break;
			/* SPEED + */
			case ORB_EFFECT_SPEED_PLUS:
				{
					$bonus = $this->get_orb_effect_stat_bonus($orb_effect, $player_nb, $actor, $opponent);
					
					$msg .= "La vitesse de {$actor->getName()} augmente de {$bonus} !<br>";
				}
				break;
			/* FLUX + */
			case ORB_EFFECT_FLUX_PLUS:
				{
					$bonus = $this->get_orb_effect_stat_bonus($orb_effect, $player_nb, $actor, $opponent);
					
					$msg .= "Le flux de {$actor->getName()} augmente de {$bonus} !<br>";
				}
				break;
			case ORB_EFFECT_RESISTANCE_PLUS:
				{
					$bonus = $this->get_orb_effect_stat_bonus($orb_effect, $player_nb, $actor, $opponent);
					
					$msg .= "La résistance de {$actor->getName()} augmente de {$bonus} !<br>";
				}
				break;
			/* CANCEL ALL ORBS */
			case ORB_EFFECT_NO_ORBS:
				{	
					$actor->removeOrb(1);
					$actor->removeOrb(2);
					$actor->removeOrb(3);
					$actor->removeOrb(4);
					
					if($this->mode != 'pve' and $this->mode != 'event' and $this->mode != 'quest') {
						$opponent->removeOrb(1);
						$opponent->removeOrb(2);
						$opponent->removeOrb(3);
						$opponent->removeOrb(4);
					}
					$msg .= "Les effets de toutes les orbes sont annulés.<br>";
				}
				break;
		}
		
		return $msg;
	}
	
	public function increment_turn() {
		if($this->mode == 'pve') {
			return RPGPVEBattles::incrementTurn($this->battle);
		} else if($this->mode == 'event') {
			return RPGEventBattles::incrementTurn($this->battle);
		} else if($this->mode == 'pvp') {
			return RPGPVPBattles::incrementTurn($this->battle);
		} else if($this->mode == 'quest') {
			return RPGQuests::incrementTurn($this->battle);
		} else {
			return false;
		}
	}
	
	
	
	
	//STATS
	public function give_hp($player_nb, Creature &$creature, $hp) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		if($hp == 0) return true;
		
		$current_hp = $this->get_hp($creature, $player_nb);
		$max_hp = $creature->getMaxPV();
		$new_hp = $current_hp + $hp;
		if($new_hp < 0) $new_hp = 0;
		if($new_hp > $max_hp) $new_hp = $max_hp;
		if($new_hp == $current_hp) return true;
			
		// creature is PVE monster
		if( ($this->mode == 'pve') and ($player_nb == 2) ) {
			return RPGPVEBattles::setMonsterHP($this->battle, $new_hp);
		}
		// creature is EVENT monster
		else if( ($this->mode == 'event') and ($player_nb == 2) ) {
			return RPGEventBattles::setMonsterHP($this->battle, $new_hp);
		}
		// creature is QUEST monster
		else if( ($this->mode == 'quest') and ($player_nb == 2) ) {
			return RPGQuests::setMonsterHP($this->battle, $new_hp);
		}
		// creature is player
		else {
			return RPGPlayers::setPVOfPlayer($creature, $new_hp);
		}
	}
	
	public function set_hp($player_nb, Creature &$creature, $hp) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		
		if($creature->getPV() == $hp) return true;
		
		// creature is PVE monster
		if( ($this->mode == 'pve') and ($player_nb == 2) ) {
			return RPGPVEBattles::setMonsterHP($this->battle, $hp);
		}
		// creature is EVENT monster
		else if( ($this->mode == 'event') and ($player_nb == 2) ) {
			return RPGEventBattles::setMonsterHP($this->battle, $hp);
		}
		// creature is QUEST monster
		else if( ($this->mode == 'quest') and ($player_nb == 2) ) {
			return RPGQuests::setMonsterHP($this->battle, $hp);
		}
		// creature is player
		else {
			return RPGPlayers::setPVOfPlayer($creature, $hp);
		}
	}
	
	public function give_fp($player_nb, Creature &$creature, $fp) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		if($fp == 0) return true;
		
		$current_fp = $this->get_fp($creature, $player_nb);
		$max_fp = $creature->getMaxPF();
		$new_fp = $current_fp + $fp;
		if($new_fp < 0) $new_fp = 0;
		if($new_fp > $max_fp) $new_fp = $max_fp;
		if($new_fp == $current_fp) return true;
			
		// creature is PVE monster
		if( ($this->mode == 'pve') and ($player_nb == 2) ) {
			return RPGPVEBattles::setMonsterFP($this->battle, $new_fp);
		}
		// creature is EVENT monster
		else if( ($this->mode == 'event') and ($player_nb == 2) ) {
			return RPGEventBattles::setMonsterFP($this->battle, $new_fp);
		}
		// creature is QUEST monster
		else if( ($this->mode == 'quest') and ($player_nb == 2) ) {
			return RPGQuests::setMonsterFP($this->battle, $new_fp);
		}
		// creature is player
		else {
			return RPGPlayers::setPFOfPlayer($creature, $new_fp);
		}
	}
	
	//SKILLS
	public function add_skill($player_nb, $skill_type, $turn) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		
		if($player_nb == 1) {
			$current_skills = $this->battle->player1SkillsToString();
			
			if(!$this->battle->addPlayer1Skill($skill_type, $turn)) return false;
			
			if($current_skills == $this->battle->player1SkillsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setPlayerSkills($this->battle, $this->battle->player1SkillsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setPlayerSkills($this->battle, $this->battle->player1SkillsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setPlayerSkills($this->battle, $this->battle->player1SkillsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer1Skills($this->battle, $this->battle->player1SkillsToString())) return false;
			}
		} else {
			$current_skills = $this->battle->player2SkillsToString();
			
			if(!$this->battle->addPlayer2Skill($skill_type, $turn)) return false;
			
			if($current_skills == $this->battle->player2SkillsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setMonsterSkills($this->battle, $this->battle->player2SkillsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setMonsterSkills($this->battle, $this->battle->player2SkillsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setMonsterSkills($this->battle, $this->battle->player2SkillsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer2Skills($this->battle, $this->battle->player2SkillsToString())) return false;
			}
		}
		
		return true;
	}
	
	public function add_active_skill($player_nb, $skill_type, $data_array) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		
		if($player_nb == 1) {
			$current_active = $this->battle->player1ActiveSkillsToString();
			
			if(!$this->battle->addPlayer1ActiveSkill($skill_type, $data_array)) return false;
			
			if($curent_active == $this->battle->player1ActiveSkillsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setPlayerActiveSkills($this->battle, $this->battle->player1ActiveSkillsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setPlayerActiveSkills($this->battle, $this->battle->player1ActiveSkillsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setPlayerActiveSkills($this->battle, $this->battle->player1ActiveSkillsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer1ActiveSkills($this->battle, $this->battle->player1ActiveSkillsToString())) return false;
			}
		} else {
			$current_active = $this->battle->player2ActiveSkillsToString();
			
			if(!$this->battle->addPlayer2ActiveSkill($skill_type, $data_array)) return false;
			
			if($curent_active == $this->battle->player2ActiveSkillsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setMonsterActiveSkills($this->battle, $this->battle->player2ActiveSkillsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setMonsterActiveSkills($this->battle, $this->battle->player2ActiveSkillsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setMonsterActiveSkills($this->battle, $this->battle->player2ActiveSkillsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer2ActiveSkills($this->battle, $this->battle->player2ActiveSkillsToString())) return false;
			}
		}
		
		return true;
	}
	
	// BUFFS
	public function add_buff($player_nb, $skill_type, $data_array) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		
		if($player_nb == 1) {
			$current_buffs = $this->battle->player1BuffsToString();
			
			if(!$this->battle->addPlayer1Buff($skill_type, $data_array)) return false;
			
			if($current_buffs == $this->battle->player1BuffsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setPlayerBuffs($this->battle, $this->battle->player1BuffsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setPlayerBuffs($this->battle, $this->battle->player1BuffsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setPlayerBuffs($this->battle, $this->battle->player1BuffsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer1Buffs($this->battle, $this->battle->player1BuffsToString())) return false;
			}
		} else {
			$current_buffs = $this->battle->player2BuffsToString();
			
			if(!$this->battle->addPlayer2Buff($skill_type, $data_array)) return false;
			
			if($current_buffs == $this->battle->player2BuffsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setMonsterBuffs($this->battle, $this->battle->player2BuffsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setMonsterBuffs($this->battle, $this->battle->player2BuffsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setMonsterBuffs($this->battle, $this->battle->player2BuffsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer2Buffs($this->battle, $this->battle->player2BuffsToString())) return false;
			}
		}
		
		return true;
	}
	
	public function reset_active_skills($player_nb) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		
		if($player_nb == 1) {
			$current_active = $this->battle->player1ActiveSkillsToString();
			if($current_active == '') return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::resetPlayerActiveSkills($this->battle)) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::resetPlayerActiveSkills($this->battle)) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::resetPlayerActiveSkills($this->battle)) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::resetPlayer1ActiveSkills($this->battle)) return false;
			}
		} else {
			$current_active = $this->battle->player2ActiveSkillsToString();
			if($current_active == '') return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::resetMonsterActiveSkills($this->battle)) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::resetMonsterActiveSkills($this->battle)) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::resetMonsterActiveSkills($this->battle)) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::resetPlayer2ActiveSkills($this->battle)) return false;
			}
		}
		
		return true;
	}
	
	// ORBS
	public function set_active_orb($player_nb, $slot, $active) {
		if( ($player_nb != 1) and ($player_nb != 2) ) return true;
		
		if($player_nb == 1) {
			$current_orbs = $this->battle->player1ActiveOrbsToString();
			
			if(!$this->battle->setPlayer1ActiveOrb($slot, $active)) return false;
			
			if($current_orbs == $this->battle->player1ActiveOrbsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setPlayerActiveOrbs($this->battle, $this->battle->player1ActiveOrbsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setPlayerActiveOrbs($this->battle, $this->battle->player1ActiveOrbsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setPlayerActiveOrbs($this->battle, $this->battle->player1ActiveOrbsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer1ActiveOrbs($this->battle, $this->battle->player1ActiveOrbsToString())) return false;
			}
		} else {
			$current_orbs = $this->battle->player2ActiveOrbsToString();
			
			if(!$this->battle->setPlayer2ActiveOrb($slot, $active)) return false;
			
			if($current_orbs == $this->battle->player2ActiveOrbsToString()) return true;
			
			if($this->mode == 'pve') {
				if(!RPGPVEBattles::setMonsterActiveOrbs($this->battle, $this->battle->player2ActiveOrbsToString())) return false;
			} else if($this->mode == 'event') {
				if(!RPGEventBattles::setMonsterActiveOrbs($this->battle, $this->battle->player2ActiveOrbsToString())) return false;
			} else if($this->mode == 'quest') {
				if(!RPGQuests::setMonsterActiveOrbs($this->battle, $this->battle->player2ActiveOrbsToString())) return false;
			} else if($this->mode == 'pvp') {
				if(!RPGPVPBattles::setPlayer2ActiveOrbs($this->battle, $this->battle->player2ActiveOrbsToString())) return false;
			}
		}
		
		return true;
	}
	
	//GETTERS
	
	//stats
	public function get_hp(Creature &$creature, $player_nb) {
		// creature is PVE / EVENT monster
		if( ($this->mode == 'pve' or $this->mode == 'event' or $this->mode == 'quest') and ($player_nb == 2) )
			return $this->battle->getMonsterHP();
		// creature is player
		else
			return $creature->getPV();
	}
	
	public function get_fp(Creature &$creature, $player_nb) {
		// creature is PVE / EVENT monster
		if( ($this->mode == 'pve' or $this->mode == 'event' or $this->mode == 'quest') and ($player_nb == 2) )
			return $this->battle->getMonsterFP();
		// creature is player
		else
			return $creature->getPF();
	}
	
	public function get_stat_with_buff(Creature &$creature, $player_nb, $stat) {
		$v = 0;
		$buff = 0;
		
		switch($stat) {
			case STAT_ATTACK:
				{
					$v = $creature->getAttack() 
					+ $this->get_buff($player_nb, BUFF_TYPE_ATTACK) 
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_ATTACK);
				}
				break;
			case STAT_DEFENSE:
				{
					$v = $creature->getDefense()
					+ $this->get_buff($player_nb, BUFF_TYPE_DEFENSE)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_DEFENSE);
				}
				break;
			case STAT_SPEED:
				{
					$v = $creature->getSpeed()
					+ $this->get_buff($player_nb, BUFF_TYPE_SPEED)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_SPEED);
				}
				break;
			case STAT_FLUX:
				{
					$v = $creature->getFlux()
					+ $this->get_buff($player_nb, BUFF_TYPE_FLUX)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_FLUX);
				}
				break;
			case STAT_RESISTANCE:
				{
					$v = $creature->getResistance()
					+ $this->get_buff($player_nb, BUFF_TYPE_RESISTANCE)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_RESISTANCE);
				}
		}
		
		if($v < 0) $v = 0;
		return $v;
	}
	
	public function get_buff($player_nb, $buff_type) {
		$buff = 0;
		
		if( $player_nb == 2 ) {
			$buff = $this->battle->getPlayer2Buff($buff_type);
		}
		else {
			$buff = $this->battle->getPlayer1Buff($buff_type);
		}

		return $buff;
	}
	
	public function get_buff_by_stat($player_nb, Creature &$actor, Creature &$opponent, $stat) {
		$buff = 0;
		
		switch($stat) {
			case STAT_ATTACK:
				{
					$buff = $this->get_buff($player_nb, BUFF_TYPE_ATTACK)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_ATTACK);
				}
				break;
			case STAT_DEFENSE:
				{
					$buff = $this->get_buff($player_nb, BUFF_TYPE_DEFENSE)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_DEFENSE);
				}
				break;
			case STAT_SPEED:
				{
					$buff = $this->get_buff($player_nb, BUFF_TYPE_SPEED)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_SPEED);
				}
				break;
			case STAT_FLUX:
				{
					$buff = $this->get_buff($player_nb, BUFF_TYPE_FLUX)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_FLUX);
				}
				break;
			case STAT_RESISTANCE:
				{
					$buff = $this->get_buff($player_nb, BUFF_TYPE_RESISTANCE)
					- $this->get_buff($player_nb == 1 ? 2 : 1, DEBUFF_TYPE_RESISTANCE);
				}
				break;
		}
		
		$buff += $this->get_orb_bonus_by_stat($player_nb, $actor, $opponent, $stat);
		
		return (int) floor($buff);
	}
	
	//orbs
	public function get_player_orbs_effects($player_nb, Creature &$actor, Creature &$opponent) {
	
		if($player_nb == 1) {
			return $this->get_orbs_effects(1, $actor, $opponent);
		} else {
			if($this->mode == 'pve' or $this->mode == 'event' or $this->mode == 'quest') return array();
			
			return $this->get_orbs_effects(2, $actor, $opponent);
		}
		
	}
	
	public function get_orbs_effects($player_nb, Player &$player, Creature &$opponent) {
		$effects = array();
		
		$i = 1;
		for( ; $i <= 4 ; $i++) {
			$orb = $player->getOrb($i);
			if($orb == null) continue;
			
			if($player_nb == 1) {
				if(!$this->battle->player1OrbIsActive($i)) continue;
			}
			else {
				if(!$this->battle->player2OrbIsActive($i)) continue;
			}
			
			$effect = $orb->getEffect();
			if($effect == "") continue;
			$trigger = $orb->getEffectTrigger();
			if($trigger == "") continue;
			
			if($this->orb_trigger_is_active($trigger, $player_nb, $player, $opponent)) $effects[$effect] = array( 'value' => true, 'slot' => $i,);
		}
		
		return $effects;
	}

	public function orb_trigger_is_active($trigger, $player_nb, Player &$actor, Creature &$opponent) {
		
		switch($trigger) {
			/* Battle Start */
			case ORB_TRIGGER_BATTLE_START:
			{
				if($this->battle->getTurn() >= 1) return true;
			}
			break;
			/* PV = 0 */
			case ORB_TRIGGER_PV_0:
			{
				if($actor->getPV() <= 0) return true;
			}
			break;
			/* PV <= 25% */
			case ORB_TRIGGER_PV_QUARTER:
			{
				$actor_hp = $this->get_hp($actor, $player_nb);
				if($actor_hp == 0) return false;
				
				$pv_per = $actor_hp / $actor->getMaxPV();
				if($pv_per <= 0.25) return true;
			}
			break;
			/* Opponent PV <= 3% */
			case ORB_TRIGGER_OPPONENT_PV_3PER:
			{
				$target_hp = $this->get_hp($opponent, $player_nb == 1 ? 2 : 1);
				if($target_hp == 0) return false;
				
				$pv_per = $target_hp / $opponent->getMaxPV();
				if($pv_per <= 0.03) { return true; }
			}
			break;
		}
		
		return false;
	}
	
	public function get_orb_bonus_by_stat($player_nb, Creature &$actor, Creature &$opponent, $stat_type) {
		$orbs_effects = $this->get_player_orbs_effects($player_nb, $actor, $opponent);
		
		$bonus = 0;
		
		foreach($orbs_effects as $effect => $data) {
			switch($stat_type) {
				case STAT_ATTACK:
				case STAT_DEFENSE:
				case STAT_SPEED:
				case STAT_FLUX:
				case STAT_RESISTANCE:
				case STAT_CRITICAL:
					{
						if($this->orb_effect_is_stat_bonus($effect, $stat_type)) {
							$bonus += $this->get_orb_effect_stat_bonus($effect, $player_nb, $actor, $opponent);
						}
					}
					break;
			}
		}
		
		return (int) floor($bonus);
	}
	
	protected function orb_effect_is_stat_bonus($effect, $stat_type) {
		switch($stat_type) {
			case STAT_ATTACK:
				{
					switch($effect) {
						case ORB_EFFECT_ATTACK_PLUS:
							return true;
					}
				}
				break;
			case STAT_DEFENSE:
				{
					switch($effect) {
						case ORB_EFFECT_DEFENSE_PLUS:
							return true;
					}
				}
				break;
			case STAT_SPEED:
				{
					switch($effect) {
						case ORB_EFFECT_SPEED_PLUS:
							return true;
					}
				}
				break;
			case STAT_FLUX:
				{
					switch($effect) {
						case ORB_EFFECT_FLUX_PLUS:
							return true;
					}
				}
				break;
			case STAT_RESISTANCE:
				{
					switch($effect) {
						case ORB_EFFECT_RESISTANCE_PLUS:
							return true;
					}
				}
				break;
			case STAT_CRITICAL:
				{
					switch($effect) {
						case ORB_EFFECT_BERSERK:
							return true;
					}
				}
				break;
		}
		
		return false;
	}
	
	public function get_orb_effect_stat_bonus($effect, $player_nb, Creature &$actor, Creature &$opponent) {
		
		$bonus = 0;
		
		switch($effect) {
			/* BERSERK */
			case ORB_EFFECT_BERSERK:
				{
					$actor_hp = $this->get_hp($actor, $player_nb);
					$actor_max_hp = $actor->getMaxPV();
					if($actor_hp > $actor_max_hp) return 0;
					
					$hp_per = $actor_hp / $actor_max_hp;
					$bonus = ((1.0 - $hp_per) / 2) * 100; // +1% crit each 2% HP lost
				}
				break;
			/* ATTACK+ */
			case ORB_EFFECT_ATTACK_PLUS:
			/* DEFENSE+ */
			case ORB_EFFECT_DEFENSE_PLUS:
			/* SPEED+ */
			case ORB_EFFECT_SPEED_PLUS:
			/* FLUX+ */
			case ORB_EFFECT_FLUX_PLUS:
			/* RESISTANCE+ */
			case ORB_EFFECT_RESISTANCE_PLUS:
				{
					$actor_hp = $this->get_hp($actor, $player_nb);
					$actor_max_hp = $actor->getMaxPV();
					if($actor_hp > $actor_max_hp) return 0;
					
					$hp_per = $actor_hp / $actor_max_hp;
					$bonus_per = 1 - $hp_per; // +1% each 1% HP lost
					
					if($effect == ORB_EFFECT_ATTACK_PLUS) $bonus = $actor->getBaseAtk() * $bonus_per;
					else if($effect == ORB_EFFECT_DEFENSE_PLUS) $bonus = $actor->getBaseDef() * $bonus_per;
					else if($effect == ORB_EFFECT_SPEED_PLUS) $bonus = $actor->getBaseSpd() * $bonus_per;
					else if($effect == ORB_EFFECT_FLUX_PLUS) $bonus = $actor->getBaseFlux() * $bonus_per;
					else $bonus = $actor->getBaseRes() * $bonus_per;
				}
				break;
		}
		
		return (int) floor($bonus);
	}	
}

?>