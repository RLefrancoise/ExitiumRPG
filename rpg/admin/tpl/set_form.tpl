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

		<title>Exitium - Ajout et Edition de set</title>
		
		<link rel="stylesheet" type="text/css" href="{ROOT}rpg/admin/css/monster_form.css" />
		<link rel="stylesheet" type="text/css" href="{ROOT}rpg/css/tooltip.css" />

		<script type="text/javascript" src="{ROOT}rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/selectbox.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/session.js"></script>
		<!--script type="text/javascript" src="../../rpg/js/window.js"></script-->
		<script type="text/javascript">
		
			var mode = '';
			var root = '';
			
			function set_mode(m) {
				mode = m;
			}
			
			function set_root(r) {
				root = r;
			}
			
			function get_all_parts() {
				get_parts('clothes');
				get_parts('leggings');
				get_parts('gloves');
				get_parts('shoes');
			}
			
			function get_parts(type) {
				var xhr = getXMLHttpRequest();
			 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if( (xhr.status == 200 || xhr.status == 0) ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "<")) {
								
								if(type == 'clothes') {
									document.getElementById('clothes').innerHTML = xhr.responseText;
									
									if(mode == 'edit') {
										var c = document.getElementById('current_clothes').value;
										setSelectBoxByValue('clothes', c);
									}
									
								}
								else if(type == 'leggings') {
									document.getElementById('leggings').innerHTML = xhr.responseText;
									
									if(mode == 'edit') {
										var l = document.getElementById('current_leggings').value;
										setSelectBoxByValue('leggings', l);
									}
									
								}
								else if(type == 'gloves') {
									document.getElementById('gloves').innerHTML = xhr.responseText;
									
									if(mode == 'edit') {
										var g = document.getElementById('current_gloves').value;
										setSelectBoxByValue('gloves', g);
									}
									
								}
								else if(type == 'shoes') {
									document.getElementById('shoes').innerHTML = xhr.responseText;
									
									if(mode == 'edit') {
										var s = document.getElementById('current_shoes').value;
										setSelectBoxByValue('shoes', s);
									}
									
								}
								
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
							
						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}
					
				};
				
				xhr.open("GET", "get_set_parts.php?sid=" + SID + "&type=" + type, true);
				xhr.send(null);
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:set_mode('{MODE}');javascript:set_root('{ROOT}');javascript:get_all_parts();">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;margin:auto;text-align:center">
			<h2>Ajout et Modification de set</h2>
			
			<form autocomplete="on" action="item_action.php?mode={MODE}&type=sets" method="post">
				<fieldset>
				<legend>Informations visuelles</legend>
					<label for="name">Nom : </label><input type="text" id="name" name="name" value="{NAME}"></input><br>
					<label for="desc">Description : </label><input type="text" id="desc" name="desc" value="{DESC}"></input><br>
					<label for="price">Prix : </label><input type="text" id="price" name="price" value="{PRICE}"></input><br>
					<input type="hidden" id="current_clothes" value="{CLOTHES}"></input>
					<input type="hidden" id="current_leggings" value="{LEGGINGS}"></input>
					<input type="hidden" id="current_gloves" value="{GLOVES}"></input>
					<input type="hidden" id="current_shoes" value="{SHOES}"></input>
					<label for="clothes">Haut : </label><select id="clothes" name="clothes"></select><br>
					<label for="leggings">Bas : </label><select id="leggings" name="leggings"></select><br>
					<label for="gloves">Gants : </label><select id="gloves" name="gloves"></select><br>
					<label for="shoes">Bottes : </label><select id="shoes" name="shoes"></select><br>
				</fieldset>
				
				<fieldset>
				<legend>Données</legend>
					<label for="pv">PV : </label><input type="text" id="pv" name="pv" value="{PV}"></input><br>
					<label for="pf">PF : </label><input type="text" id="pf" name="pf" value="{PF}"></input><br>
					<label for="atk">Attaque : </label><input type="text" id="atk" name="atk" value="{ATTACK}"></input><br>
					<label for="def">Défense : </label><input type="text" id="def" name="def" value="{DEFENSE}"></input><br>
					<label for="spd">Vitesse : </label><input type="text" id="spd" name="spd" value="{SPEED}"></input><br>
					<label for="flux">Flux : </label><input type="text" id="flux" name="flux" value="{FLUX}"></input><br>
					<label for="res">Résistance : </label><input type="text" id="res" name="res" value="{RESISTANCE}"></input><br>
					<input type="hidden" name="item_id" value="{ID}"></input>
				</fieldset>
				<input type="submit" value="Valider"></input>
			</form>
		
		</div>
	</body>
</html>