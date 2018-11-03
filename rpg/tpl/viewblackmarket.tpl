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

		<title>Exitium - Marché Noir</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewblackmarket{SD_CSS}.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/tooltip.css" />
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
		
		var buy_mode = "";
		
		function set_iframe_mode(mode){
			if( (mode == "sets") || (mode == "equips") || (mode == "upgrades") ){
				document.getElementById('iframe').style.borderColor = "rgb(209,171,144)";
			}
			if(mode == "syringes"){
				document.getElementById('iframe').style.borderColor = "rgb(156,180,214)";
			}
			if(mode == "special"){
				document.getElementById('iframe').style.borderColor = "rgb(169,148,211)";
			}
			
			document.getElementById('iframe').src = './viewblackmarketlist.php?sid=' + SID + '&mode=' + mode;
			document.getElementById('iframe_div').style.visibility = 'visible';
			
			buy_mode = mode;
			
			hide_buy_button();
			hide_message();
			
			if(mode != "upgrades")
				document.getElementById('buy_button').href = "javascript:buy_item(read_buy_response)";
			else
				document.getElementById('buy_button').href = "javascript:upgrade_weapon(read_upgrade_response)";
		}
		
		function buy_item(callback) {
			var xhr = getXMLHttpRequest();
     
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					if(xhr.status == 200 || xhr.status == 0) {
						callback(xhr.responseText);
					}
					else {
						alert(xhr.responseText);
					}
				}
			};
			
			var x = document.getElementById("iframe");
			var y = (x.contentWindow || x.contentDocument);
			if (y.document) y = y.document;
			
			var selected_item = y.getElementById("items_table").getAttribute("selected");
			var quantity = parseInt(y.getElementById("quantity_" + selected_item).innerHTML);
			
			xhr.open("GET", "buyitem.php?sid=" + SID + "&mode=" + buy_mode + "&s=" + selected_item + "&q=" + quantity, true);
			xhr.send(null);
		}
		
		function read_buy_response(response) {
			hide_iframe();
			hide_buy_button();
			
			if(string_starts_with(response, "buy_ok")) {
				show_message('AchatOK.png');
			} else if(string_starts_with(response, "no_money")) {
				show_message('PasARGENT.png');
			} else if(string_starts_with(response, "inventory_full")) {
				show_message('PasplaceINV.png');
			} else if(string_starts_with(response, "not_connected")) {
				alert("Vous n'êtes pas connecté !");
			} else if(string_starts_with(response, "error")) {
				alert("Une erreur est survenue.");
			} else {
				alert("Le serveur a retourné une valeur inconnue : " + response);
			}
		}
		
		function upgrade_weapon(callback) {
			var xhr = getXMLHttpRequest();
     
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					callback(xhr.responseText);
				}
			};
			
			var x = document.getElementById("iframe");
			var y = (x.contentWindow || x.contentDocument);
			if (y.document) y = y.document;
			
			var selected_item = y.getElementById("items_table").getAttribute("selected");
			
			xhr.open("GET", "upgradeweapon.php?sid=" + SID + "&s=" + selected_item, true);
			xhr.send(null);
		}
		
		function read_upgrade_response(response) {
			hide_iframe();
			hide_buy_button();
			if(string_starts_with(response, "upgrade_success")) {
				show_message('AmeOK.png');
			} else if(string_starts_with(response, "upgrade_failure")) {
				show_message('AmeECHEC.png');
			} else if(string_starts_with(response, "no_money")) {
				show_message('PasARGENT.png');
			} else if(string_starts_with(response, "not_connected")) {
				alert("Vous n'êtes pas connecté !");
			} else if(string_starts_with(response, "error")) {
				alert("Une erreur est survenue.");
			}
		}
		
		function hide_iframe() {
			document.getElementById('iframe_div').style.visibility = "hidden";
		}
		
		function hide_message() {
			document.getElementById('message').style.visibility = "hidden";
			document.getElementById('message_pic').src = '';
		}
		
		function hide_buy_button() {
			document.getElementById('buy_button').style.visibility = "hidden";
		}
		
		function show_iframe() {
			document.getElementById('iframe_div').style.visibility = "visible";
		}
		
		function show_message(image) {
			//message.style.backgroundImage = "url('http://localhost/forumH2/images/rpg/blackmarket/messages/" + image + "')";
			document.getElementById('message').style.visibility = "visible";
			document.getElementById('message_pic').src = './images/rpg/blackmarket/messages/' + image;
		}
		
		function show_buy_button() {
			document.getElementById('buy_button').style.visibility = "visible";
		}
		
		function hide_buy_button() {
			document.getElementById('buy_button').style.visibility = "hidden";
		}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		<!-- rpg menu & chatbox-->
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<!-- BEGIN background_music -->
		<object type="application/x-shockwave-flash" data="./dewplayer/dewplayer.swf?mp3=./rpg/sound/mp3/BlackMarket.mp3&amp;autostart=1&amp;autoreplay=true" width="0" height="0"></object>
		<!-- END background_music -->
		
		<!-- pour la tooltip -->
		<div id="tooltip"></div>
		
		<!-- Main Menu -->
		<table id="main_menu">
			<tr>
				<td>
					<table id="main_menu_bg">
						<tbody>
							<tr>
								<td colspan="2" align="center" valign="center">
									<img id="buy_or_leave" alt="" style="margin-top:13px" src="images/rpg/blackmarket/{SD_DIR}buy_or_leave.{SD_EXT}"/>
								</td>
							</tr>
							<tr>
								<td align="center" valign="center">
									<div id="equip_bg">
										<div style="display:inline-block;margin-top:20px">
											<a href="javascript:set_iframe_mode('sets')" style="margin-bottom:40px" id="set_button"></a>
											<a href="javascript:set_iframe_mode('equips')" style="margin-bottom:40px" id="equip_button"></a>
											<a href="javascript:set_iframe_mode('upgrades')" id="upgrade_button"></a>
										</div>
									</div>
								</td>
								
								<td align="center" valign="center">
									<div style="margin-right:13px">
										<a href="javascript:set_iframe_mode('syringes')" style="margin-bottom:35px" id="syringe_button"></a>
										<a href="javascript:set_iframe_mode('special')" id="special_button"></a>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<a href="{BACK_LINK}" id="exit_button"></a>
				</td>
			</tr>
		</table>
		
		<!-- iframe -->
		<div id="iframe_div">
			<table>
				<tr>
					<td align="center" valign="center">
						<iframe id="iframe" width="454" height="454"  src="">Votre navigateur ne supporte pas les frames !</iframe>
					</td>
				</tr>
				<tr>
					<td valign="center" style="visibility:hidden">
						<a href="javascript:buy_item(read_buy_response)" id="buy_button"></a>
					</td>
				</tr>
			</table>
		</div>
		
		<!-- pour les messages -->
		<div id="message"><img id="message_pic" alt="" src=""/></div>
	</body>
</html>