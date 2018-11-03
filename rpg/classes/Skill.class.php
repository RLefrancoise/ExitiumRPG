<?php
	include_once(__DIR__ . '/../../common.php');
	
	abstract class AbstractSkill {
	
		protected $name;
		protected $desc;
		protected $type;
		protected $element;
		protected $cooldown;
		protected $pf;
		protected $subskill;
		
		/*public static function getSkillByType($type) {
			switch($type) {
				case SKILL_TYPE_POWER:
					return new PowerSkill();
				case SKILL_TYPE_SHIELD:
					return new ShieldSkill();
				case SKILL_TYPE_PARALYZE:
					return new ParalyzeSkill();
				case SKILL_TYPE_COUNTER:
					return new CounterSkill();
				case SKILL_TYPE_CURSE:
					return new CurseSkill();
				case SKILL_TYPE_DOUBLESTRIKE:
					return new DoubleStrikeSkill();
				case SKILL_TYPE_ABSORB:
					return new AbsorbSkill();
				case SKILL_TYPE_REGEN:
					return new RegenSkill();
				default:
					return null;
			}
		}*/
		
		protected function __construct($name, $desc, $type, $element, $cooldown = 1, $pf = 0, $subskill = '') {
			$this->name = $name;
			$this->desc = $desc;
			$this->type = $type;
			$this->element = $element;
			$this->cooldown = $cooldown;
			$this->pf = $pf;
			$this->subskill = $subskill;
		}
		
		public function getName() {
			global $_SKILLS_DATA;
			
			if($this->subskill != '') 
				return $this->name . '/' . $_SKILLS_DATA[$this->subskill]['name'];
			else
				return $this->name;
		}
		
		public function getDescription() {
			global $_SKILLS_DATA;
			
			if($this->subskill != '') 
				return $this->desc . '<br>' . $_SKILLS_DATA[$this->subskill]['desc'];
			else
				return $this->desc;
		}
		
		public function getType() {
			return $this->type;
		}
		
		public function getElement() {
			return $this->element;
		}
		
		public function getCooldown() {
			global $_SKILLS_DATA;
			
			if($this->subskill != '') {
				$cd = ($this->cooldown > $_SKILLS_DATA[$this->subskill]['cooldown']) ? $this->cooldown * 1.5 : $_SKILLS_DATA[$this->subskill]['cooldown'] * 1.5;
				return (int) floor($cd);
			} else
				return $this->cooldown;
				
		}
		
		public function getPF() {
			global $_SKILLS_DATA;
			
			if($this->subskill != '') {
				$pf = (int) floor(($this->pf + $_SKILLS_DATA[$this->subskill]['pf']) * 0.75);
				if($pf < $this->pf or $pf < $_SKILLS_DATA[$this->subskill]['pf']) $pf = ($this->pf > $_SKILLS_DATA[$this->subskill]['pf'] ? $this->pf : $_SKILLS_DATA[$this->subskill]['pf']);
	
				return $pf;
			}
			else
				return $this->pf;
		}
		
		public function getSubSkill() {
			return $this->subskill;
		}
		
		/* Returns description with name
		*/
		public function getFullDescription() {
			global $_ELEMENTS_STRINGS, $_SKILLS_DATA;
			
			$html = '';
			
			if($this->cooldown == 1)
				$html = '<strong>'.$this->getName().'</strong><br>'.$this->getDescription().'<br><em>Coût en PF : '.$this->getPF().'<br>Cooldown : '.$this->getCooldown().' tour<br>Elément : '.$_ELEMENTS_STRINGS[$this->element].'</em>';
			else
				$html = '<strong>'.$this->getName().'</strong><br>'.$this->getDescription().'<br><em>Coût en PF : '.$this->getPF().'<br>Cooldown : '.$this->getCooldown().' tours<br>Elément : '.$_ELEMENTS_STRINGS[$this->element].'</em>';
				
			//if($this->subskill != '')
			//	$html .= '<br><em>Sous-skill : ' . $_SKILLS_DATA[$this->subskill]['name'] . '</em>';
				
			return $html;
		}
		
		public function setElement($element) {
			global $_ELEMENTS_STRINGS;
			
			if(array_key_exists($element, $_ELEMENTS_STRINGS))
				$this->element = $element;
		}
	}
	
	class Skill extends AbstractSkill {
		
		public static function getSkillByType($type, $element, $subskill) {
			global $_SKILLS_DATA;
			
			$type_is_valid = false;
			foreach($_SKILLS_DATA as $key => $value) {
				if($key == $type) { $type_is_valid = true; break; }
			}
			
			if($type_is_valid)
				return new Skill($type, $element, $subskill);
			else
				return null;
				
			/*switch($type) {
				case SKILL_TYPE_POWER:
				case SKILL_TYPE_SHIELD:
				case SKILL_TYPE_PARALYZE:
				case SKILL_TYPE_COUNTER:
				case SKILL_TYPE_CURSE:
				case SKILL_TYPE_DOUBLESTRIKE:
				case SKILL_TYPE_ABSORB:
				case SKILL_TYPE_REGEN:
					return new Skill($type, $element);
				default:
					return null;
			}*/
		}
		
		protected function __construct($type, $element, $subskill) {
			global $_SKILLS_NAMES, $_SKILLS_DESCRIPTIONS, $_SKILLS_COOLDOWNS, $_SKILLS_DATA;
			
			//if(!in_array($type, $_SKILLS_DATA)) return;
			$type_is_valid = false;
			foreach($_SKILLS_DATA as $key => $value) {
				if($key == $type) { $type_is_valid = true; break; }
			}
			
			if(!$type_is_valid) return;
			
			/*switch($type) {
				case SKILL_TYPE_POWER:
				case SKILL_TYPE_SHIELD:
				case SKILL_TYPE_PARALYZE:
				case SKILL_TYPE_COUNTER:
				case SKILL_TYPE_CURSE:
				case SKILL_TYPE_DOUBLESTRIKE:
				case SKILL_TYPE_ABSORB:
				case SKILL_TYPE_REGEN:*/
					parent::__construct($_SKILLS_DATA[$type]['name'], $_SKILLS_DATA[$type]['desc'], $type, $element, $_SKILLS_DATA[$type]['cooldown'], $_SKILLS_DATA[$type]['pf'], $subskill);
					/*break;
				default:
					break;
			}*/
		}
	}
	
?>