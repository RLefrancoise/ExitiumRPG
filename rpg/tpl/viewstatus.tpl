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

		<title>Exitium - Statut de {USER_STATUS}</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewstatus{SD_CSS}.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/tooltip.css" />
		
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="rpg/js/imageutils.js"></script>
		<script type="text/javascript" src="rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript" src="rpg/js/fusion.js"></script>
		<script type="text/javascript">
			
			function open_armorpart_menu(type, event) {
				if(type != 'cloth' && type != 'leggings' && type != 'glove' && type != 'shoe') return;
				
				var inner = '<ul>\
								<li><a href="javascript:remove_armorpart(\'' + type + '\')">Retirer</a></li>\
								<li><a href="javascript:change_armorpart_name(\'' + type + '\')">Renommer</a></li>\
								<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>\
							</ul>';
							
				moveElementAtMousePosition('menu', event, 0, 0);
				document.getElementById('menu').innerHTML = inner;
				document.getElementById('menu').style.visibility = 'visible';
			}
			
			function open_orb_menu(slot, event) {
				var inner = '<ul>\
								<li><a href="javascript:remove_orb(' + slot + ')">Retirer</a></li>\
								<li><a href="javascript:close_inventory_menu()">Fermer le menu</a></li>\
							</ul>';
							
				moveElementAtMousePosition('menu', event, 0, 0);
				document.getElementById('menu').innerHTML = inner;
				document.getElementById('menu').style.visibility = 'visible';
			}
			
			function remove_armorpart(type) {
			
				close_inventory_menu();
				
				if(type != 'cloth' && type != 'leggings' && type != 'glove' && type != 'shoe') return;
				
				//create ajax object for request
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						//here is the response from the server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "equipment_remove_ok")) {
							reload_part('equipment');
							reload_part('inventory');
							reload_part('stats');
							reload_part('state');
						} else if(string_starts_with(xhr.responseText, "equipment_remove_no_space")) { 
							alert('Votre inventaire est plein !');
						} else {
							alert('Le serveur a retourné une valeur inconnue.\n' + xhr.responseText);
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
					
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=equipment_remove&type=" + type, true);
				xhr.send(null);
			}
			
			function remove_orb(slot) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 4) return;
				
				//create ajax object for request
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						//here is the response from the server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "orb_remove_ok")) {
							reload_part('inventory');
							reload_part('stats');
							reload_part('state');
							reload_part('orbs');
						} else if(string_starts_with(xhr.responseText, "orb_remove_no_space")) { 
							alert('Votre inventaire est plein !');
						} else {
							alert('Le serveur a retourné une valeur inconnue.');
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
					
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=orb_remove&slot=" + slot, true);
				xhr.send(null);
			}
			
			function change_armorpart_name(type) {
			
				close_inventory_menu();
				
				if(type != 'weapon' && type != 'cloth' && type != 'leggings' && type != 'glove' && type != 'shoe') return;
				
				var info = '';
				if(type == 'weapon') info = 'Nom de l\'arme';
				else if(type == 'cloth') info = 'Nom du haut';
				else if(type == 'leggings') info = 'Nom du bas';
				else if(type == 'glove') info = 'Nom des gants';
				else if(type == 'shoe') info = 'Nom des bottes';
				
				var input = prompt(info);
				if(input != null) {
					//if input is empty, ignore it
					if(input == '') return;
					
					//create ajax object for request
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
							//here is the response from the server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "equipment_rename_ok")) {
								reload_part('equipment');
							} else {
								alert(xhr.responseText);
								alert('Le serveur a retourné une valeur inconnue.');
							}
							
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					var _type = encodeURIComponent(type);
					var _input = encodeURIComponent(input);
					
					//xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=equipment_rename&type=" + _type + "&v=" + _input, true);
					//xhr.send(null);
					
					xhr.open("POST", "updateplayerstatus.php", true);
					xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xhr.send("sid=" + SID + "&mode=equipment_rename&type=" + _type + "&v=" + _input);
				}
			}
			
			function reload_part(part_type) {
				var t = '';
				var elem_id = '';
				
				switch(part_type) {
					case 'equipment':
						t = 'equipment';
						elem_id = 'character_equipment';
						break;
					case 'inventory':
						t = 'inventory';
						elem_id = 'character_inventory';
						break;
					case 'stats':
						t = 'stats';
						elem_id = 'character_stats';
						break;
					case 'state':
						t = 'state';
						elem_id = 'character_state';
						break;
					case 'orbs':
						t = 'orbs';
						elem_id = 'character_orbs';
						break;
					case 'skills':
						t = 'skills';
						elem_id = 'character_skills';
						break;
					case 'user_info':
						t = 'user_info';
						elem_id = 'user_main_info';
						break;
					case 'options':
						t = 'options';
						elem_id = 'options_bg';
						break;
					default:
						break;
				}
				
				//create ajax object for request
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
				
					if(xhr.readyState == 4) {
					
						if( xhr.status == 200 || xhr.status == 0 ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else {
								document.getElementById(elem_id).innerHTML = xhr.responseText;	
							}
							
						} else {
							//if(xhr.status != 508) // byethost problem ?
							//	alert('Une erreur est survenue lors du traitement de la requête. (statut : ' + xhr.status + ' ' + xhr.statusText + ')');
							//else
								reload_part(part_type);
						}
						
					}
				};
			
				xhr.open("GET", "viewstatuspart.php?sid=" + SID + "&mode=" + t, true);
				xhr.send(null);
			}
			
			function load_parts() {
				reload_part('equipment');
				reload_part('inventory');
				reload_part('stats');
				reload_part('state');
				reload_part('orbs');
				reload_part('skills');
				reload_part('user_info');
				reload_part('options');
			}
			
			function open_inventory_menu(slot, event) {
			
				//create ajax object to request menu according to item
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else {
							moveElementAtMousePosition('menu', event, 0, 0);
							document.getElementById('menu').innerHTML = xhr.responseText;
							document.getElementById('menu').style.visibility = 'visible';
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "getinventorymenu.php?sid=" + SID + "&slot=" + slot, true);
				xhr.send(null);
			}
			
			function close_inventory_menu() {
				document.getElementById('menu').style.visibility = 'hidden';
			}
			
			function use_item(slot) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 16) return;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "not_usable")) {
							alert('Vous ne pouvez pas utiliser cet objet.');
						} else if(string_starts_with(xhr.responseText, "use_ok")){
							reload_part('inventory');
							reload_part('stats');
							reload_part('state');
							reload_part('equipment');
							reload_part('skills');
						} else {
							alert(xhr.responseText);
							alert('Le serveur a retourné une valeur inconnue.');
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=use_item&slot=" + slot, true);
				xhr.send(null);
			}
			
			function equip_item(slot) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 16) return;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "equip_ok")){
							reload_part('equipment');
							reload_part('inventory');
							reload_part('stats');
							reload_part('state');
							reload_part('orbs');
						} else if(string_starts_with(xhr.responseText, "equipment_remove_no_space")) {
							alert('Pas assez de place dans l\'inventaire !');
						} else if(string_starts_with(xhr.responseText, "equip_item_level_too_low")) {
							alert('Vous n\'avez pas le niveau requis pour équiper cet objet !');
						} else if(string_starts_with(xhr.responseText, "equip_item_no_orb_slot")) {
							alert('Vous ne pouvez plus équiper d\'orbes, retirer en une d\'abord.');
						} else if(string_starts_with(xhr.responseText, "equip_already_has_orb")) {
							alert('Vous avez déjà équipé cette orbe.');
						} else {
							alert('Le serveur a retourné une valeur inconnue.');
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=equip_item&slot=" + slot, true);
				xhr.send(null);
			}
			
			function request_sell_price(slot, multi) {
				close_inventory_menu();
				
				if(slot < 1 || slot > 16) return;
				
				var quantity = 1;
				
				if(multi == true) {
					var input = prompt('Quantité à vendre');
					if(input == null || input == '') return;
					
					var reg = /^\d+$/;
					if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); request_sell_price(slot, multi); return; }
					
					quantity = input;
					
					if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); request_sell_price(slot, multi); return; }
				}
				
				
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else {
							var sell_price = xhr.responseText.substring(0, xhr.responseText.indexOf("|")); //bricolage ici mais peut pas faire autrement avec serveurs gratuits
							var valid_sell = confirm('Vendre pour ' + sell_price + ' Ralz ?');
							if(valid_sell == true) {
								sell_item(slot, quantity);
							}
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "getsellprice.php?sid=" + SID + "&slot=" + slot + '&q=' + quantity, true);
				xhr.send(null);
			}
			
			function sell_item(slot, quantity) {
				close_inventory_menu();
				
				if(slot < 1 || slot > 16) return;
				
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "sell_ok")){
							alert('La vente a été effectuée.');
							reload_part('inventory');
						} else {
							alert(xhr.responseText);
							alert('Le serveur a retourné une valeur inconnue.');
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=sell_item&slot=" + slot + "&q=" + quantity, true);
				xhr.send(null);
			}
			
			function drop_item(slot, multi) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 16) return;
				
				var quantity = 1;
				
				if(multi == true) {
					var input = prompt('Quantité à jeter');
					if(input == null || input == '') return;
					
					var reg = /^\d+$/;
					if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); drop_item(slot, multi); return; }
					
					quantity = input;
					
					if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); drop_item(slot, multi); return; }
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
							} else if(string_starts_with(xhr.responseText, "drop_item_ok")){
								reload_part('inventory');
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=drop_item&slot=" + slot + "&v=" + quantity, true);
					xhr.send(null);

				}
			}
			
			function store_item(slot, multi) {
			
				close_inventory_menu();
				
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
			
			function give_stat_point(stat) {
				if(stat != 'atk' && stat != 'def' && stat != 'res' && stat != 'spd' && stat != 'flux') return;
				
				var txt = '';
				if(stat == 'atk') txt = 'Donner un point en attaque ?';
				else if(stat == 'def') txt = 'Donner un point en défense ?';
				else if(stat == 'res') txt = 'Donner un point en résistance ?';
				else if(stat == 'spd') txt = 'Donner un point en vitesse ?';
				else txt = 'Donner un point en flux ?';
				
				var c = confirm(txt);
				if(c == true) {
					
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						
							//response from server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "give_ok")){
								reload_part('stats');
								reload_part('state');
								reload_part('equipment');
							} else if(string_starts_with(xhr.responseText, "no_more_points")){
								alert("Vous ne pouvez plus donner de points.");
							} else if(string_starts_with(xhr.responseText, "max_capacity")){
								alert("Cette statistique a atteint sa valeur maximale.");
							}else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=give_stat_point&stat=" + stat, true);
					xhr.send(null);
				}
			}
			
			function open_skill_menu(slot, event) {
				/*if(slot < 1 || slot > 4) return;
				
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else {
							moveElementAtMousePosition('menu', event, 0, 0);
							document.getElementById('menu').innerHTML = xhr.responseText;
							document.getElementById('menu').style.visibility = 'visible';
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "getskillmenu.php?sid=" + SID + "&slot=" + slot, true);
				xhr.send(null);*/
				
				if(slot < 1 || slot > 4) return;
				
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "<")) {
							document.getElementById("skills_menu").innerHTML = xhr.responseText;
							
							show_skills_menu();
						} else {
							alert(xhr.responseText);
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "getskillmenu.php?sid=" + SID + "&slot=" + slot, true);
				xhr.send(null);
			}
			
			function show_skills_menu() {
				close_inventory_menu();
				
				document.getElementById("skills_menu").style.display = "block";
			}
			
			function hide_skills_menu() {
				close_inventory_menu();
				
				document.getElementById("skills_menu").style.display = "none";
			}
			
			function open_skill_actions(slot, event) {
				if(slot < 1 || slot > 4) return;
				
				//create ajax object to request menu according to item
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else {
							//alert(xhr.responseText);
							moveElementAtMousePosition('menu', event, 0, 0);
							document.getElementById('menu').innerHTML = xhr.responseText;
							document.getElementById('menu').style.visibility = 'visible';
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=skill_actions&slot=" + slot, true);
				xhr.send(null);
			}
			
			function learn_skill(skill_nb, skill_type) {
			
				close_inventory_menu();
				hide_skills_menu();
				
				if(skill_nb < 1 || skill_nb > 4) return;
				
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "invalid_skill")) {
							alert('Le skill choisi est invalide.');
						} else if(string_starts_with(xhr.responseText, "already_learnt")) {
							alert('Vous connaissez déjà ce skill. Merci d\'en choisir un autre.');
						} else if(string_starts_with(xhr.responseText, "slot_already_used")) {
							alert('Ce slot est déjà utilisé.');
						} else if(string_starts_with(xhr.responseText, "learn_skill_ok")) {
							reload_part('skills');
						}else {
							alert(xhr.responseText);
							alert('Le serveur a retourné une valeur inconnue.');
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=learn_skill&slot=" + skill_nb + "&t=" + encodeURIComponent(skill_type), true);
				xhr.send(null);
			}
			
			function rename_skill(skill_nb) {
			
				close_inventory_menu();
				
				if(skill_nb < 1 || skill_nb > 4) return;
				
				var input = prompt('Renommer le skill ' + skill_nb + ' :');
				if(input != null) {
					//if input is empty, ignore it
					if(input == '') return;
					
					//create ajax object for request
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
							//here is the response from the server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "skill_rename_ok")) {
								reload_part('skills');
							} else {
								alert('Le serveur a retourné une valeur inconnue.');
							}
							
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					var _input = encodeURIComponent(input);
					
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=skill_rename&name=" + _input + "&nb=" + skill_nb, true);
					xhr.send(null);
				}
			}
			
			function change_skill_element(slot, element) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 4) return;
				
				//create ajax object for request
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						//here is the response from the server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "skill_change_element_ok")) {
							reload_part('skills');
						} else {
							alert('Le serveur a retourné une valeur inconnue.');
						}
						
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
				
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=skill_change_element&element=" + element + "&slot=" + slot, true);
				xhr.send(null);
			}
			
			function get_skill_element_menu(slot, event) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 4) return;
				
				//create ajax object for request
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						//here is the response from the server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else {
							//alert(xhr.responseText);
							moveElementAtMousePosition('menu', event, 0, 0);
							document.getElementById('menu').innerHTML = xhr.responseText;
							document.getElementById('menu').style.visibility = 'visible';
						}
						
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
				
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=skill_element_menu&slot=" + slot, true);
				xhr.send(null);
			}
			
			function set_gender() {
				
				var input = prompt('Taper M pour homme, F pour femme :');
				if(input != null) {
					//if input is empty, ignore it
					if(input == '') return;
					//if input is not valid, retry
					if(input != 'M' && input != 'F') { set_gender(); return; }
					
					//create ajax object for request
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
							//here is the response from the server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "set_gender_ok")) {
								reload_part('user_info');
							} else {
								alert(xhr.responseText);
								alert('Le serveur a retourné une valeur inconnue.');
							}
							
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
					
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=set_gender&g=" + input, true);
					xhr.send(null);
				}
			}
			
			function center_display() {
			
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				document.getElementById('status_div').style.margin = "auto";
				
				if(larg > 1362) {
					document.getElementById('status_div').style.left = "50%";
					document.getElementById('status_div').style.marginLeft = "-681px";
				}
				else {
					document.getElementById('status_div').style.left = "0";
				}
				
				if(haut > 935) {
					document.getElementById('status_div').style.top = "50%";
					document.getElementById('status_div').style.marginTop = "-467px";
				}
				else {
					document.getElementById('status_div').style.top = "5px";
				}
			}
			
			function give_ralz() {
			
				close_inventory_menu();
				
				var ralz = '';
				
				do {
					ralz = prompt('Nombre de Ralz :');
				
					var reg = /^\d+$/;
					
					if(ralz == null) return;
					
					if(!reg.test(ralz)) alert('Veuillez saisir un chiffre.');
					
				}while(!reg.test(ralz));
				
				var player = '';
				
				do {
					player = prompt('Nom du joueur à qui envoyer :');
					
					if(player == null) return;
					
					if(player == '') alert('Veuillez saisir un nom.');
					
				}while(player == '');
				
				var c = confirm('Envoyer ' + ralz + ' Ralz au joueur ' + player + '. Vous êtes sur ?');
				if(c == true) {
					
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						
							//response from server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "no_player")) {
								alert('Ce joueur n\'existe pas.');
							} else if(string_starts_with(xhr.responseText, "give_to_self")) {
								alert('Vous ne pouvez pas vous envoyer de l\'argent.');
							} else if(string_starts_with(xhr.responseText, "give_ralz_ok")){
								alert("L'envoi à réussi. Le joueur a été informé par MP de votre envoi.");
								reload_part('inventory');
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert(xhr.responseText);
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=give_ralz&r=" + ralz + "&p=" + encodeURIComponent(player), true);
					xhr.send(null);

				}
			}
			
			function give_item(slot, multi) {
			
				close_inventory_menu();
				
				if(slot < 1 || slot > 16) return;
				
				var quantity = 1;
				
				if(multi == true) {
					var input = prompt('Quantité à envoyer');
					if(input == null || input == '') return;
					
					var reg = /^\d+$/;
					if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); give_item(slot, multi); return; }
					
					quantity = input;
					
					if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); give_item(slot, multi); return; }
				}
				
				var player = '';
				
				do {
					player = prompt('Nom du joueur à qui envoyer :');
					
					if(player == null) return;
					
					if(player == '') alert('Veuillez saisir un nom.');
					
				}while(player == '');
				
				var c = confirm('Envoyer ' + quantity + ' exemplaire(s) de cet objet au joueur ' + player + '. Vous êtes sur ?');
				if(c == true) {
					
					var xhr = getXMLHttpRequest();
		 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						
							//response from server
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue.');
							} else if(string_starts_with(xhr.responseText, "no_player")) {
								alert('Ce joueur n\'existe pas.');
							} else if(string_starts_with(xhr.responseText, "give_to_self")) {
								alert('Vous ne pouvez pas vous envoyer de l\'argent.');
							} else if(string_starts_with(xhr.responseText, "give_error")) {
								alert('L\'envoi a échoué. L\'inventaire de ' + player + ' est peut-être plein.');
							} else if(string_starts_with(xhr.responseText, "give_item_ok")){
								alert("L'envoi à réussi. Le joueur a été informé par MP de votre envoi.");
								reload_part('inventory');
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
						} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
							alert(xhr.responseText);
							alert('Une erreur est survenue lors du traitement de la requête.');
						}
					};
				
					xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=give_item&s=" + slot + "&q=" + quantity + "&p=" + encodeURIComponent(player), true);
					xhr.send(null);

				}
			}
			
			function set_option(option, state) {
				if(option != 'sound' && option != 'animations' && option != 'alpha' && option != 'hd') return;
				if(state != 'on' && state != 'off') return;
				
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "set_option_ok")) {
							reload_part('options');
						}else {
							alert(xhr.responseText);
							alert('Le serveur a retourné une valeur inconnue.');
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=set_option&o=" + option + "&s=" + state, true);
				xhr.send(null);
			}
			
			function show_intro_link_menu(event) {
			
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else {
							//alert(xhr.responseText);
							moveElementAtMousePosition('menu', event, 0, 0);
							document.getElementById('menu').innerHTML = xhr.responseText;
							document.getElementById('menu').style.visibility = 'visible';
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=get_intro_link_menu", true);
				xhr.send(null);
			}
			
			function go(url) {
				document.location.href = url;
			}
			
			function set_introduction_link() {
				
				close_inventory_menu();
				
				var url = '';
				
				do {
					url = prompt('Lien vers la fiche de présentation :');
					
					if(url == null) return;
					
					if(url == '' || !string_starts_with(url, "http://")) alert('Veuillez saisir une url valide.');
					
				}while(url == '' || !string_starts_with(url, "http://"));
				
				var xhr = getXMLHttpRequest();
	 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
					
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
						} else if(string_starts_with(xhr.responseText, "invalid_url")) {
							alert('Cette URL est invalide. Vérifiez que le nom de domaine est correct.');
						} else if(string_starts_with(xhr.responseText, "set_introduction_link_ok")) {
							alert("Le lien a été mis à jour.");
						}else {
							alert('Le serveur a retourné une valeur inconnue : ' + xhr.responseText);
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
					}
				};
			
				xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=set_intro_link&url=" + encodeURIComponent(url), true);
				xhr.send(null);
			}
			
			function open_fusion_menu() {
				close_inventory_menu();
				document.getElementById('fusion_block').style.display = 'block';
				get_fusion_skills(1);
				get_fusion_skills(2);
			}
			
			function close_fusion_menu() {
				document.getElementById('fusion_block').style.display = 'none';
				reset_fusion();
			}
			
			function give_pi() {
				close_inventory_menu();
				
				var input = prompt('Nombre de Ralz à donner au clan');
				if(input == null || input == '') return;
				
				var reg = /^\d+$/;
				if(!reg.test(input)) { alert('Veuillez saisir un chiffre.'); open_pi_popup(); return; }
				
				quantity = input;
				
				if(quantity == 0) { alert('Veuillez saisir une quantité positive.'); open_pi_popup(); return; }
				
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
								} else if(string_starts_with(xhr.responseText, "no_clan")) {
									alert('Vous n\'avez pas de clan !');
								} else if(string_starts_with(xhr.responseText, "pi_ok")) {
									reload_part('inventory');
								} else {
									alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
								}
								
							} else {
								alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
							}
						}
						
					};
					
					xhr.open("GET", "updateplayerstatus.php?mode=give_pi&sid=" + SID + "&q=" + quantity, true);
					xhr.send(null);
				}
				
			}
			
			function change_onglet(name)
			{
				document.getElementById('type_'+anc_onglet).className = 'onglet_0 onglet';
				document.getElementById('type_'+name).className = 'onglet_1 onglet';
				document.getElementById('skills_'+anc_onglet).style.display = 'none';
				document.getElementById('skills_'+name).style.display = 'block';
				anc_onglet = name;
			}
			
			var anc_onglet = 'physical';
            
		</script>
		
		<style type="text/css">
			.onglet
			{
					display:inline-block;
					margin-top:3px;
					margin-left:3px;
					margin-right:3px;
					padding:3px;
					border:1px solid black;
					cursor:pointer;
			}
			.onglet_0
			{
					background:#bbbbbb;
					border-bottom:1px solid black;
			}
			.onglet_1
			{
					background:#dddddd;
					border-bottom:0px solid black;
					padding-bottom:4px;
			}
			.skills_list
			{
				margin-top:-1px;
				padding:5px;
				display:none;
			}
		</style>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:load_parts();javascript:center_display();javascript:change_onglet(anc_onglet);" onresize="javascript:center_display()">
		<!-- rpg menu & chatbox-->
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>

		<!-- BEGIN background_music -->
		<div id="bgm">
			<audio id="bgm_audio" autoplay loop>
				<source src="./rpg/sound/mp3/Status.mp3" type="audio/mpeg">
				<source src="./rpg/sound/ogg/Status.ogg" type="audio/ogg">
			</audio>
		</div>
		<!-- END background_music -->
		
		<!-- pour la tooltip -->
		<div id="tooltip"></div>
		<div id="menu">
			<!-- menu affiché par AJAX -->
		</div>
		<!-- fusion-->
		<div id="fusion_block" style="display:none">
			<div id="fusion_bg"></div>
			<div style="width:1050px;height:660px;position:fixed;top:50%;left:50%;margin-left:-525px;margin-top:-330px;z-index:11">
				<a style="display:inline-block;width:29px;height:29px;position:absolute;top:92px;left:50%;margin-left:-15px;;" href="javascript:close_fusion_menu()"><img src="images/rpg/status/fusion/QuitterFusion2.png" /></a>
				<div id="fusion_menu">
					<div id="fusion_skills1">
					</div>
					<div id="fusion_skills2">
					</div>
				</div>
				<div id="fusion_menu2">
				</div>
				<a id="fusion_button" style="display:inline-block;position:absolute;left:50%;margin-left:-65px" href="javascript:fuse_skill()"></a>
			</div>
		</div>
		
		<!-- skills menu -->
		<div id="skills_menu" style="display:none;text-align:center;z-index:12;background:rgb(64,64,128);border:2px outset white;position:fixed;width:600px;height:400px;top:50%;left:50%;margin-left:-300px;margin-top:-200px;">
		</div>
		
		<!-- main div -->
		<div id="status_div">
			<a id="close_button" href="{BACK_LINK}"></a>
			<table id="status_table">
				<tr>
					<td valign="top">
						<!-- fiche du personnage -->
						<table id="character_info">
							<tbody class="character_status_window_text">
								<tr>
									<td colspan="3"><img id="character_status_text_display" alt="" src="images/rpg/status/{SD_DIR}character_status_text_display.{SD_EXT}"/></td>
								</tr>
								
								<tr>
									<td rowspan="2"><img id="character_avatar" alt="" src="{USER_AVATAR}" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{AVATAR_INFO}"/></td>
									<td colspan="2">
											<div id="character_equipment">
												<!-- Equipement affiché ici par AJAX -->
											</div>
									</td>
								</tr>
								
								<tr>
									<td>
										<div id="character_orbs">
											<!-- orbs affichées par AJAX -->
										</div>
									</td>
								</tr>
								
								<tr valign="middle">
									<td colspan="3">
										<div id="character_state" style="margin-top:10px;float:left">
											<!-- état affiché par AJAX -->
										</div>
										
										<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Vos points de karma. Confèrent un bonus d'expérience.<br>Faites du RP pour gagner plus d'expérience !" style="margin:auto;text-align:center">
											<p style="display:inline;margin:0;padding:0">Karma - Bonus {USER_KARMA_BONUS}%<p>
											<!-- BEGIN karma_bloc -->
											<img alt="" src="images/rpg/status/{SD_DIR}icons/{karma_bloc.KARMA_IMAGE}.{SD_EXT}"/>
											<!-- END karma_bloc -->
										</div>
									</td>
								</tr>
								
								<tr>
									<td colspan="3" align="center">
										<table cellspacing="20" style="margin-top:-30px">
											<tr class="character_status_window_text">
												<td valign="center">
													<div id="character_stats">
														<!-- stats affichées par AJAX -->
													</div>
												</td>
												
												<td valign="center">
													<div id="character_skills">
														<!-- skills affichés par AJAX -->
													</div>
												</td>
												
												<td valign="top">
													<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Votre niveau. Gagnez des points d'expérience pour passer au niveau supérieur et devenir plus fort." id="character_level">
														<img style="margin-top:40px" alt="" src="images/rpg/status/{SD_DIR}character_level.{SD_EXT}"/>
														<p id="character_level_display">{USER_LEVEL}</p>
													</div>
												</td>
												
											</tr>
										</table>
									</td>
								</tr>
								
								<tr>
									<td colspan="3" valign="center" align="center">
										
										<div id="character_inventory">
												<!-- inventaire affiché par AJAX -->
										</div>
									</td>
								</tr>
							<tbody>
						</table>
					</td>
					<td valign="top">
						<!-- fiche du joueur -->
						<table id="user_info">
							<tbody class="character_status_window_text">
								<tr>
									<td><img id="character_status_text_display" alt="" src="images/rpg/status/{SD_DIR}user_status_text_display.{SD_EXT}"/></td>
								</tr>
								<tr>
									<td align="center" valign="center">
										<div id="user_main_info" style="display:inline-block">
											
										</div>
									</td>
								</tr>
								<tr>
									<td valign="center" align="center">
										<div style="display:inline-block">
											<p id="introduction_link" onclick="javascript:show_intro_link_menu(event)" style="margin-left:-250px;font-size:20px;cursor:pointer">Fiche de présentation</p>
										</div>
									</td>
								</tr>
								<tr>
									<td align="center" valign="center">
										<div id="user_forum_info" style="display:inline-block">
											<div style="display:inline-block;margin-left:20px">
												<p>Inscrit(e) le : {USER_REGDATE}</p>
												<p>Dernière visite le : {USER_LASTVISIT}</p>
												<p>Messages : {USER_MSG_NB}</p>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						
						<!-- fenêtre des options -->
						<div id="options_div">
							<img src="images/rpg/status/{SD_DIR}options_pic.{SD_EXT}"/>
							<div id="options_bg" style="width:400px">
								<!-- affiché par AJAX -->
							</div>	
						</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>