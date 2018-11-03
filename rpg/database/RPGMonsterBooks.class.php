<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . '/../classes/MonsterBook.class.php');
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGMonsterBooks {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getMonsterBook($player_id) {
			global $db;
			
			$sql = 'SELECT *
					FROM rpg_monster_books
					WHERE player_id = ' . (int) $db->sql_escape($player_id);
			$result = $db->sql_query($sql);
			
			$data = array();
			
			while($info = $db->sql_fetchrow($result)) {
				if(!array_key_exists($info['monster_id'], $data)) {
					$data[$info['monster_id']] = array();
				}
				
				$data[$info['monster_id']][$info['area_part_id']] = array(
					'encounters'	=>	$info['encounters'],
					'wins'			=>	$info['wins'],
					'loses'			=>	$info['loses'],
				);
			}
			
			$db->sql_freeresult($result);
			
			$mb = new MonsterBook($player_id, $data);
			return $mb;
		}
		
		public static function addEntry($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_monster_books ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function updateEntry($player_id, $monster_id, $part_id, $data) {
			global $db;
			
			$sql = 'UPDATE rpg_monster_books
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE player_id = ' . (int) $player_id . '
					AND monster_id = ' . (int) $monster_id . '
					AND area_part_id = ' . (int) $part_id;
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
		
		public static function deleteEntry($player_id, $monster_id, $part_id) {
			global $db;
			
			$sql = 'DELETE FROM rpg_monster_books
					WHERE player_id = ' . (int) $player_id . '
					AND monster_id = ' . (int) $monster_id . '
					AND area_part_id = ' . (int) $part_id;
			
			$db->sql_query($sql);
			
			$request_success = ($db->sql_affectedrows() > 0);
			
			return $request_success;
		}
	}
?>