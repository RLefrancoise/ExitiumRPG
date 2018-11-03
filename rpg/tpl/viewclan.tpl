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

		<title>Exitium - Clan</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewclan{SD_CSS}.css" />
		
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
		
		var clan_mode = "";
		
		function set_iframe_mode(mode){
			hide_message();
			hide_main_menu();
			
			if(mode == "create"){
				document.getElementById('iframe').src = './viewcreateclan.php?sid=' + SID;
				document.getElementById('iframe').style.visibility = 'visible';
				show_message('AccueilCreate.png');
			}
			if(mode == "search"){
				document.getElementById('iframe').src = './viewsearchclan.php?sid=' + SID;
				document.getElementById('iframe').style.visibility = 'visible';
				show_message('AccueilSearch.png');
			}
			
			clan_mode = mode;
			
			refresh_iframe_position();
		}
		
		function refresh_iframe_position() {
		
			var larg = 0;
			var haut = 0;
			if (document.body)
			{
				larg = (document.body.clientWidth);
				haut = (document.body.clientHeight);
			}
			else
			{
				larg = (window.innerWidth);
				haut = (window.innerHeight);
			}

			if(clan_mode == "create") {
				larg = larg - 768 - (larg * 0.01); //margin-right : 1% 
				if(larg < 0) larg = 0;
				larg = larg.toString();
				document.getElementById('iframe').style.marginLeft = larg + 'px';
			}
			if(clan_mode == "search") {
				larg = larg - 881 - (larg * 0.01); //margin-right : 1% 
				if(larg < 0) larg = 0;
				larg = larg.toString();
				document.getElementById('iframe').style.marginLeft = larg + 'px';
			}
		}
		
		function hide_iframe() {
			document.getElementById('iframe').style.visibility = "hidden";
			document.getElementById('iframe').style.backgroundImage = "none";
		}
		
		function hide_message() {
			document.getElementById('message').style.visibility = "hidden";
			document.getElementById('message_pic').src = '';
		}
		
		function hide_main_menu() {
			document.getElementById('main_menu').style.visibility = "hidden";
		}
		
		function show_iframe() {
			document.getElementById('iframe').style.visibility = "visible";
		}
		
		function show_message(image) {
			document.getElementById('message').style.visibility = "visible";
			document.getElementById('message_pic').src = './images/rpg/clans/messages/' + image;
		}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>

		<!-- BEGIN background_music -->
		<object type="application/x-shockwave-flash" data="./dewplayer/dewplayer.swf?mp3=./rpg/sound/mp3/Clans.mp3&amp;autostart=1&amp;autoreplay=true" width="0" height="0"></object>
		<!-- END background_music -->
		
		<!-- Main Menu -->
		<table id="main_menu">
			<tbody>
				<tr>
					<td colspan="2" align="center" valign="center">
						<img id="title" alt="" src="images/rpg/clans/accueil/{SD_DIR}QuePuisje.{SD_EXT}"/>
					</td>
				</tr>
				<tr>
					<td align="center" valign="center">
						<!-- BEGIN create_button_enabled_bloc -->
						<a href="javascript:set_iframe_mode('create')" id="create_button_enabled"></a>
						<!-- END create_button_enabled_bloc -->
						<!-- BEGIN create_button_disabled_bloc -->
						<span id="create_button_disabled"></span>
						<!-- END create_button_disabled_bloc -->
					</td>
					<td align="center" valign="center"><a href="javascript:set_iframe_mode('search')" id="search_button"></a></td>
				</tr>
				<tr>
					<td colspan="2" align="center" valign="center">
						<!-- BEGIN see_button_enabled_bloc -->
						<a href="viewclanpage.php?id={see_button_enabled_bloc.CLAN_ID}" id="see_button_enabled"></a>
						<!-- END see_button_enabled_bloc -->
						<!-- BEGIN see_button_disabled_bloc -->
						<span id="see_button_disabled"></span>
						<!-- END see_button_disabled_bloc -->
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" valign="center"><a href="{BACK_LINK}" id="exit_button"></a></td>
				</tr>
			</tbody>
		</table>
		
		<iframe id="iframe"></iframe>
		
		
		
		<!-- pour les messages -->
		<div id="message"><img id="message_pic" alt="" src=""/></div>
		
		
		
	</body>
</html>