<?php

class RPGConfig {

	public static $_STARTING_ITEMS = array(
		0	=>	array( 	'type'		=> 'syringe',
						'id'		=> 1,
						'number'	=> 10),
		1	=>	array(  'type'		=> 'syringe',
						'id'		=> 4,
						'number'	=> 10),
	);
		
	public static $_CLAN_PI_RALZ = array(
		1	=>	200000,
		2	=>	400000,
		3	=>	800000,
		4	=>	1600000,
		5	=>	3200000,
		6	=>	6400000,
	);

	public static $_CLAN_STAT_BONUS = array(
		1	=>	array(
					STAT_ATTACK		=>	1,
					STAT_DEFENSE	=>	1,
					STAT_SPEED		=>	1,
					STAT_FLUX		=>	1,
					STAT_RESISTANCE	=>	1,
					STAT_PV			=>	10,
					STAT_PF			=>	10,
				),
		2	=>	array(
					STAT_ATTACK		=>	2,
					STAT_DEFENSE	=>	2,
					STAT_SPEED		=>	2,
					STAT_FLUX		=>	2,
					STAT_RESISTANCE	=>	2,
					STAT_PV			=>	20,
					STAT_PF			=>	20,
				),
		3	=>	array(
					STAT_ATTACK		=>	3,
					STAT_DEFENSE	=>	3,
					STAT_SPEED		=>	3,
					STAT_FLUX		=>	3,
					STAT_RESISTANCE	=>	3,
					STAT_PV			=>	30,
					STAT_PF			=>	30,
				),
		4	=>	array(
					STAT_ATTACK		=>	4,
					STAT_DEFENSE	=>	4,
					STAT_SPEED		=>	4,
					STAT_FLUX		=>	4,
					STAT_RESISTANCE	=>	4,
					STAT_PV			=>	40,
					STAT_PF			=>	40,
				),
		5	=>	array(
					STAT_ATTACK		=>	5,
					STAT_DEFENSE	=>	5,
					STAT_SPEED		=>	5,
					STAT_FLUX		=>	5,
					STAT_RESISTANCE	=>	5,
					STAT_PV			=>	50,
					STAT_PF			=>	50,
				),
		6	=>	array(
					STAT_ATTACK		=>	6,
					STAT_DEFENSE	=>	6,
					STAT_SPEED		=>	6,
					STAT_FLUX		=>	6,
					STAT_RESISTANCE	=>	6,
					STAT_PV			=>	60,
					STAT_PF			=>	60,
				),
	);
		
	private function __construct() { }
}

?>