<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include_once($phpbb_root_path . 'common.' . $phpEx);

require_once("../rpg/database/RPGItemPacks.class.php");

$pack = RPGItemPacks::getPack(1);

print_r($pack);

?>