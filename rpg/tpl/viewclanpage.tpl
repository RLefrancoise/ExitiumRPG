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

		<title>Exitium - {CLAN_NAME}</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewclanpage{SD_CSS}.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/tooltip.css" />
		
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
			var clan_id = -1;
			var refresh_timer = null;
			var clan_mode = '';
			
			function set_clan_id(id, mode) {
				clan_id = id;
				clan_mode = mode;
			}
			
			function scrollDiv(divId, depl) {
			   var scroll_container = document.getElementById(divId);
			   scroll_container.scrollTop -= depl;
			}
			
			function refresh_chatbox(callback) {
				if(clan_id == -1) return;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						callback(xhr.responseText);
					}
				};
				
				xhr.open("GET", "viewclanmessages.php?sid=" + SID + "&id=" + clan_id, true);
				xhr.send(null);
			}
			
			function refresh_response(response) {
				document.getElementById('messages_div').innerHTML = response;
				scrollDiv('messages_div', -9999); // aller en bas de la chat box
				
				if(refresh_timer != null) {
					clearTimeout(refresh_timer);
					refresh_timer = null;
				}
				
				refresh_timer = setTimeout('refresh_chatbox(refresh_response)', 10000); // rafraichir la chatbox toutes les 10 secondes
			}
			
			function write_message(callback) {
				
				if(clan_id == -1) return;
				if(document.getElementById("message_input").value == "") return;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						callback(xhr.responseText);
					}
				};
				
				var message = encodeURIComponent(document.getElementById("message_input").value);
				
				xhr.open("POST", "postclanmessage.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.send("sid=" + SID + "&clan=" + clan_id + "&m=" + message);
			}
			
			function write_response(response) {
				
				if(string_starts_with(response, "not_connected")) {
					alert('Vous n\'êtes pas connecté !');
				}
				else if(string_starts_with(response, "not_allowed")) {
					alert('Vous n\'êtes pas autorisé à poster des messages sur cette chatbox !');
				}
				else if(string_starts_with(response, "error")) {
					alert('Une erreur est survenue !');
				}
				else if(string_starts_with(response, "message_sent")) {
					document.getElementById("message_input").value = '';
					refresh_chatbox(refresh_response);
				}
				else {
					alert("Le serveur a retourné une valeur inconnue : " + response);
				}
			}
			
			function clan_button() {
			
				if(clan_mode != 'leader' && clan_mode != 'member' && clan_mode != 'newcommer')
					return;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						clan_button_response(xhr.responseText);
					} else if(xhr.readyState == 4 && xhr.status != 200 && xhr.status != 0) {
						alert("L'envoi de la requête a échoué : state=" + xhr.readyState + " status=" + xhr.status);
					}
				};
						
				/* check the mode */
				if(clan_mode == 'leader') {
				
					var validate = confirm('Voulez-vous vraiment supprimer le clan ?');
					if(validate == true) {
						xhr.open("GET", "clanmanagement.php?sid=" + SID + "&mode=delete&id=" + clan_id, true);
						xhr.send(null);
					}
					
				} else if(clan_mode == 'member') {
					var validate = confirm('Voulez-vous vraiment quitter le clan ?');
					if(validate == true) {
						xhr.open("GET", "clanmanagement.php?sid=" + SID + "&mode=quit&id=" + clan_id, true);
						xhr.send(null);
					}
				
				} else if(clan_mode == 'newcommer') {
					var validate = confirm('Voulez-vous vraiment rejoindre ce clan ?');
					if(validate == true) {
						xhr.open("GET", "clanmanagement.php?sid=" + SID + "&mode=join&id=" + clan_id, true);
						xhr.send(null);
					}
				}
			}
			
			function clan_button_response(response) {
				if(string_starts_with(response, "not_connected")) {
					alert('Vous n\'êtes pas connecté !');
					document.location.href = 'index.php';
				} else if(string_starts_with(response, "error")) {
					alert('Une erreur est survenue !');
				} else if(string_starts_with(response, "join_ok")) {
					alert('Votre demande a été envoyée au chef du clan. Vous recevrez une réponse par MP quand votre demande aura été traitée.');
				} else if(string_starts_with(response, "already_has_clan")) {
					alert('Vous êtes déjà dans un clan !');
				} else if(string_starts_with(response, "quit_ok")) {
					alert('Vous avez quitté le clan !');
					location.reload();
				} else if(string_starts_with(response, "is_not_member")) {
					alert('Vous essayez de quitter un clan dans lequel vous n\'êtes pas, ou dont vous êtes le chef.\nL\'opération est annulée.');
				} else if(string_starts_with(response, "delete_ok")) {
					alert('Le clan a bien été supprimé, vous allez être redirigé vers l\'acceuil des clans.');
					document.location.href = 'viewclan.php';
				} else if(string_starts_with(response, "is_not_leader")) {
					alert('Vous essayez de supprimer un clan dont vous n\'êtes pas le chef, l\'opération est annulée.');
				} else {
					alert('Le serveur a retourné une valeur inconnue. Erreur : ' + response);
				}
			}
			
			function open_leader_menu(member_id, event) {
				var inner = '<ul>\
								<li><a href="javascript:ban_member(' + member_id + ')">Exclure</a></li>\
								<li><a href="javascript:close_leader_menu()">Fermer le menu</a></li>\
							</ul>';
							
				moveElementAtMousePosition('menu', event, 0, 0);
				document.getElementById('menu').innerHTML = inner;
				document.getElementById('menu').style.visibility = 'visible';
			}
			
			function close_leader_menu() {
				document.getElementById('menu').style.visibility = 'hidden';
			}
			
			function ban_member(member_id) {
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
							document.location.href = 'index.php';
						} else if(string_starts_with(xhr.responseText, "ban_ok")) {
							alert('Ce membre a été exclu du clan.');
							document.location.reload();
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue !');
						} else {
							alert('Le serveur a retourné une valeur inconnue. Erreur : ' + response);
						}
					} else if(xhr.readyState == 4 && xhr.status != 200 && xhr.status != 0) {
						alert("L'envoi de la requête a échoué : state=" + xhr.readyState + " status=" + xhr.status);
					}
				};
				
				if(clan_mode == 'leader') {
				
					var validate = confirm('Voulez-vous vraiment exclure ce membre ?');
					if(validate == true) {
						xhr.open("GET", "clanmanagement.php?sid=" + SID + "&mode=ban_member&id=" + clan_id + '&m=' + member_id, true);
						xhr.send(null);
					}
					
				} 
			}
			
			function center_display() {
			
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				document.getElementById('main_table').style.margin = "auto";
				
				if(larg > 1340) {
					document.getElementById('main_table').style.left = "50%";
					document.getElementById('main_table').style.marginLeft = "-670px";
				}
				else {
					document.getElementById('main_table').style.left = "0";
				}
				
				if(haut > 870) {
					document.getElementById('main_table').style.top = "50%";
					document.getElementById('main_table').style.marginTop = "-435px";
				}
				else {
					document.getElementById('main_table').style.top = "5px";
				}
			}
			
			function load_pi_menu() {
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
							document.location.href = 'index.php';
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue !');
						} else if(string_starts_with(xhr.responseText, "<")) {
							document.getElementById('pi_block').innerHTML = xhr.responseText;
						} else {
							alert('Le serveur a retourné une valeur inconnue. Erreur : ' + response);
						}
					} else if(xhr.readyState == 4 && xhr.status != 200 && xhr.status != 0) {
						alert("L'envoi de la requête a échoué : state=" + xhr.readyState + " status=" + xhr.status);
					}
				};
				
				if(clan_mode == 'leader') {
					xhr.open("GET", "clanmanagement.php?sid=" + SID + "&mode=pi_menu&id=" + clan_id, true);
					xhr.send(null);
				} 
			}
			
			function open_pi_menu() {
				close_leader_menu();
				document.getElementById('pi_block').style.display = 'block';
			}
			
			function close_pi_menu() {
				document.getElementById('pi_block').style.display = 'none';
			}
			
			function use_pi(type) {
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
							document.location.href = 'index.php';
						} else if(string_starts_with(xhr.responseText, "pi_ok")) {
							alert('Le buff a été acheté.');
							load_pi_menu();
						} else if(string_starts_with(xhr.responseText, "no_ralz")) {
							alert('PI insuffisant.');
						} else if(string_starts_with(xhr.responseText, "buff_max")) {
							alert('Ce buff ne peut plus être amélioré.');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue !');
						} else {
							alert('Le serveur a retourné une valeur inconnue. Erreur : ' + response);
						}
					} else if(xhr.readyState == 4 && xhr.status != 200 && xhr.status != 0) {
						alert("L'envoi de la requête a échoué : state=" + xhr.readyState + " status=" + xhr.status);
					}
				};
				
				if(clan_mode == 'leader') {
				
					var validate = confirm('Voulez-vous vraiment acheter ce buff ?');
					if(validate == true) {
						xhr.open("GET", "clanmanagement.php?sid=" + SID + "&mode=use_pi&id=" + clan_id + '&type=' + type, true);
						xhr.send(null);
					}
					
				} 
			}
			
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:set_clan_id({CLAN_ID},'{CLAN_MODE}');javascript:center_display();javascript:refresh_chatbox(refresh_response);javascript:load_pi_menu();" onresize="javascript:center_display()">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<div id="tooltip"></div>
		<div id="menu"></div>
		
		<!-- BEGIN pi_menu -->
		<div id="pi_block">
			<!--a id="pi_close" href="javascript:close_pi_menu()"><img src="images/rpg/clans/see/pi/close.png" /></a>
			<p style="color:white;font-size:20px;font-weight:bold;padding:0;margin-left:25px;margin-top:20px;">Solde : {pi_menu.CLAN_PI} PI</p>
			<table width="695" height="590" style="margin-top:10px;margin:auto;text-align:center;">
				<tr>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.ATK_LEVEL}</span><br>
						<a href="javascript:use_pi('atk')"><img src="images/rpg/clans/see/pi/atk.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff Attaque</strong><br><br>Augmente l'attaque de {pi_menu.ATK_BONUS}." /></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.ATK_PI} PI</span>
					</td>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.DEF_LEVEL}</span><br>
						<a href="javascript:use_pi('def')"><img src="images/rpg/clans/see/pi/def.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff défense</strong><br><br>Augmente la défense de {pi_menu.DEF_BONUS}."/></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.DEF_PI} PI</span>
					</td>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.SPD_LEVEL}</span><br>
						<a href="javascript:use_pi('spd')"><img src="images/rpg/clans/see/pi/spd.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff Vitesse</strong><br><br>Augmente la vitesse de {pi_menu.SPD_BONUS}." /></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.SPD_PI} PI</span>
					</td>
				</tr>
				<tr>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.FLUX_LEVEL}</span><br>
						<a href="javascript:use_pi('flux')"><img src="images/rpg/clans/see/pi/flux.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff Flux</strong><br><br>Augmente le flux de {pi_menu.FLUX_BONUS}."/></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.FLUX_PI} PI</span>
					</td>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.RES_LEVEL}</span><br>
						<a href="javascript:use_pi('res')"><img src="images/rpg/clans/see/pi/res.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff Résistance</strong><br><br>Augmente la résistance de {pi_menu.RES_BONUS}." /></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.RES_PI} PI</span>
					</td>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.PV_LEVEL}</span><br>
						<a href="javascript:use_pi('pv')"><img src="images/rpg/clans/see/pi/PV.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff PV</strong><br><br>Augmente les PV de {pi_menu.PV_BONUS}." /></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.PV_PI} PI</span>
					</td>
				</tr>
				<tr>
					<td>
						<span style="color:white;font-size:16px;font-weight:bold;">LVL {pi_menu.PF_LEVEL}</span><br>
						<a href="javascript:use_pi('pf')"><img src="images/rpg/clans/see/pi/PF.png" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Buff PF</strong><br><br>Augmente les PF de {pi_menu.PF_BONUS}."/></a><br>
						<span style="color:rgb(50,255,50);font-size:16px;font-weight:bold;">Augmenter<br>{pi_menu.PF_PI} PI</span>
					</td>
				</tr>
			</table-->
		</div>
		<!-- END pi_menu -->
		
		<table id="main_table" style="position:absolute;top:0;left:0;width:1340px;height:870px">
			<tr>
				<!-- clan leader & members -->
				<td width="304">
					<div id="clan_info">
						<p id="clan_leader_text">{CLAN_LEADER}</p>
						<p id="clan_level_text">{CLAN_LEVEL} <span style="color:cyan">{CLAN_XP_BONUS}</span></p>
						<p id="clan_members_text">{CLAN_MEMBERS} <span style="color:cyan">{CLAN_RALZ_BONUS}</span></p>
					</div>
					<!-- BEGIN members_display -->
					<div id="clan_members">
						<div id="members_div" style="position:relative;top:80px;overflow:hidden;height:440px">
							<table>
								<!-- BEGIN members_bloc -->
								<tr {members_display.members_bloc.LEADER_MENU}>
									<td width="204">
										<span style="float:left;margin-left:20px">{members_display.members_bloc.MEMBER_NAME}</span>
									</td>
									<td width="100" align="right">
										<span style="float:right;margin-right:20px">LVL{members_display.members_bloc.MEMBER_LEVEL}</span>
									</td>
								</tr>
								<!-- END members_bloc -->
							</table>
						</div>
						<div style="height:40px;width:304px;margin-left:92px;margin-top:80px">
							<a href="javascript:scrollDiv('members_div',30)"><img src="images/rpg/clans/see/{SD_DIR}buttons/Monter.{SD_EXT}"/></a>
							<a href="javascript:scrollDiv('members_div',-30)"><img src="images/rpg/clans/see/{SD_DIR}buttons/Descendre.{SD_EXT}"/></a>
						</div>
					</div>
					<!-- END members_display -->
					
					<!-- BEGIN clan_button_bloc -->
					<a href="javascript:clan_button()" id="{clan_button_bloc.BUTTON_MODE}"></a>
					<!-- END clan_button_bloc -->
				</td>
				<!-- clan name & desc -->
				<td id="central_info" align="center">
						<a id="close_button" href="{BACK_LINK}"></a>
						<div id="clan_name">
							<p onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{CLAN_INFO}">
								<!-- BEGIN pi_link -->
								<a href="javascript:open_pi_menu()" style="text-decoration:none;color:white;">{CLAN_NAME}</a>
								<!-- END pi_link -->
								<!-- BEGIN no_pi_link -->
								{CLAN_NAME}
								<!-- END no_pi_link -->
							</p>
						</div>
						<div id="clan_image" style="background-image:url({CLAN_IMAGE})"></div>
						<div id="desc_div" style="width:600px;height:347px;margin:auto;padding:10 10 10 10;margin-top:10px">
							<textarea id="clan_description" readonly>{CLAN_DESC}</textarea>
						</div>
				</td>
				<!-- clan chatbox -->
				<td align="center" width="304" height="845">
					<div id="chat">
							<div id="messages_div" style="overflow:hidden;height:745px">
							<!-- les messages sont affichés ici par AJAX -->
							</div>
							<div style="height:40px;width:304px">
								<a href="javascript:scrollDiv('messages_div',30)"><img src="images/rpg/clans/see/{SD_DIR}buttons/Monter.{SD_EXT}"/></a>
								<a href="javascript:scrollDiv('messages_div',-30)"><img src="images/rpg/clans/see/{SD_DIR}buttons/Descendre.{SD_EXT}"/></a>
							</div>
							<div style="height:40px;margin-top:5px">
								<input id="message_input" style="height:32px;font-size:20px;margin-left:10px"/>
								<a href="javascript:write_message(write_response)" style="display:block;float:right;margin-top:4px;margin-right:20px"><img src="images/rpg/clans/see/{SD_DIR}buttons/AjouterMSG.{SD_EXT}"/></a>
							</div>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>