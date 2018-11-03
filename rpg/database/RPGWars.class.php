<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/../classes/War.class.php");
	
	class RPGWars {
	
		private function __construct() {
		}
		
		public static function getWar() {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_wars';
					
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			if(!$info) return null;
			
			$w = new War($info);
			return $w;
		}
		
		public static function createWar($data) {
			global $db;
			
			$sql = 'INSERT INTO rpg_wars ' . $db->sql_build_array('INSERT', $data);
			$db->sql_query($sql);

			$request_success = ($db->sql_affectedrows() > 0);
			if(!$request_success) return false;
			
			$id = $db->sql_nextid();
			
			return $id;
		}
		
		public static function deleteWar(War &$war) {
			global $db;
			
			$sql = 'DELETE FROM rpg_wars
					WHERE id = ' . $war->getId();
			$db->sql_query($sql);
			
			$success = ($db->sql_affectedrows() > 0);
			
			return $success;
		}
		
		public static function isWarRunning() {
			global $db;
			
			$war = RPGWars::getWar();
			if(!$war) return false;
			if($war->isOver()) return false;
			
			return true;
		}
		
		public static function setPoints(War &$war, $orga, $points) {
			global $db;
			
			$field = '';
			
			switch($orga) {
				case ORGA_EMPIRE:
					$field = 'empire_points';
					break;
				case ORGA_REVO:
					$field = 'revo_points';
					break;
				case ORGA_ECLYPSE:
					$field = 'eclypse_points';
					break;
				case ORGA_CONSEIL:
					$field = 'conseil_points';
					break;
			}
			
			if($field == '') return false;
			
			$data = array(
				$field = (int) $points,
			);
			
			$sql = 'UPDATE rpg_wars
					SET ' . $db->sql_build_array('UPDATE', $data) . '
					WHERE id = ' . $war->getId();
			$db->sql_query($sql);
				
			$request_success = ($db->sql_affectedrows() > 0);
			
			if($request_success) $war->setPoints($orga, $points);
			
			return $request_success;
		} 
	}

?>