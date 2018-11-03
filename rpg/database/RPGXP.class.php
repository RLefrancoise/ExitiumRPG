<?php
	include_once(__DIR__ . '/../../common.php');
	
	include_once(__DIR__ . "/Database.class.php");
	
	class RPGXP {
		private static $theInst;

		private function __construct() {
		}
		
		public static function getXPByLvl($level){
			global $db;
			
			$sql = 'SELECT DISTINCT xp 
					FROM rpg_xp
					WHERE level = ' . (int) $db->sql_escape($level);
			$result = $db->sql_query($sql);
			$info = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			/*$bdd = &Database::getBDD();
			$req = $bdd->prepare('SELECT xp FROM rpg_xp WHERE level = ?');
			$req->execute(array($level));
			
			$info = $req->fetch();
			$req->closeCursor();*/
			
			return (int) $info['xp'];
		}
	}
?>