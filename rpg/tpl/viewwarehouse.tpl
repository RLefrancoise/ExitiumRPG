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

		<title>Exitium - Banque</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewwarehouse{SD_CSS}.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/tooltip.css" />
		
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="rpg/js/imageutils.js"></script>
		<script type="text/javascript" src="rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
			
			function reload_part(part) {
				var div_id = '';
				
				switch(part) {
					case 'warehouse':
						div_id = 'warehouse_div';
						break;
					case 'inventory':
						div_id = 'inventory_div';
						break;
					case 'info':
						div_id = 'info_ralz';
						break;
				}
				
				var xhr = getXMLHttpRequest();
			 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if( (xhr.status == 200 || xhr.status == 0) ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "<")) {
								document.getElementById(div_id).innerHTML = xhr.responseText;
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}
					
				};
				
				xhr.open("GET", "viewwarehouse.php?sid=" + SID + "&part=" + part, true);
				xhr.send(null);
			}
			
			function retrieve(slot, multi) {
				
				if(slot < 1 || slot > 50) return;
				
				var quantity = 1;
				
				if(multi == true) {
					var input = prompt('Quantité à retirer');
					if(input == null || input == '') return;
					
					var reg = /^\d+$/;
					if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); retrieve(slot, multi); return; }
					
					quantity = input;
					
					if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); retrieve(slot, multi); return; }
				}
				
				var c = confirm('Vous êtes sur ?');
				if(c == true) {
				
					var xhr = getXMLHttpRequest();
			 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4) {
							if( (xhr.status == 200 || xhr.status == 0) ) {
							
								if(string_starts_with(xhr.responseText, "not_connected")) {
									alert('Vous n\'êtes pas connecté !');
								} else if(string_starts_with(xhr.responseText, "error")) {
									alert('Une erreur est survenue !');
								} else if(string_starts_with(xhr.responseText, "inventory_error")) {
									alert('Une erreur est survenue, votre inventaire est peut-être plein.');
								} else if(string_starts_with(xhr.responseText, "retrieve_ok")) {
									reload_part('warehouse');
									reload_part('inventory');
								} else {
									alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
								}
								
							} else {
								alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
							}
						}
						
					};
					
					xhr.open("GET", "warehouse.php?sid=" + SID + "&mode=retrieve&slot=" + slot + "&q=" + quantity, true);
					xhr.send(null);
				}
			}
			
			function store(slot, multi) {
				
				if(slot < 1 || slot > 16) return;
				
				var quantity = 1;
				
				if(multi == true) {
					var input = prompt('Quantité à stocker');
					if(input == null || input == '') return;
					
					var reg = /^\d+$/;
					if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); store_item(slot, multi); return; }
					
					quantity = input;
					
					if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); store_item(slot, multi); return; }
				}
				
				var c = confirm('Vous êtes sur ?');
				if(c == true) {
					
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						
							//response from server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "warehouse_error")) {
								alert('Une erreur est survenue, votre casier est peut-être plein.');
							} else if(string_starts_with(xhr.responseText, "store_ralz")) {
								alert('Les Ralz ne peuvent être stockés en tant qu\'objet.');
							} else if(string_starts_with(xhr.responseText, "store_ok")){
								reload_part('warehouse');
								reload_part('inventory');
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=store_item&slot=" + slot + "&q=" + quantity, true);
					xhr.send(null);

				}
			}
			
			function store_ralz() {
			
				var quantity = 0;
				
				var input = prompt('Quantité à stocker');
				if(input == null || input == '') return;
				
				var reg = /^\d+$/;
				if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); store_ralz(); return; }
				
				quantity = input;
				
				if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); store_ralz(); return; }
				
				var c = confirm('Vous êtes sur ?');
				if(c == true) {
				
					var xhr = getXMLHttpRequest();
			 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4) {
							if( (xhr.status == 200 || xhr.status == 0) ) {
							
								if(string_starts_with(xhr.responseText, "not_connected")) {
									alert('Vous n\'êtes pas connecté !');
								} else if(string_starts_with(xhr.responseText, "error")) {
									alert('Une erreur est survenue !');
								} else if(string_starts_with(xhr.responseText, "no_ralz")) {
									alert('Vous n\'avez pas de Ralz.');
								} else if(string_starts_with(xhr.responseText, "store_ok")) {
									alert("Les Ralz ont été stockés");
									reload_part('ralz');
								} else {
									alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
								}
								
							} else {
								alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
							}
						}
						
					};
					
					xhr.open("GET", "warehouse.php?sid=" + SID + "&mode=store_ralz&q=" + quantity, true);
					xhr.send(null);
				}
			}
			
			function retrieve_ralz() {
			
				var quantity = 0;
				
				var input = prompt('Quantité à retirer');
				if(input == null || input == '') return;
				
				var reg = /^\d+$/;
				if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); retrieve_ralz(); return; }
				
				quantity = input;
				
				if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); retrieve_ralz(); return; }
				
				var c = confirm('Vous êtes sur ?');
				if(c == true) {
				
					var xhr = getXMLHttpRequest();
			 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4) {
							if( (xhr.status == 200 || xhr.status == 0) ) {
							
								if(string_starts_with(xhr.responseText, "not_connected")) {
									alert('Vous n\'êtes pas connecté !');
								} else if(string_starts_with(xhr.responseText, "error")) {
									alert('Une erreur est survenue !');
								} else if(string_starts_with(xhr.responseText, "no_ralz")) {
									alert('Votre compte en banque est vide.');
								} else if(string_starts_with(xhr.responseText, "retrieve_ok")) {
									alert("Les Ralz ont été retirés.");
									reload_part('info');
								} else {
									alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
								}
								
							} else {
								alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
							}
						}
						
					};
					
					xhr.open("GET", "warehouse.php?sid=" + SID + "&mode=retrieve_ralz&q=" + quantity, true);
					xhr.send(null);
				}
			}
			
			function clock_counter() {
				setInterval("clock_decrease()", 1000);
			}
			
			function clock_decrease() {
				var clock = document.getElementById('call_rate_clock').innerHTML;
				
				var t = clock.split(":");
				var h = parseInt(t[0], 10);
				var m = parseInt(t[1], 10);
				var s = parseInt(t[2], 10);
				
				s = s - 1;
				
				if(s < 0) {
					s = 59;
					m = m - 1;
					if(m < 10) m = "0" + m.toString();
				}
				if(m < 0) {
					m = 59;
					h = h - 1;
				}
				if(h < 0) {
					location.reload();
				}
				
				if(s >= 0 && s < 10) s = "0" + s.toString();
				if(m < 10) m = "0" + m.toString();
				if(h < 10) h = "0" + h.toString();
				
				document.getElementById("call_rate_clock").innerHTML = h + ":" + m + ":" + s;
			}
			
			function center_display() {
			
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				if(larg > 1200) {
					document.getElementById('main_div').style.left = "50%";
					document.getElementById('main_div').style.marginLeft = "-600px";
				}
				else {
					document.getElementById('main_div').style.left = "0";
				}
				
				if(haut > 638) {
					document.getElementById('main_div').style.top = "50%";
					document.getElementById('main_div').style.marginTop = "-319px";
				}
				else {
					document.getElementById('main_div').style.top = "5%";
				}
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:clock_counter();javascript:center_display();">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<div id="tooltip"></div>
		
		<!-- BEGIN background_music -->
		<object type="application/x-shockwave-flash" data="./dewplayer/dewplayer.swf?mp3=./rpg/sound/mp3/Warehouse.mp3&amp;autostart=1&amp;autoreplay=false" width="0" height="0"></object>
		<!-- END background_music -->
		
		<div id="main_div" style="width:1200px;height:638px;position:absolute;top:50%;left:50%;margin-left:-600px;margin-top:-319px;">
		
			<div style="width:1200px;height:480px;">
				
				<div style="width:263px;height:480px;margin-left:40px;float:left;">
					<div style="width:263px;height:220px;margin:auto;font-family:'Alien Encounters Solid';">
						<div id="info_ralz">
							<span style="display:inline-block;margin-left:170px;margin-top:15px;">{RALZ}</span>
							<span style="display:inline-block;margin-left:170px;margin-top:70px;">{CALL_RATE}%</span>
						</div>
						<span style="color:white;font-size:13px;display:inline-block;margin-top:4px;">Application du taux : <span id="call_rate_clock">{CALL_RATE_CLOCK}</span></span>
					</div>
					
					<div style="width:263px;height:202px;margin:auto;margin-top:50px;">
						<a id="store_button" href="javascript:store_ralz();"></a>
						<a id="retrieve_button" href="javascript:retrieve_ralz();"></a>
					</div>
				</div>
				
				<div id="warehouse_div">
					<img onload="set_image_position(this, 663, 28, true)" src="images/rpg/warehouse/buttons/Onglet01.png"></img>
					<img onload="set_image_position(this, 705, 28, true)" src="images/rpg/warehouse/buttons/Onglet02.png"></img>
					<img onload="set_image_position(this, 747, 28, true)" src="images/rpg/warehouse/buttons/Onglet03.png"></img>
					<span style="display:inline-block;position:absolute;top:52px;left:675px;color:white;font-family:'Alien Encounters Solid';font-size:14px;">Onglet 1</span>
					
					<!-- BEGIN item -->
					<div style="width:42px;height:42px;border:1px solid rgb(113,19,129);display:inline-block;position:absolute;left:{item.ITEM_X}px;top:{item.ITEM_Y}px;margin-left:-21px;margin-top:-21px;"></div>
					<img onload="set_image_position(this,{item.ITEM_X},{item.ITEM_Y},true)" onclick="{item.ON_CLICK}" title="{item.TOOLTIP_TEXT}" onmouseover="{item.MOUSE_OVER}" onmouseout="{item.MOUSE_OUT}" style="{item.STYLE}" src="{item.ITEM_ICON}"/>
					<!-- END item -->
				</div>
				
			</div>
		
			<div style="width:1200px;height:128px;margin:auto;margin-top:30px;">
				
				<a id="close_button" href="{BACK_LINK}"></a>
				
				<div id="inventory_div">
					<!-- BEGIN inventory_item -->
					<div style="width:42px;height:42px;border:1px solid rgb(41,64,167);display:inline-block;position:absolute;left:{inventory_item.ITEM_X}px;top:{inventory_item.ITEM_Y}px;margin-left:-21px;margin-top:-21px;"></div>
					<img onload="set_image_position(this,{inventory_item.ITEM_X},{inventory_item.ITEM_Y},true)" onclick="{inventory_item.ON_CLICK}" title="{inventory_item.TOOLTIP_TEXT}" onmouseover="{inventory_item.MOUSE_OVER}" onmouseout="{inventory_item.MOUSE_OUT}" style="{inventory_item.STYLE}" src="{inventory_item.ITEM_ICON}"/>
					<!-- END inventory_item -->
				</div>
			</div>
		
		</div>
		
	</body>
</html>