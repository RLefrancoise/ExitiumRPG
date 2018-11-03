<?php

//general
define("SITE_URL", "http://localhost/ExitiumRPG/");
define("RPG_POST_USER_ID", 59);
define("RPG_KARMA_MIN_POSTS", 10);
define("RPG_ACCOUNT_LOGIN", "Yuki Kazuki");
define("RPG_ACCOUNT_PASSWORD", "");

define("MAX_ENERGY", 30);

$UNLIMITED_USERS = array(
	2,
);

$SALARIES = array(
	//1 Site Admin, nothing
	2 	=> 2500, // Général de l'ouest
	3	=> 2500, // Général de l'est
	4	=> 2500, // Général du Nord
	5	=> 2500, // Général du Sud
	6	=> 3750, // 3e Empereur
	7	=> 2000, // Soldat Impérial
	8	=> 2000, // Esclave
	9	=> 3000, // Intendant
	10	=> 3750, // Dirigeante
	11	=> 2500, // Leader de l'ouest
	12	=> 2500, // Leader de l'est
	13	=> 2500, // Leader du Nord
	14	=> 2500, // Leader du Sud
	15	=> 2000, // Rebelles
	16	=> 2000, // Libertin
	17	=> 3000, // Conseiller
	18	=> 3750, // Suprême
	19	=> 2000, // Traqueur
	20	=> 2000, // Soldat Exitian
	21	=> 3000, // Haut Traqueur
	22	=> 3750, // Chef Eclypse
	23	=> 2500, // Associé n°1
	24	=> 2000, // Membre Eclypse
	25	=> 2000, // Citoyen
	// Déesse temporelle -> rien
	27	=> 3000, // Bras Droit
	28	=> 2000, // Esclave de l'empereur
	29	=> 2500, // La demoiselle
	30	=> 2500, // Associé n°2
	31	=> 2500, // Associé n°3
	32	=> 2500, // Associé n°4
	33	=> 2000, // Personnel
	34	=> 2000, // Personnel
	35	=> 2500, // Associé n°4
	36	=> 2500, // Associé n°3
	37	=> 2500, // Associé n°2
);

//clans
define("CLAN_IMG_PATH", __DIR__ . '../../images/rpg/clans/see/clan_images/');
define("CLAN_CREATE_PRICE", 1500);
define("MAX_CLAN_IMAGE_WIDTH", 600);
define("MAX_CLAN_IMAGE_HEIGHT", 400);
define("MAX_CLAN_IMAGE_SIZE", 500000);
define("MAX_CHATBOX_MSG", 50);

//inn
define("REST_PRICE", 100);
define("SLEEP_PRICE", 180);

//creature
define("STAT_ATTACK", "atk");
define("STAT_DEFENSE", "def");
define("STAT_SPEED", "spd");
define("STAT_FLUX", "flux");
define("STAT_RESISTANCE", "res");
define("STAT_CRITICAL", "critical");
define("STAT_PV", "pv");
define("STAT_PF", "pf");

//player
//--default
define("DEFAULT_LEVEL", 1);
define("DEFAULT_XP", 0);
define("DEFAULT_KARMA", 0);
define("MAX_LEVEL", 30);
define("MIN_PV", 50);
define("MIN_PF", 50);
define("DEFAULT_ATK", 5);
define("DEFAULT_DEF", 5);
define("DEFAULT_SPD", 5);
define("DEFAULT_FLUX", 5);
define("DEFAULT_RES", 5);
define("DEFAULT_POINTS", 0);
define("DEFAULT_RALZ", 1000);

//--stats
define("PV_PER_DEF_POINT", 5);
define("PF_PER_RES_POINT", 5);
define("ATK_PER_POINT", 1);
define("DEF_PER_POINT", 1);
define("SPD_PER_POINT", 1);
define("FLUX_PER_POINT", 1);
define("RES_PER_POINT", 1);

define("POINTS_PER_LEVEL", 3);
define("LEADER_PV_MULTIPLIER", 4);
define("LEADER_PF_MULTIPLIER", 4);
define("LEADER_STAT_EFFECT_MULTIPLIER", 1);

define("STAT_MAX_CAPACITY", 45);
define("MAX_KARMA", 5);

//monsters
//--behavior (flags)
define("MONSTER_BEHAVIOR_ATTACK", 'attack');
define("MONSTER_BEHAVIOR_DEFEND", 'defend');
define("MONSTER_BEHAVIOR_SKILL", 'skill');
define("MONSTER_BEHAVIOR_IDLE", 'idle');

