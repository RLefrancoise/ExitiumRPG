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

		<title>Exitium - Auberge</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewinn{SD_CSS}.css" />
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
			var can_act = true;
			
			function inn(mode) {
				if(!can_act) return false;
				
				if(mode != 'sleep' && mode != 'rest') return;
				
				can_act = false;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						can_act = true;
						
						if( (xhr.status == 200 || xhr.status == 0) ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "no_heal")) {
								alert('Vous avez déjà tous vos PV et PF. Repassez nous voir plus tard !');
							} else if(string_starts_with(xhr.responseText, "rest_ok")) {
								alert('Vous avez récupéré 50% de vos PV et PF.\nRevenez quand vous voulez !');
							} else if(string_starts_with(xhr.responseText, "sleep_ok")) {
								alert('Vous avez récupéré tous vos PV et PF.\nRevenez quand vous voulez !');
							} else if(string_starts_with(xhr.responseText, "no_money")) {
								alert('Vous n\'avez pas assez d\'argent !');
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
							
						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}
					
				};
				
				xhr.open("GET", "inn.php?sid=" + SID + "&mode=" + mode, true);
				xhr.send(null);
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<!-- BEGIN background_music -->
		<object type="application/x-shockwave-flash" data="./dewplayer/dewplayer.swf?mp3=./rpg/sound/mp3/Inn.mp3&amp;autostart=1&amp;autoreplay=true" width="0" height="0"></object>
		<!-- END background_music -->
		
		<table id="inn">
			<tr>
				<td>
					<table id="main_menu">
						<tr>
							<td align="center" valign="center">
								<img id="welcome" alt="" src="images/rpg/inn/{SD_DIR}welcome.{SD_EXT}"/>
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<a id="rest_link" href="javascript:inn('rest')"><p>Je veux juste me reposer</p><p><span style="color:rgb(0,255,64)">Récupère 50% de vos PV/PF</span></p><p>Prix : {REST_PRICE} Ralz</p></a> 
							</td>
						</tr>
						<tr>
							<td align="center" valign="top">
								<a id="sleep_link" style="margin-top:-40px" href="javascript:inn('sleep')"><p>Je veux passer ma nuit ici</p><p><span style="color:rgb(0,255,64)">Récupère tous vos PV/PF</span></p><p>Prix : {SLEEP_PRICE} Ralz</p></a> 
							</td>
						</tr>
					</table>
				</td>
			</tr>
			
			<tr>
				<td align="center" valign="center">
					<a id="exit_link" href="{BACK_LINK}"></a> 
				</td>
			</tr>
		</table>
	</body>
</html>