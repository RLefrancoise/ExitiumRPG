<?php
	include_once(__DIR__ . '/../../common.php');
	
	class RPGRPForums {
		private static $theInst;

		private function __construct() {
		}
		
		public static function forumIsRP($forum_id) {
			global $db;
			
			$sql = 'SELECT DISTINCT *
					FROM rpg_rp_forums
					WHERE forum_id = ' . (int) $forum_id;
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			
			if($info) return true;
			else return false;
		}
	}
	
?>