$_MONSTERS_BEHAVIORS = array(
	MONSTER_BEHAVIOR_ATTACK,
	MONSTER_BEHAVIOR_DEFEND,
	MONSTER_BEHAVIOR_SKILL,
	MONSTER_BEHAVIOR_IDLE,
);

$_MONSTERS_BEHAVIOR_FLAGS = array(
	MONSTER_BEHAVIOR_ATTACK			=> 0x1, //001
	MONSTER_BEHAVIOR_DEFEND			=> 0x2, //010
	MONSTER_BEHAVIOR_SKILL			=> 0x4, //100
	MONSTER_BEHAVIOR_IDLE			=> 0x8 // 1000
);


//skills
$_SKILLS_REQUIRED_LEVELS = array(1 => 1, 2 => 10, 3 => 20, 4 => 30);

define("SKILL_KIND_PHYSICAL", "physical");
define("SKILL_KIND_MAGICAL", "magical");
define("SKILL_KIND_BUFF", "buff");
define("SKILL_KIND_HEAL", "heal");
define("SKILL_KIND_HELP", "help");


define("SKILL_TYPE_POWER", "power"); // damage x 3
define("SKILL_TYPE_SHIELD", "shield"); // damage / 3
define("SKILL_TYPE_ARCANA", 'arcana'); // magic damage x 3
define("SKILL_TYPE_BARRIER", 'barrier'); // magic damage / 3
define("SKILL_TYPE_PARALYZE", "paralyze"); // opponent can't attack next turn
define("SKILL_TYPE_COUNTER", "counter"); // repels damage to opponent
define("SKILL_TYPE_CURSE", "curse"); // opponent's hp decreases each turn
define("SKILL_TYPE_DOUBLESTRIKE", "doublestrike"); // can attack twice
define("SKILL_TYPE_ABSORB", "absorb"); // received damage give hp
define("SKILL_TYPE_REGEN", "regen"); // hp increases each turn
define("SKILL_TYPE_CANCEL", "cancel"); // remove effects of both players
define("SKILL_TYPE_DISPEL", "dispel"); // remove effects of opponent
define("SKILL_TYPE_ILLUSION", 'illusion'); // 100% evade for 2 turns, decrease 5% PV each turn
define("SKILL_TYPE_LIFEDRAIN", 'lifedrain'); // drain PV of opponent
define("SKILL_TYPE_MAGICDRAIN", 'magicdrain'); // drain PF of opponent
define("SKILL_TYPE_WRATH", 'wrath'); // buff attack
define("SKILL_TYPE_PROTECTION", 'protection'); // buff defense
define("SKILL_TYPE_GODSPEED", 'godspeed'); // buff speed
define("SKILL_TYPE_FOCUS", 'focus'); // buff flux
define("SKILL_TYPE_SHIELDING", 'shielding'); // buff resistance
define("SKILL_TYPE_INTIMIDATION", 'intimidation'); // debuff attack
define("SKILL_TYPE_ARMORBREAK", 'armorbreak'); // debuff defense
define("SKILL_TYPE_STUN", 'stun'); // debuff speed
define("SKILL_TYPE_MAGICSEAL", 'magicseal'); // debuff flux
define("SKILL_TYPE_FRAGILITY", 'fragility'); // debuff resistance
define("SKILL_TYPE_HEAL", 'heal'); //heal hp
define("SKILL_TYPE_ENERGY_BLOOD", 'energy_blood'); //give hp to increase fp

//elements
define("ELEMENT_WATER", 'water');
define("ELEMENT_THUNDER", 'thunder');
define("ELEMENT_FIRE", 'fire');
define("ELEMENT_ICE", 'ice');
define("ELEMENT_LIGHT", 'light');
define("ELEMENT_DARKNESS", 'darkness');
define("ELEMENT_EARTH", 'earth');
define("ELEMENT_WIND", 'wind');
define("ELEMENT_NONE", 'none');

$_ELEMENTS_STRINGS = array(
	ELEMENT_WATER	=> 'Eau',
	ELEMENT_THUNDER	=> 'Foudre',
	ELEMENT_FIRE	=> 'Feu',
	ELEMENT_ICE		=> 'Glace',
	ELEMENT_LIGHT	=> 'Lumière',
	ELEMENT_DARKNESS => 'Ténèbres',
	ELEMENT_EARTH	=> 'Terre',
	ELEMENT_WIND	=> 'Vent',
	ELEMENT_NONE	=> 'Sans élément',
);

