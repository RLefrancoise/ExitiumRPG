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
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewrpgmenu.css" />
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/imageutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
			var can_update = true;
			
			function start_update() {
				setInterval(function() { update(); }, 5000);
			}
			
			function go(url) {
				
				if(SID != undefined && SID != '')
					parent.location.href = url + '?sid=' + SID;
				else
					parent.location.href = url;
			}
			
			function update() {
				if(!can_update) return;
				
				can_update = false;
				
				//create ajax object to request menu according to item
				var xhr = getXMLHttpRequest();

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if(xhr.status == 200 || xhr.status == 0) {
							//response from server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								//nothing
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else {
								var j = xhr.responseText.split('<!--');
								var json = JSON.parse(j[0]);
								
								//pv
								clip_image(document.getElementById('hp_img'), 0, 202 * (json['hp'] / json['maxhp']), 10, 0);
								document.getElementById('hp').innerHTML = 'PV - ' + json['hp'] + '/' + json['maxhp'];
								//pf
								clip_image(document.getElementById('fp_img'), 0, 202 * (json['fp'] / json['maxfp']), 10, 0);
								document.getElementById('fp').innerHTML = 'PF - ' + json['fp'] + '/' + json['maxfp'];
								//xp
								clip_image(document.getElementById('xp_img'), 0, 202 * (json['xp'] / json['maxxp']), 10, 0);
								document.getElementById('xp').innerHTML = 'XP - ' + json['xp'] + '/' + json['maxxp'];
								//energy
								document.getElementById('energy').innerHTML = 'Energie : ' + json['energy'] + '/' + json['maxenergy'];
								//honor
								document.getElementById('honor').innerHTML = 'Honneur : ' + json['honor'];
								//ralz
								document.getElementById('ralz').innerHTML = 'Ralz : ' + json['ralz'];
							}

						} /*else {
							alert('Une erreur est survenue lors du traitement de la requête : ' + xhr.responseText);
						}*/
						
						can_update = true;
					}
				};

				xhr.open("GET", "viewrpgmenu.php?sid=" + SID + "&mode=update", true);
				xhr.send(null);
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');start_update()">
		<div style="font-family:Calibri, Arial;color:white;text-align:center;font-size:12px;height:227px">
			<p style="font-size:20px;margin-top:35px"><strong>Statut</strong></p>
			
			<div style="height:170px;margin:auto;">
				<div class="bars_multilayer">
					<img class="layer1" alt="" src="images/rpg/status/barres/empty.png"/>
					<img id="hp_img" onload="clip_image(this,0,202 * ({USER_HP}/{USER_MAX_HP}),10,0);" class="layer2" alt="" src="images/rpg/status/barres/PV.png"/>
				</div>
				<p id="hp" class="info">PV - {USER_HP}/{USER_MAX_HP}<p>
				
				<div class="bars_multilayer">
					<img class="layer1" alt="" src="images/rpg/status/barres/empty.png"/>
					<img id="fp_img" onload="clip_image(this,0,202 * ({USER_FP}/{USER_MAX_FP}),10,0);" class="layer2" alt="" src="images/rpg/status/barres/PF.png"/>
				</div>
				<p id="fp" class="info">PF - {USER_FP}/{USER_MAX_FP}<p>
				
				<div class="bars_multilayer">
					<img class="layer1" alt="" src="images/rpg/status/barres/empty2.png"/>
					<img id="xp_img" onload="clip_image(this,0,202 * ({USER_XP}/{USER_MAX_XP}),10,0);" class="layer2" alt="" src="images/rpg/status/barres/XP.png"/>
				</div>
				<p id="xp" class="info">XP - {USER_XP}/{USER_MAX_XP}<p>
				
				<div style="font-size:14px;margin:auto;height:40px;">
					<span><span id="energy">Energie : {USER_ENERGY}/{USER_MAX_ENERGY}</span><span style="display:inline-block;width:25px"></span><span id="honor">Honneur : {USER_HONOR}</span></span><br>
					<!--div style="display:inline-block;text-align:center;">
						<p id="energy" class="info" style="float:left;margin-right:10px">Energie : {USER_ENERGY}/{USER_MAX_ENERGY}</p>
						<p id="honor" class="info" style="float:right;margin-left:10px">Honneur : {USER_HONOR}</p>
					</div-->
					<p id="ralz" class="info">Ralz : {USER_RALZ}</p>
				</div>
				
			</div>
		</div>
		
		<div style="color:white;text-align:center;margin-left:5px;">
			<table width="240" height="240">
				<tr>
					<td><a href="javascript:go('./viewstatus.php')" title="Menu de statut"><img src="images/rpg/profil.png"/></a></td>
					<td><a href="javascript:go('./viewmap.php')" title="Carte du monde"><img src="images/rpg/map.png"/></a></td>
				</tr>
				<tr>
					
					<td><a href="javascript:go('./viewinn.php')" title="Auberge"><img src="images/rpg/auberge.png"/></a></td>
					<td><a href="javascript:go('./viewclan.php')" title="Menu des clans"><img src="images/rpg/clan.png"/></a></td>
				</tr>
				<tr>
					<td><a href="javascript:go('./viewblackmarket.php')" title="Marché noir"><img src="images/rpg/black.png"/></a></td>
					<td><a href="javascript:go('./viewwarehouse.php')" title="Banque"><img src="images/rpg/icons/Ralz.png"/></a></td>
				</tr>
				<tr>
					<td><a href="javascript:go('./viewachievements.php')" title="Succès"><img src="images/rpg/succes.png"/></a></td>
					<td><a href="javascript:go('./viewmonsterbook.php')" title="Bestiaire"><img src="images/rpg/monsterbook.png"/></a></td>
				</tr>
			</table>
		</div>
		
		<div style="text-align:center;margin-top:10px">
			<a href="javascript:go('./index.php')"><img src="images/rpg/menu/forum.png"/></a>
		</div>
	</body>
</html>