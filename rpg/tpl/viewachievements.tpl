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

		<title>Exitium - Succès</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewachievements{SD_CSS}.css" />
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<!-- BEGIN background_music -->
		<object type="application/x-shockwave-flash" data="./dewplayer/dewplayer.swf?mp3=./rpg/sound/mp3/Inn.mp3&amp;autostart=1&amp;autoreplay=true" width="0" height="0"></object>
		<!-- END background_music -->
		
		<p><a id="exit_link" href="{BACK_LINK}">Retour</a></p>
		
		<!-- BEGIN category_bloc -->
		<div style="width:850px;margin:auto;border:1px solid black;border-radius:20px;background-color:rgb(220,220,220);margin-bottom:5px;">
			<div class="category">{category_bloc.CATEGORY_NAME}</div>
		
			<div class="achievements_block">
				<!-- BEGIN achievement_bloc -->
				<div class="achievement_block">
					<span style="display:inline-block;float:left;width:300px;margin-left:2px;">{category_bloc.achievement_bloc.NAME}</span>
					<span style="display:inline-block;width:400px;">{category_bloc.achievement_bloc.CONDITION}</span>
					<!-- BEGIN locked_bloc -->
					<span style="display:inline-block;float:right;color:red;margin-right:2px;">Non obtenu</span>
					<!-- END locked_bloc -->
					<!-- BEGIN unlocked_bloc -->
					<span style="display:inline-block;float:right;color:green;margin-right:2px;">Obtenu</span>
					<!-- END unlocked_bloc -->
				</div>
				<!-- END achievement_bloc -->
			</div>
		</div>
		<!-- END category_bloc -->
	</body>
</html>