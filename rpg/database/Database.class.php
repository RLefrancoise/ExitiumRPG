<?php
	class Database {

	  	private $bdd;
		private static $theInst;

		private function __construct() {
			try
			{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$this->bdd = new PDO('mysql:host=localhost;dbname=arkham', 'root', '', $pdo_options);
				$req = $this->bdd->prepare('SET CHARACTER SET `UTF8`'); //utf8 pour les caract�res japonais !
				$req->execute();
				$req->closeCursor();
			}
			catch (Exception $e)
			{
					die('Erreur : ' . $e->getMessage());
			}
		}

		/********************************/

		public static function getBDD() {

			if (!isset(Database::$theInst)) {
			
				//echo "<P> Creation obj Database</P>";			
				Database::$theInst = new Database();
			}

			return Database::$theInst->bdd;
		}

		/********************************/

  		
	}
?>