//buffs
define("BUFF_TYPE_ATTACK", 'buff_attack');
define("BUFF_TYPE_DEFENSE", 'buff_defense');
define("BUFF_TYPE_SPEED", 'buff_speed');
define("BUFF_TYPE_FLUX", 'buff_flux');
define("BUFF_TYPE_RESISTANCE", 'buff_resistance');

define("DEBUFF_TYPE_ATTACK", 'debuff_attack');
define("DEBUFF_TYPE_DEFENSE", 'debuff_defense');
define("DEBUFF_TYPE_SPEED", 'debuff_speed');
define("DEBUFF_TYPE_FLUX", 'debuff_flux');
define("DEBUFF_TYPE_RESISTANCE", 'debuff_resistance');

$_BUFFS = array(
	/* Buffs */
	BUFF_TYPE_ATTACK,
	BUFF_TYPE_DEFENSE,
	BUFF_TYPE_SPEED,
	BUFF_TYPE_FLUX,
	BUFF_TYPE_RESISTANCE,
	
	/* Debuffs */
	DEBUFF_TYPE_ATTACK,
	DEBUFF_TYPE_DEFENSE,
	DEBUFF_TYPE_SPEED,
	DEBUFF_TYPE_FLUX,
	DEBUFF_TYPE_RESISTANCE,
);

//effects
define("EFFECT_DAMAGE_MULTIPLIER", 'damage_multiplier');
define("EFFECT_DAMAGE_DIVIDER", 'damage_divider');
define("EFFECT_MAGIC_DAMAGE_MULTIPLIER", 'magic_damage_multiplier');
define("EFFECT_MAGIC_DAMAGE_DIVIDER", 'magic_damage_divider');
define("EFFECT_ATTACK_NUMBER", 'attack_number');
define("EFFECT_PARALYZE", 'paralyze');
define("EFFECT_REPEL_DAMAGE", 'repel_damage');
define("EFFECT_ABSORB_DAMAGE", 'absorb_damage');
define("EFFECT_DOUBLESTRIKE", 'double_strike');
define("EFFECT_REGEN", 'regen');
define("EFFECT_CURSE", 'curse');
define("EFFECT_EVADE", 'evade');
define("EFFECT_LOSE_HP", 'lose_hp');

$_DEFAULT_EFFECTS = array(
	EFFECT_DAMAGE_MULTIPLIER 	=> 1,
	EFFECT_DAMAGE_DIVIDER		=> 1,
	EFFECT_MAGIC_DAMAGE_MULTIPLIER	=> 1,
	EFFECT_MAGIC_DAMAGE_DIVIDER	=> 1,
	EFFECT_ATTACK_NUMBER		=> 1,
	EFFECT_PARALYZE				=> false,
	EFFECT_REPEL_DAMAGE			=> false,
	EFFECT_ABSORB_DAMAGE 		=> false,
	EFFECT_DOUBLESTRIKE			=> false,
	EFFECT_REGEN				=> false,
	EFFECT_CURSE				=> false,
	EFFECT_EVADE				=> false,
	EFFECT_LOSE_HP				=> false,
);

