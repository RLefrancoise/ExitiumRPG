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

		<title>Exitium - Edition de monstre</title>
		
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
			
			function get_monsters_pictures() {
				
				var xhr = getXMLHttpRequest();
			 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if( (xhr.status == 200 || xhr.status == 0) ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "<")) {
								document.getElementById('monsters_images').innerHTML = xhr.responseText;
								
								if(mode == 'add') {
									set_monster_picture(root);
								}
								
								//set correct option if in edit mode
								if(mode == 'edit') {
									var current_img = document.getElementById('monster_img').src;
									current_img = current_img.substring(current_img.lastIndexOf("/") + 1);
									current_img = decodeURIComponent(current_img);
									
									/*var index = 0;
									var tmp = -1;
									
									//search index of img in the list
									var options = document.getElementById('monsters_images').childNodes;
									
									for(i = 0 ; i < document.getElementById('monsters_images').length ; i++) {
										if(options[i].nodeName != 'OPTION') { continue; }
										
										tmp++;
										
										if( options[i].value == current_img) {
											index = tmp;
											break;
										}
									}
									
									document.getElementById('monsters_images').selectedIndex = index;*/
									
									setSelectBoxByValue('monsters_images', current_img);
								}
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
							
						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}
					
				};
				
				xhr.open("GET", "get_monsters_pictures.php?sid=" + SID, true);
				xhr.send(null);
			}
		
			function get_monsters_bgm() {
				
				var xhr = getXMLHttpRequest();
			 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if( (xhr.status == 200 || xhr.status == 0) ) {
						
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							} else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							} else if(string_starts_with(xhr.responseText, "<")) {
								document.getElementById('monster_bgm').innerHTML = xhr.responseText;
								
								//set correct option if in edit mode
								if(mode == 'edit') {
									var current_bgm = document.getElementById('current_bgm').value;
									current_bgm = decodeURIComponent(current_bgm);
									
									/*var index = 0;
									var tmp = -1;
									
									//search index of bgm in the list
									var options = document.getElementById('monster_bgm').childNodes;
									
									for(i = 0 ; i < document.getElementById('monster_bgm').length ; i++) {
										if(options[i].nodeName != 'OPTION') { continue; }
										
										tmp++;
										
										if( options[i].value == current_bgm) {
											index = tmp;
											break;
										}
									}
									
									document.getElementById('monster_bgm').selectedIndex = index;*/
									
									setSelectBoxByValue('monster_bgm', current_bgm);
								}
								
								
							} else {
								alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
							}
							
						} else {
							alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
						}
					}
					
				};
				
				xhr.open("GET", "get_monsters_bgm.php?sid=" + SID, true);
				xhr.send(null);
			}
			
			function set_monster_picture(root) {
				document.getElementById('monster_img').src = root + 'images/rpg/battle/monsters/' + document.getElementById('monsters_images').value;
			}
			
			function set_mode(m) {
				mode = m;
			}
			
			function set_root(r) {
				root = r;
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:set_mode('{MODE}');javascript:set_root('{ROOT}');javascript:get_monsters_pictures();javascript:get_monsters_bgm();">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;margin:auto;text-align:center">
			<h2>Ajout et Modification de monstre</h2>
			
			<form autocomplete="on">
				<fieldset>
				<legend>Informations visuelles et audio</legend>
				<label for="name">Nom : </label><input type="text" id="name" name="name" value="{NAME}"></input><br>
				<label for="monsters_images">Image : </label><select id="monsters_images" onchange="javascript:set_monster_picture('{ROOT}');"></select><br>
				<label for="monster_bgm">BGM : </label><select id="monster_bgm"></select><br>
				<input type="hidden" id="current_bgm" name="current_bgm" value="{BGM}"></input>
				<img id="monster_img" src="{ROOT}images/rpg/battle/monsters/{IMG}"/><br>
				</fieldset>
				<fieldset>
				<legend>Données</legend>
				<label for="level">Niveau : </label><input type="text" id="level" name="level" value="{LEVEL}"></input><br>
				<label for="pv">PV : </label><input type="text" id="pv" name="pv" value="{PV}"></input><br>
				<label for="pf">PF : </label><input type="text" id="pf" name="pf" value="{PF}"></input><br>
				<label for="atk">Attaque : </label><input type="text" id="atk" name="atk" value="{ATTACK}"></input><br>
				<label for="def">Défense : </label><input type="text" id="def" name="def" value="{DEFENSE}"></input><br>
				<label for="spd">Vitesse : </label><input type="text" id="spd" name="spd" value="{SPEED}"></input><br>
				<label for="flux">Flux : </label><input type="text" id="flux" name="flux" value="{FLUX}"></input><br>
				<label for="res">Résistance : </label><input type="text" id="res" name="res" value="{RESISTANCE}"></input><br>
				<label for="ralz">Ralz : </label><input type="text" id="ralz" name="ralz" value="{RALZ}"></input><br>
				</fieldset>
				<fieldset>
				<legend>Combat</legend>
				<label for="behavior">Comportement : </label><input type="checkbox" id="behavior" name="behavior" value="attack" {ATTACK_CHECKED}>Attaque <input type="checkbox" name="behavior" value="skill" {SKILL_CHECKED}>Skill <input type="checkbox" name="behavior" value="defend" {DEFEND_CHECKED}>Défendre<br>
				</fieldset>
			</form>
		
		</div>
	</body>
</html>