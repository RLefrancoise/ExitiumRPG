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

		<title>Exitium - Ajout et Edition d'équipement</title>
		
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
			
			function get_items_icons() {
				
				var xhr = getXMLHttpRequest();
			 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if( (xhr.status == 200 || xhr.status == 0) ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "<")) {
								
								document.getElementById('items_images').innerHTML = xhr.responseText;
								
								if(mode == 'add') {
									set_item_picture(root);
								}
								
								//set correct option if in edit mode
								if(mode == 'edit') {
									var current_img = document.getElementById('current_img').value;
									//current_img = current_img.substring(current_img.lastIndexOf("/") + 1);
									current_img = decodeURIComponent(current_img);
									
									setSelectBoxByText('items_images', current_img);
								}
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
							
						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}
					
				};
				
				xhr.open("GET", "get_items_pictures.php?sid=" + SID, true);
				xhr.send(null);
			}
			
			function set_item_picture(root) {
				document.getElementById('item_img').src = root + 'images/rpg/icons/' + document.getElementById('items_images').value;
			}
			
			function set_mode(m) {
				mode = m;
			}
			
			function set_root(r) {
				root = r;
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:set_mode('{MODE}');javascript:set_root('{ROOT}');javascript:get_items_icons();">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;margin:auto;text-align:center">
			<h2>Ajout et Modification d'équipement</h2>
			
			<form autocomplete="on" action="item_action.php?mode={MODE}&type={TYPE}" method="post">
				<fieldset>
				<legend>Informations visuelles</legend>
					<label for="name">Nom : </label><input type="text" id="name" name="name" value="{NAME}"></input><br>
					<label for="desc">Description : </label><input type="text" id="desc" name="desc" value="{DESC}"></input><br>
					<label for="items_images">Image : </label><select id="items_images" name="items_images" onchange="javascript:set_item_picture('{ROOT}');"></select><br>
					<label for="price">Prix : </label><input type="text" id="price" name="price" value="{PRICE}"></input><br>
					<img id="item_img" src="{ROOT}images/rpg/icons/{IMG}"/><br>
					<input type="hidden" id="current_img" value="{IMG}"></input>
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
					<label for="level">Niveau requis : </label><input type="text" id="level" name="level" value="{LEVEL}"></input><br>
					<input type="hidden" name="item_id" value="{ID}"></input>
				</fieldset>
				<input type="submit" value="Valider"></input>
			</form>
		
		</div>
	</body>
</html>