$_SKILLS_DATA = array(
	SKILL_TYPE_POWER 		=> array(	'name'		=> 'Puissance',
										'desc'		=> 'Lance 3 attaques successives.',
										'cooldown'	=> 4,
										'pf'		=> 30,
										'priority'	=> 1,
										'kind'		=>	SKILL_KIND_PHYSICAL),
	SKILL_TYPE_SHIELD 		=> array(	'name'		=> 'Bouclier',
										'desc'		=> 'Divise les dégâts reçus par 3.',
										'cooldown'	=> 4,
										'pf'		=> 30,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_PHYSICAL),
	SKILL_TYPE_ARCANA 		=> array(	'name'		=> 'Arcanes',
										'desc'		=> 'Lance 3 attaques magiques successives.',
										'cooldown'	=> 4,
										'pf'		=> 30,
										'priority'	=> 1,
										'kind'		=>	SKILL_KIND_MAGICAL),
	SKILL_TYPE_BARRIER 		=> array(	'name'		=> 'Barrière',
										'desc'		=> 'Divise les dégâts magiques reçus par 3.',
										'cooldown'	=> 4,
										'pf'		=> 30,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_MAGICAL),
	SKILL_TYPE_PARALYZE		=> array(	'name'		=> 'Immobilisation',
										'desc'		=> 'Empêche l\'adversaire d\'attaquer.',
										'cooldown'	=> 3,
										'pf'		=> 15,
										'priority'	=> 1,
										'kind'		=>	SKILL_KIND_HELP),
	SKILL_TYPE_COUNTER		=> array(	'name'		=> 'Contre',
										'desc'		=> 'Renvoie une attaque reçue.',
										'cooldown'	=> 5,
										'pf'		=> 35,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_HELP,
										'sound'		=> 'Renvoi_atk'),
	SKILL_TYPE_CURSE		=> array(	'name'		=> 'Malédiction',
										'desc'		=> 'Retire des PV à l\'adversaire progressivement.',
										'cooldown'	=> 5,
										'pf'		=> 40,
										'priority'	=> 1,
										'kind'		=>	SKILL_KIND_MAGICAL),
	SKILL_TYPE_DOUBLESTRIKE	=> array(	'name'		=> 'Double Frappe',
										'desc'		=> 'Frappe puis frappe une nouvelle fois le tour suivant.',
										'cooldown'	=> 3,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_PHYSICAL),
	SKILL_TYPE_ABSORB		=> array(	'name'		=> 'Absorption',
										'desc'		=> 'Convertit une attaque reçue en soin.',
										'cooldown'	=> 5,
										'pf'		=> 35,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_HELP),
	SKILL_TYPE_REGEN		=> array(	'name'		=> 'Régénération',
										'desc'		=> 'Régénère des PV progressivement.',
										'cooldown'	=> 5,
										'pf'		=> 40,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_HEAL),
	SKILL_TYPE_CANCEL		=> array(	'name'		=> 'Annulation',
										'desc'		=> 'Annule les effets progressifs des deux joueurs.',
										'cooldown'	=> 3,
										'pf'		=> 30,
										'priority'	=> 3,
										'kind'		=>	SKILL_KIND_HELP),
	SKILL_TYPE_DISPEL		=> array(	'name'		=> 'Dissipation',
										'desc'		=> 'Annule les effets progressifs de l\'adversaire.',
										'cooldown'	=> 5,
										'pf'		=> 35,
										'priority'	=> 3,
										'kind'		=>	SKILL_KIND_HELP),
	SKILL_TYPE_ILLUSION		=> array(	'name'		=> 'Illusion',
										'desc'		=> 'Empêche d\'être touché pendant 2 tours en échange de quelques PV.',
										'cooldown'	=> 4,
										'pf'		=> 40,
										'priority'	=> 3,
										'kind'		=>	SKILL_KIND_HELP),
	SKILL_TYPE_LIFEDRAIN	=> array(	'name'		=> 'Drain de vie',
										'desc'		=> 'Draine les PV de l\'adversaire.',
										'cooldown'	=> 3,
										'pf'		=> 15,
										'priority'	=> 1,
										'kind'		=>	SKILL_KIND_MAGICAL),
	SKILL_TYPE_MAGICDRAIN	=> array(	'name'		=> 'Drain de magie',
										'desc'		=> 'Draine les PF de l\'adversaire.',
										'cooldown'	=> 3,
										'pf'		=> 0,
										'priority'	=> 1,
										'kind'		=>	SKILL_KIND_MAGICAL),
	SKILL_TYPE_WRATH		=> array(	'name'		=> 'Fureur',
										'desc'		=> 'Booste l\'attaque durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_PROTECTION	=> array(	'name'		=> 'Protection',
										'desc'		=> 'Booste la défense durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_GODSPEED		=> array(	'name'		=> 'Célérité',
										'desc'		=> 'Booste la vitesse durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_FOCUS		=> array(	'name'		=> 'Concentration',
										'desc'		=> 'Booste le flux durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_SHIELDING	=> array(	'name'		=> 'Blindage',
										'desc'		=> 'Booste la résistance durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_INTIMIDATION	=> array(	'name'		=> 'Intimidation',
										'desc'		=> 'Baisse l\'attaque de l\'ennemi durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_ARMORBREAK	=> array(	'name'		=> 'Brise-Armure',
										'desc'		=> 'Baisse la défense de l\'ennemi durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_STUN			=> array(	'name'		=> 'Entrave',
										'desc'		=> 'Baisse la vitesse de l\'ennemi durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_MAGICSEAL	=> array(	'name'		=> 'Sceau magique',
										'desc'		=> 'Baisse le flux de l\'ennemi durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_FRAGILITY	=> array(	'name'		=> 'Fragilité',
										'desc'		=> 'Baisse la résistance de l\'ennemi durant 3 tours.',
										'cooldown'	=> 4,
										'pf'		=> 20,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_BUFF),
	SKILL_TYPE_HEAL			=> array(	'name'		=> 'Soins',
										'desc'		=> 'Soigne le lanceur en lui redonnant des PV.',
										'cooldown'	=> 4,
										'pf'		=> 35,
										'priority'	=> 2,
										'kind'		=>	SKILL_KIND_HEAL),
	SKILL_TYPE_ENERGY_BLOOD	=> array(	'name'		=>	'Sang énergisant',
										'desc'		=>	'Sacrifie quelques PV pour rendre des PF.',
										'cooldown'	=>	3,
										'pf'		=>	0,
										'priority'	=>	2,
										'kind'		=>	SKILL_KIND_HEAL),
);

