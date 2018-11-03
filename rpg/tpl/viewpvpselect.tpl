<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="fr" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta http-equiv="imagetoolbar" content="no" />
		<meta name="resource-type" content="document" />
		<meta name="distribution" content="global" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />

		<title>Exitium - Sélection d'un adversaire</title>
		
		<!--link rel="stylesheet" type="text/css" href="rpg/css/viewplayer.css" /-->
		
		
	</head>

	<body onload="javascript:set_sid('{SID}');">
		<form name="pvp_request_form" action="startbattle.php?sid={SID}&amp;mode=pvp_request&amp;f={FORUM_ID}&amp;t={TOPIC_ID}" method="post">
			<div>Choisir un adversaire : 
				<select name="opponent_id">
					<!-- BEGIN opponent_bloc -->
					<option value="{opponent_bloc.ID}">{opponent_bloc.NAME}</option>
					<!-- END opponent_bloc -->
				</select>
			</div>
			
			<input type="submit" value="Submit">
		</form>
	</body>
</html>