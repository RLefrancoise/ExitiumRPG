<?php

function purge_special_chars($string) {
	$string = preg_replace("/\\\\\\\\\\\\\\\\/", '\\', $string); // pour byethost qui écrit \\\\ au lieu de \ dans la BDD
	$string = str_replace("\\\\", '\\', $string); // changer \\ en \ lorsque l'on saisit \ dans la chatbox
	$string = str_replace("\'", "'", $string); // transformer \' en ' pour l'affichage
	$string = preg_replace("/\\\\+&quot;/", "", $string); // transformer \&quot; en " pour l'affichage
	
	return $string;
}

function generate_json_message($message) {
	$a = array("message" => $message);
	return json_encode($a);
}

function colorize_string($str, $r, $g, $b) {
	return "<span style=\"color:rgb({$r},{$g},{$b})\">" . $str. "</span>";
}

?>