//battle
define("BASE_ACCURACY_VALUE", 60);
define("BASE_CRITICAL_VALUE", 5);

define("DEFAULT_PVP_BGM", "PvP");
define("PVP_TURN_SECONDS", 30);
define("PVP_MAX_TURNS_FOR_CANCEL", 3);
define("PVP_WIN_HONOR_POINTS", 2);
define("PVP_LOSE_HONOR_POINTS", 1);
define("PVP_DRAW_HONOR_POINTS", 1);

define("BATTLE_ANIMS_PATH", "images/rpg/battle/animations/");
define("BATTLE_SOUNDS_PATH", "rpg/sound/effects/");
define("BATTLE_JINGLE_VICTORY", "victory");
define("BATTLE_JINGLE_GAMEOVER", "gameover");

$_BATTLE_ANIMS = array( 'attack'	=> array (	'path' => 'Atk-Simple.png',
												'duration' => 1000,
												'width' => 192,
												'height' => 192,
												'frames' => 10,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_simple'),
												
						'item'		=> array (	'path' => 'Objets.png',
												'duration' => 2700,
												'width' => 192,
												'height' => 192,
												'frames' => 27,
												'delay' => 150,
												'priority' => 2,
												'sound'	=> 'Objets'),
												
						'Atk-Eau'	=> array (	'path' => 'Atk-Eau.png',
												'duration' => 1000,
												'width' => 192,
												'height' => 192,
												'frames' => 10,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_eau'),
												
						'Atk-Elec'	=> array (	'path' => 'Atk-Elec.png',
												'duration' => 2800,
												'width' => 192,
												'height' => 192,
												'frames' => 17,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_elec'),
												
						'Atk-Feu'	=> array (	'path' => 'Atk-Feu.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_feu'),
												
						'Atk-Glace'	=> array (	'path' => 'Atk-Glace.png',
												'duration' => 2800,
												'width' => 192,
												'height' => 192,
												'frames' => 28,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_glace'),
												
						'Atk-Lumière' => array ('path' => 'Atk-Lumière.png',
												'duration' => 3300,
												'width' => 192,
												'height' => 192,
												'frames' => 33,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_lumière'),
												
						'Atk-Ténèbres' => array ('path' => 'Atk-Ténèbres.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_ténèbres'),
												
						'Atk-Terre' => array (	'path' => 'Atk-Terre.png',
												'duration' => 1600,
												'width' => 192,
												'height' => 192,
												'frames' => 16,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_terre'),
												
						'Atk-Vent' => array (	'path' => 'Atk-Vent.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Atk_vent'),
												
						'Def-Eau'	=> array (	'path' => 'Def-Eau.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_eau'),
												
						'Def-Elec'	=> array (	'path' => 'Def-Elec.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_elec'),
												
						'Def-Feu'	=> array (	'path' => 'Def-Feu.png',
												'duration' => 2500,
												'width' => 192,
												'height' => 192,
												'frames' => 25,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_feu'),
												
						'Def-Glace'	=> array (	'path' => 'Def-Glace.png',
												'duration' => 3300,
												'width' => 192,
												'height' => 192,
												'frames' => 33,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_glace'),
												
						'Def-Lumière'	=> array (	'path' => 'Def-Lumière.png',
												'duration' => 2200,
												'width' => 192,
												'height' => 192,
												'frames' => 22,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_lumière'),
												
						'Def-Ténèbres'	=> array (	'path' => 'Def-Ténèbres.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_ténèbres'),
												
						'Def-Terre'	=> array (	'path' => 'Def-Terre.png',
												'duration' => 1500,
												'width' => 192,
												'height' => 192,
												'frames' => 15,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_terre'),
												
						'Def-Vent'	=> array (	'path' => 'Def-Vent.png',
												'duration' => 3300,
												'width' => 192,
												'height' => 192,
												'frames' => 33,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Def_vent'),
												
						'Heal'		=> array (	'path' => 'Heal.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'heal'),
												
						'Buff-Eau'	=> array (	'path' => 'Buff-Eau.png',
												'duration' => 5000,
												'width' => 192,
												'height' => 192,
												'frames' => 50,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_eau'),
												
						'Buff-Elec'	=> array (	'path' => 'Buff-Elec.png',
												'duration' => 1000,
												'width' => 192,
												'height' => 192,
												'frames' => 10,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_elec'),
						
						'Buff-Feu'		=> array (	'path' => 'Buff-Feu.png',
												'duration' => 2500,
												'width' => 192,
												'height' => 192,
												'frames' => 25,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_feu'),
												
						'Buff-Glace'	=> array (	'path' => 'Buff-Glace.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_glace'),
												
						'Buff-Lumière'	=> array (	'path' => 'Buff-Lumière.png',
												'duration' => 2500,
												'width' => 192,
												'height' => 192,
												'frames' => 25,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_lumière'),
												
						'Buff-Ténèbres'	=> array (	'path' => 'Buff-Ténèbres.png',
												'duration' => 2500,
												'width' => 192,
												'height' => 192,
												'frames' => 25,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_ténèbres'),
												
						'Buff-Terre'	=> array (	'path' => 'Buff-Terre.png',
												'duration' => 3300,
												'width' => 192,
												'height' => 192,
												'frames' => 33,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_terre'),
												
						'Buff-Vent'	=> array (	'path' => 'Buff-Vent.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Buff_vent'),
												
						'DOT-Eau'	=> array (	'path' => 'DOT-Eau.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_eau'),
												
						'DOT-Elec'	=> array (	'path' => 'DOT-Elec.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_elec'),
												
						'DOT-Feu'	=> array (	'path' => 'DOT-Feu.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_feu'),
												
						'DOT-Glace'	=> array (	'path' => 'DOT-Glace.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_glace'),
												
						'DOT-Lumière'	=> array (	'path' => 'DOT-Lumière.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_lumière'),
												
						'DOT-Ténèbres'	=> array (	'path' => 'DOT-Ténèbres.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_ténèbres'),
												
						'DOT-Terre'	=> array (	'path' => 'DOT-Terre.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_terre'),
												
						'DOT-Vent'	=> array (	'path' => 'DOT-Vent.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'DOT_vent'),
												
						'Debuff-Eau'	=> array (	'path' => 'Debuff-Eau.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_eau'),
												
						'Debuff-Elec'	=> array (	'path' => 'Debuff-Elec.png',
												'duration' => 2500,
												'width' => 192,
												'height' => 192,
												'frames' => 25,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_elec'),
												
						'Debuff-Feu'	=> array (	'path' => 'Debuff-Feu.png',
												'duration' => 2000,
												'width' => 192,
												'height' => 192,
												'frames' => 20,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_feu'),
												
						'Debuff-Glace'	=> array (	'path' => 'Debuff-Glace.png',
												'duration' => 2500,
												'width' => 192,
												'height' => 192,
												'frames' => 25,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_glace'),
												
						'Debuff-Lumière'	=> array (	'path' => 'Debuff-Lumière.png',
												'duration' => 2300,
												'width' => 192,
												'height' => 192,
												'frames' => 23,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_lumière'),
												
						'Debuff-Ténèbres'	=> array (	'path' => 'Debuff-Ténèbres.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_ténèbres'),
												
						'Debuff-Terre'	=> array (	'path' => 'Debuff-Terre.png',
												'duration' => 3000,
												'width' => 192,
												'height' => 192,
												'frames' => 30,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_terre'),
												
						'Debuff-Vent'	=> array (	'path' => 'Debuff-Vent.png',
												'duration' => 3500,
												'width' => 192,
												'height' => 192,
												'frames' => 35,
												'delay' => 100,
												'priority' => 1,
												'sound'	=> 'Debuff_vent'),
);

$_BATTLE_ACTIONS_ANIMS = array( 'attack'	=> $_BATTLE_ANIMS['attack'],
								'item'		=> $_BATTLE_ANIMS['item']);

$_SKILLS_ANIMS = array(
	SKILL_TYPE_POWER	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Atk-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Atk-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Atk-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Atk-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Atk-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Atk-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Atk-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Atk-Vent']),
	
	SKILL_TYPE_SHIELD	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Def-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Def-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Def-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Def-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Def-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Def-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Def-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Def-Vent']),
									
	SKILL_TYPE_ARCANA	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Atk-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Atk-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Atk-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Atk-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Atk-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Atk-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Atk-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Atk-Vent']),
	
	SKILL_TYPE_BARRIER	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Def-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Def-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Def-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Def-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Def-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Def-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Def-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Def-Vent']),
									
	SKILL_TYPE_PARALYZE	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_COUNTER	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Def-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Def-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Def-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Def-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Def-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Def-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Def-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Def-Vent']),
									
	SKILL_TYPE_CURSE	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['DOT-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['DOT-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['DOT-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['DOT-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['DOT-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['DOT-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['DOT-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['DOT-Vent']),
									
	SKILL_TYPE_DOUBLESTRIKE	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_ABSORB	=> array(	ELEMENT_NONE	=> $_BATTLE_ANIMS['Heal']),
	
	SKILL_TYPE_REGEN	=> array(	ELEMENT_NONE	=> $_BATTLE_ANIMS['Heal']),
	
	SKILL_TYPE_CANCEL	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_DISPEL	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_ILLUSION	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_LIFEDRAIN	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_MAGICDRAIN	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_WRATH	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_PROTECTION	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_GODSPEED	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_FOCUS	=> array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_SHIELDING => array(	ELEMENT_WATER	=> $_BATTLE_ANIMS['Buff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Buff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Buff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Buff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Buff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Buff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Buff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Buff-Vent']),
									
	SKILL_TYPE_INTIMIDATION	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_ARMORBREAK	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_STUN		=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_MAGICSEAL	=> array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_FRAGILITY => array( 	ELEMENT_WATER	=> $_BATTLE_ANIMS['Debuff-Eau'],
									ELEMENT_THUNDER => $_BATTLE_ANIMS['Debuff-Elec'],
									ELEMENT_FIRE	=> $_BATTLE_ANIMS['Debuff-Feu'],
									ELEMENT_ICE		=> $_BATTLE_ANIMS['Debuff-Glace'],
									ELEMENT_LIGHT	=> $_BATTLE_ANIMS['Debuff-Lumière'],
									ELEMENT_DARKNESS => $_BATTLE_ANIMS['Debuff-Ténèbres'],
									ELEMENT_EARTH	=> $_BATTLE_ANIMS['Debuff-Terre'],
									ELEMENT_WIND	=> $_BATTLE_ANIMS['Debuff-Vent']),
									
	SKILL_TYPE_HEAL		=> array(	ELEMENT_NONE	=> $_BATTLE_ANIMS['Heal']),
	
	SKILL_TYPE_ENERGY_BLOOD		=> array(	ELEMENT_NONE	=> $_BATTLE_ANIMS['Heal']),
);

//fusion
$_FORBIDDEN_FUSIONS = array(
	SKILL_TYPE_POWER		=> array(	SKILL_TYPE_ARCANA, SKILL_TYPE_DOUBLESTRIKE	),
	SKILL_TYPE_ARCANA		=> array(	SKILL_TYPE_POWER, SKILL_TYPE_DOUBLESTRIKE	),
	SKILL_TYPE_DOUBLESTRIKE	=> array(	SKILL_TYPE_ARCANA, SKILL_TYPE_POWER 		),
);

//orb
define("ORB_TYPE_ATK", 0);
define("ORB_TYPE_DEF", 1);
define("ORB_TYPE_SPD", 2);
define("ORB_TYPE_FLX", 3);
define("ORB_TYPE_PV", 4);
define("ORB_TYPE_PF", 5);
	
$_ORB_MULTIPLIERS = array(1 => 0.05, 2 => 0.1, 3 => 0.15);

//--- orbs effects
define("ORB_EFFECT_NO_CRITICAL", 'no_critical');
define("ORB_EFFECT_REBIRTH", 'rebirth');
define("ORB_EFFECT_KILL", 'kill');
define("ORB_EFFECT_NO_ORBS", 'no_orbs');
define("ORB_EFFECT_BERSERK", 'berserk');
define("ORB_EFFECT_ATTACK_PLUS", 'attack+');
define("ORB_EFFECT_DEFENSE_PLUS", 'defense+');
define("ORB_EFFECT_SPEED_PLUS", 'speed+');
define("ORB_EFFECT_FLUX_PLUS", 'flux+');
define("ORB_EFFECT_RESISTANCE_PLUS", 'resistance+');

$_ORB_EFFECTS_AVAILABILITY = array(
	ORB_EFFECT_NO_CRITICAL	=> 'before',
	ORB_EFFECT_REBIRTH		=> 'after',
	ORB_EFFECT_KILL			=> 'after',
	ORB_EFFECT_NO_ORBS		=> 'before',
	ORB_EFFECT_BERSERK		=> 'before',
	ORB_EFFECT_ATTACK_PLUS	=> 'before',
	ORB_EFFECT_DEFENSE_PLUS	=> 'before',
	ORB_EFFECT_SPEED_PLUS	=> 'before',
	ORB_EFFECT_FLUX_PLUS	=> 'before',
	ORB_EFFECT_RESISTANCE_PLUS	=>	'before',
);

define("ORB_TRIGGER_BATTLE_START", 'battle_start');
define("ORB_TRIGGER_PV_0", 'pv_0');
define("ORB_TRIGGER_PV_QUARTER", 'pv_quarter');
define("ORB_TRIGGER_OPPONENT_PV_3PER", 'opponent_pv_3');

/*$_ORB_TRIGGERS_AVAILABILITY = array(
	ORB_TRIGGER_BATTLE_START 		=> 'before',
	ORB_TRIGGER_PV_0				=> 'after',
	ORB_TRIGGER_PV_QUARTER			=> 'before',
	ORB_TRIGGER_OPPONENT_PV_3PER	=> 'after',
);*/

//special
define("SPECIAL_EFFECT_UPGRADE_WEAPON", 'upgrade_weapon');
define("SPECIAL_EFFECT_RESET_POINTS", 'reset_points');
define("SPECIAL_EFFECT_RESET_SKILLS", 'reset_skills');
define("SPECIAL_EFFECT_UP_ENERGY", 'up_energy');

// inventory
define("INVENTORY_SIZE", 16);

//weapon
define("WEAPON_GRADE_D", "D"); 	// D -> 10
define("WEAPON_GRADE_C", "C"); 	// C -> 20
define("WEAPON_GRADE_B", "B");	// B -> 30
define("WEAPON_GRADE_A", "A");	// A -> 40
define("WEAPON_GRADE_S", "S");	// S -> 50
define("WEAPON_GRADE_SS", "SS"); // SS -> 60
define("WEAPON_GRADE_X", "X"); //X -> 80

//armors
define("ARMOR_CLOTH", "clothes");
define("ARMOR_LEGGINGS", "leggings");
define("ARMOR_GLOVES", "gloves");
define("ARMOR_SHOES", "shoes");
	
$_WEAPON_ATTACKS = array(WEAPON_GRADE_D => 10
				, WEAPON_GRADE_C => 20
				, WEAPON_GRADE_B => 30
				, WEAPON_GRADE_A => 40
				, WEAPON_GRADE_S => 50
				, WEAPON_GRADE_SS => 60
				, WEAPON_GRADE_X => 80);
				
$_WEAPON_ACCURACIES = array(WEAPON_GRADE_D => 0
				, WEAPON_GRADE_C => 5
				, WEAPON_GRADE_B => 5
				, WEAPON_GRADE_A => 10
				, WEAPON_GRADE_S => 10
				, WEAPON_GRADE_SS => 15
				, WEAPON_GRADE_X => 20);
				
$_WEAPON_CRITICALS = array(WEAPON_GRADE_D => 0
				, WEAPON_GRADE_C => 0
				, WEAPON_GRADE_B => 5
				, WEAPON_GRADE_A => 10
				, WEAPON_GRADE_S => 15
				, WEAPON_GRADE_SS => 20
				, WEAPON_GRADE_X => 20);

				
// quests

define("QUEST_FORUM_ID", 9);
define("QUEST_TOPIC_ID", 121);
define("QUEST_POST_ID", 640);

define("QUEST_TYPE_BATTLE", "quest_battle"); // kill a mob to valid quest
define("QUEST_TYPE_SURVIVAL", "quest_survival"); // survive several mobs to valid quest
define("QUEST_TYPE_RIDDLE", "quest_riddle"); // find riddle's answer to valid quest

// warehouse

define("WAREHOUSE_SIZE", 50);
define("CALL_RATE", 0.005);

// wars

define("WAR_DEFAULT_POINTS", 20);

// organisations

define("ORGA_EMPIRE", 1);
define("ORGA_REVO", 2);
define("ORGA_ECLYPSE", 3);
define("ORGA_CONSEIL", 4);
define("ORGA_CITOYEN", 5);
define("ORGA_DIVINE", 6);


?>