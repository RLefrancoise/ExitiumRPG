<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
include_once('./template/template.php');
include_once('./rpg/classes/rpgconfig.php');
include_once('./rpg/php/upload_functions.php');
include_once('./rpg/database/RPGClans.class.php');
include_once('./rpg/database/RPGUsersPlayers.class.php');

// Start session management
$user->session_begin();
$auth->acl($user->data);

$error = 'unknown';

// user is connected ?
if($user->data['username'] == "Anonymous") {
	$error = "not_connected";
} else {
	$player = RPGUsersPlayers::getPlayerByUserId($user->data['user_id']);
	if($player->isInBattle()) {
		echo "<p>Cette page n'est pas accessible car vous êtes en combat.</p>";
		die();
	}
	// player exists ?
	if(!$player) {
		$error = 'error';
	// player has already a clan ?
	} else if($player->getClan() !== null) {
		$error = 'already_has_clan';
	// player has enough money ?
	} else if($player->getRalz() < CLAN_CREATE_PRICE) {
		$error = 'no_money';
	// is there a clan name ?
	} else if(!isset($_POST['clan_name'])) {
		$error = 'no_clan_name';
	// is there a clan text ?
	} else if(!isset($_POST['clan_text'])) {
		$error = 'no_clan_text';
	} else {
		//get clan info
		$clan_name = htmlspecialchars($_POST['clan_name']);
		if(RPGClans::nameExists($clan_name)) {
			$error = 'name_already_used';
		}
		else {
			$clan_text = htmlspecialchars($_POST['clan_text']);
			
			//try to upload the clan image
			$image_name = '';
			$upload_state = upload_image('clan_image', __DIR__ . '/images/rpg/clans/see/clan_images/', MAX_CLAN_IMAGE_WIDTH, MAX_CLAN_IMAGE_HEIGHT, MAX_CLAN_IMAGE_SIZE, $image_name);
			switch($upload_state) {
				case 'upload_ok':
					$db->sql_transaction('begin');
					if(RPGClans::createClan($clan_name, $clan_text, $image_name, $user->data['user_id']) and RPGPlayers::setRalzByPlayer($player, $player->getRalz() - CLAN_CREATE_PRICE)) {
						$error = 'create_ok';
					} else {
						$error = 'bdd_error';
					}
					$db->sql_transaction('commit');
					break;
				case 'upload_error':
					$error = 'upload_error';
					break;
				case 'internal_error':
					$error = 'internal_error';
					break;
				case 'dimension_error':
					$error = 'dimension_error';
					break;
				case 'no_image':
					$error = 'no_image';
					break;
				case 'wrong_extension':
					$error = 'wrong_extension';
					break;
				case 'no_post':
					$error = 'no_post';
					break;
				default:
					break;
			}

		}
	}
}

?>

<script type="text/javascript">
<!--
        parent.create_end("<?php echo $error; ?>");
//-->
</script>
