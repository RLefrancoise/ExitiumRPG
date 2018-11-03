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

		<title>Exitium - Carte du monde</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewmap{SD_CSS}.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/tooltip.css" />
		
		<!--script type="text/javascript" src="rpg/js/niftyplayer/niftyplayer.js"></script-->
		
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="rpg/js/imageutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<!--script type="text/javascript" src="soundmanager/script/soundmanager2.js"></script-->
		<!--script type="text/javascript" src="rpg/js/sound.js"></script-->
		
		<!--script type="text/javascript">
		soundManager.setup({
		  url: '/soundmanager/swf/',
		  // optional: use 100% HTML5 mode where available
		  preferFlash: false,
		  waitForWindowLoad: true,
		  onready: function() {
			start_bgm(); 
		  },
		  ontimeout: function() {
			// Hrmm, SM2 could not start. Missing SWF? Flash blocked? Show an error, etc.?
		  }
		});
		</script-->
		<script type="text/javascript">
			var battle_window = null;
			var current_area = false;
			var bgm_object = null;
			
			function show_area(area) {
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				current_area = area;
				
				switch(area) {
					case 'east_coast':
						document.getElementById('east_coast_zoom').style.width = "352px";
						document.getElementById('east_coast_zoom').style.height = "710px";
						document.getElementById('east_coast_zoom').style.left = "50%";
						document.getElementById('east_coast_zoom').style.top = "50%";
						document.getElementById('east_coast_zoom').style.marginLeft = "-176px";
						document.getElementById('east_coast_zoom').style.marginTop = "-355px";
						document.getElementById('east_coast_zoom').style.display = "block";
						
						if(larg < 352) {
							document.getElementById('east_coast_zoom').style.left = "0";
							document.getElementById('east_coast_zoom').style.marginLeft = "0px";
						}
						if(haut < 710) {
							document.getElementById('east_coast_zoom').style.top = "0";
							document.getElementById('east_coast_zoom').style.marginTop = "0px";
						}
						
						hide_map();
						break;
					case 'white_desert':
						document.getElementById('white_desert_zoom').style.width = "628px";
						document.getElementById('white_desert_zoom').style.height = "424px";
						document.getElementById('white_desert_zoom').style.left = "50%";
						document.getElementById('white_desert_zoom').style.top = "50%";
						document.getElementById('white_desert_zoom').style.marginLeft = "-314px";
						document.getElementById('white_desert_zoom').style.marginTop = "-212px";
						document.getElementById('white_desert_zoom').style.display = "block";
						
						if(larg < 628) {
							document.getElementById('white_desert_zoom').style.left = "0";
							document.getElementById('white_desert_zoom').style.marginLeft = "0px";
						}
						if(haut < 424) {
							document.getElementById('white_desert_zoom').style.top = "0";
							document.getElementById('white_desert_zoom').style.marginTop = "0px";
						}
						
						hide_map();
						break;
					case 'dry_desert':
						document.getElementById('dry_desert_zoom').style.width = "558px";
						document.getElementById('dry_desert_zoom').style.height = "402px";
						document.getElementById('dry_desert_zoom').style.left = "50%";
						document.getElementById('dry_desert_zoom').style.top = "50%";
						document.getElementById('dry_desert_zoom').style.marginLeft = "-279px";
						document.getElementById('dry_desert_zoom').style.marginTop = "-201px";
						document.getElementById('dry_desert_zoom').style.display = "block";
						
						if(larg < 558) {
							document.getElementById('dry_desert_zoom').style.left = "0";
							document.getElementById('dry_desert_zoom').style.marginLeft = "0px";
						}
						if(haut < 402) {
							document.getElementById('dry_desert_zoom').style.top = "0";
							document.getElementById('dry_desert_zoom').style.marginTop = "0px";
						}
						
						hide_map();
						break;
					case 'termorr':
						document.getElementById('termorr_zoom').style.width = "750px";
						document.getElementById('termorr_zoom').style.height = "770px";
						document.getElementById('termorr_zoom').style.left = "50%";
						document.getElementById('termorr_zoom').style.top = "50%";
						document.getElementById('termorr_zoom').style.marginLeft = "-375px";
						document.getElementById('termorr_zoom').style.marginTop = "-385px";
						document.getElementById('termorr_zoom').style.display = "block";
						
						if(larg < 750) {
							document.getElementById('termorr_zoom').style.left = "0";
							document.getElementById('termorr_zoom').style.marginLeft = "0px";
						}
						if(haut < 770) {
							document.getElementById('termorr_zoom').style.top = "0";
							document.getElementById('termorr_zoom').style.marginTop = "0px";
						}
						
						hide_map();
						break;
				}
			}
			
			function hide_area(area) {
				current_area = false;
				
				switch(area) {
					case 'east_coast':
						document.getElementById('east_coast_zoom').style.display = "none";
						break;
					case 'white_desert':
						document.getElementById('white_desert_zoom').style.display = "none";
						break;
					case 'dry_desert':
						document.getElementById('dry_desert_zoom').style.display = "none";
						break;
					case 'termorr':
						document.getElementById('termorr_zoom').style.display = "none";
						break;
				}
			}
			
			function show_map() {
				document.getElementById('main_div').style.visibility = "visible";
			}
			
			function hide_map() {
				document.getElementById('main_div').style.visibility = "hidden";
			}
			
			function start_battle(area, part) {
				stop_bgm();
				
				if(battle_window == null || battle_window.closed)
					battle_window = open_pve_window(SID, area, part);
				else
					battle_window.focus();
				
				pollForWindowClosure();
			}
			
			function pollForWindowClosure(){
				
				if(battle_window.closed){
					start_bgm();
					return;
				}
				  
				setTimeout(pollForWindowClosure, 2000);
			}
			
			function start_bgm() {
				/*if(document.getElementById("bgm")) {
					//EP_play('ep_player1');
					niftyplayer('niftyPlayer1').play();
				}*/
				/*if(document.getElementById("bgm")) {
					if(bgm_object == null)
						bgm_object = play_bgm('bgm', './rpg/sound/mp3/field1.mp3');
					else {
						loop_sound(bgm_object);
					}
				}*/
				
				if(document.getElementById("bgm")) {
					var a = document.getElementById('bgm_audio');
					if(a) {
						a.load();
						a.play();
					}
				}
			}
			
			/*function stop_bgm() {
				if(document.getElementById("bgm")) {
					//EP_stop('ep_player1');
					niftyplayer('niftyPlayer1').stop();
				}
					
			}*/
			
			function stop_bgm() {
				if(document.getElementById("bgm")) {
					var a = document.getElementById('bgm_audio');
					if(a) {
						a.pause();
						a.currentTime = 0;
					}
				}
				/*if(document.getElementById("bgm")) {
					//EP_stop('ep_player1');
					//niftyplayer('niftyPlayer1').stop();
					stop_sound('bgm');
				}*/
				/*if(document.getElementById("bgm"))
					document.getElementById("bgm").innerHTML = '';*/
			}
			
			/*function init_bgm() {
				niftyplayer('niftyPlayer1').registerEvent('onSongOver', 'niftyplayer(\'niftyPlayer1\').play()');
				niftyplayer('niftyPlayer1').play();
			}*/
			
			function center_display() {
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				document.getElementById('main_div').style.width = "905px";
				document.getElementById('main_div').style.height = "696px";
				document.getElementById('main_div').style.left = "50%";
				document.getElementById('main_div').style.top = "50%";
				document.getElementById('main_div').style.marginLeft = "-452px";
				document.getElementById('main_div').style.marginTop = "-348px";
				document.getElementById('main_div').style.display = "block";
				
				if(larg < 905) {
					document.getElementById('main_div').style.left = "0";
					document.getElementById('main_div').style.marginLeft = "0px";
				}
				if(haut < 696) {
					document.getElementById('main_div').style.top = "0";
					document.getElementById('main_div').style.marginTop = "0px";
				}
				
				if(current_area != false) {
					show_area(current_area);
				}
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');javascript:center_display()" onresize="javascript:center_display()">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<!-- BEGIN background_music -->
		<div id="bgm">
			<audio id="bgm_audio" autoplay loop>
				<source src="./rpg/sound/mp3/field1.mp3" type="audio/mpeg">
				<source src="./rpg/sound/ogg/field1.ogg" type="audio/ogg">
			</audio>
		</div>
		<!-- END background_music -->
		
		<a id="back" href="{BACK_LINK}">Retour au forum</a>
		
		<div id="tooltip">
		</div>
		
		<div id="main_div">
			<img id="map" src="images/rpg/map/map.{SD_EXT}" alt="map" />
			<a id="east_coast" href="javascript:show_area('east_coast')" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Les côtes de l'est</strong><br>{EAST_COAST_DESC}<br><span style='font-style:italic'>Niveau minimum conseillé : {EAST_COAST_LEVEL}</span>"></a>
			<a id="white_desert" href="javascript:show_area('white_desert')" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le désert blanc</strong><br>{WHITE_DESERT_DESC}<br><span style='font-style:italic'>Niveau minimum conseillé : {WHITE_DESERT_LEVEL}</span>"></a>
			<a id="dry_desert" href="javascript:show_area('dry_desert')" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le désert aride</strong><br>{DRY_DESERT_DESC}<br><span style='font-style:italic'>Niveau minimum conseillé : {DRY_DESERT_LEVEL}</span>"></a>
			<a href="javascript:show_area('termorr')"><img id="termorr_mines" src="images/rpg/map/{SD_DIR}pink_square.{SD_EXT}" alt="pink_square" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Les mines de Termorr</strong><br>{TERMORR_MINES_DESC}<br><span style='font-style:italic'>Niveau minimum conseillé : {TERMORR_MINES_LEVEL}</span>"/></a>
			
			<a id="castle" href="viewforum.php?sid={SID}&f=11"><img src="images/rpg/map/{SD_DIR}red_dot.{SD_EXT}" alt="red_dot" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Château Impérial</strong><br>QG de l'Empire."/></a>
			<a id="imperial_city" href="viewforum.php?sid={SID}&f=12"><img src="images/rpg/map/{SD_DIR}red_dot.{SD_EXT}" alt="red_dot" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Cité Judiciaire</strong><br>Une cité magnifique, reflet de la puissance de l'empire."/></a>
			<a id="esperia" href="viewforum.php?sid={SID}&f=17"><img src="images/rpg/map/{SD_DIR}red_dot.{SD_EXT}" alt="red_dot" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Esperia</strong><br>QG des Révolutionnaires."/></a>
			
			<img id="desert_town" src="images/rpg/map/{SD_DIR}pink_square.{SD_EXT}" alt="pink_square" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Inclusio</strong><br>Une ville dans les glaces."/>
			<a id="forest_town" href="viewforum.php?sid={SID}&f=19"><img src="images/rpg/map/{SD_DIR}pink_square.{SD_EXT}" alt="pink_square" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Litoreus</strong><br>QG du Cartel."/></a>
			<img id="ice_town" src="images/rpg/map/{SD_DIR}pink_square.{SD_EXT}" alt="pink_square"  onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Ixia</strong><br>Une ville dans le désert."/>
		</div>
		
		<div id="east_coast_zoom">
			<a style="color:white" href="javascript:show_map();hide_area('east_coast');">Revenir à la carte</a>
			<img src="images/rpg/map/{SD_DIR}areas/zoom/east_coast_zoom.{SD_EXT}"/>
			<a class="area_name" id="east_coast_part1" href="javascript:start_battle(1,1)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Verte-Plage</strong><br>Niveau {EAST_COAST_PART1_MIN_LVL}-{EAST_COAST_PART1_MAX_LVL}">Verte-Plage</a>
			<a class="area_name" id="east_coast_part2" href="javascript:start_battle(1,2)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le Grand Sentier</strong><br>Niveau {EAST_COAST_PART2_MIN_LVL}-{EAST_COAST_PART2_MAX_LVL}"><div style="margin-top:100px">Le Grand Sentier</div></a>
			<a class="area_name" id="east_coast_part3" href="javascript:start_battle(1,3)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Les Epineuses</strong><br>Niveau {EAST_COAST_PART3_MIN_LVL}-{EAST_COAST_PART3_MAX_LVL}"><div style="margin-top:60px">Les Epineuses</div></a>
			<a class="area_name" id="east_coast_part4" href="javascript:start_battle(1,4)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Sombreforêt</strong><br>Niveau {EAST_COAST_PART4_MIN_LVL}-{EAST_COAST_PART4_MAX_LVL}">Sombreforêt</a>
		</div>
		
		<div id="white_desert_zoom">
			<a style="color:white" href="javascript:show_map();hide_area('white_desert');">Revenir à la carte</a>
			<img src="images/rpg/map/{SD_DIR}areas/zoom/white_desert_zoom.{SD_EXT}"/>
			<a class="area_name" id="white_desert_part1" href="javascript:start_battle(2,5)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le Grand Pic</strong><br>Niveau {WHITE_DESERT_PART1_MIN_LVL}-{WHITE_DESERT_PART1_MAX_LVL}"><div style="display:inline-block;margin-left:30px">Le Grand Pic</div></a>
			<a class="area_name" id="white_desert_part2" href="javascript:start_battle(2,6)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>La Plaine aux Miroirs</strong><br>Niveau {WHITE_DESERT_PART2_MIN_LVL}-{WHITE_DESERT_PART2_MAX_LVL}"><div style="display:inline-block;margin-top:80px">La Plaine aux Miroirs</div></a>
			<a class="area_name" id="white_desert_part3" href="javascript:start_battle(2,7)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Blanche-Vallée</strong><br>Niveau {WHITE_DESERT_PART3_MIN_LVL}-{WHITE_DESERT_PART3_MAX_LVL}"><div style="margin-top:80px;margin-left:50px">Blanche-Vallée</div></a>
			<a class="area_name" id="white_desert_part4" href="javascript:start_battle(2,8)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le Col du Grand-Froid</strong><br>Niveau {WHITE_DESERT_PART4_MIN_LVL}-{WHITE_DESERT_PART4_MAX_LVL}"><div style="margin-top:40px">Le Col du Grand-Froid</div></a>
		</div>
		
		<div id="dry_desert_zoom">
			<a style="color:white" href="javascript:show_map();hide_area('dry_desert');">Revenir à la carte</a>
			<img src="images/rpg/map/{SD_DIR}areas/zoom/dry_desert_zoom.{SD_EXT}"/>
			<a class="area_name" id="dry_desert_part1" href="javascript:start_battle(3,9)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>La Plaine aux Mirages</strong><br>Niveau {DRY_DESERT_PART1_MIN_LVL}-{DRY_DESERT_PART1_MAX_LVL}"><div style="margin-top:150px;margin-left:10px">La Plaine aux Mirages</div></a>
			<a class="area_name" id="dry_desert_part2" href="javascript:start_battle(3,10)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le Couloir</strong><br>Niveau {DRY_DESERT_PART2_MIN_LVL}-{DRY_DESERT_PART2_MAX_LVL}"><div style="margin-top:50px;margin-left:10px">Le Couloir</div></a>
			<a class="area_name" id="dry_desert_part3" href="javascript:start_battle(3,11)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Les Grandes Dunes</strong><br>Niveau {DRY_DESERT_PART3_MIN_LVL}-{DRY_DESERT_PART3_MAX_LVL}"><div style="margin-top:85px;margin-left:-65px">Les Grandes<br>Dunes</div></a>
			<a class="area_name" id="dry_desert_part4" href="javascript:start_battle(3,12)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Chaude-Brise</strong><br>Niveau {DRY_DESERT_PART4_MIN_LVL}-{DRY_DESERT_PART4_MAX_LVL}"><div style="margin-top:25px">Chaude-Brise</div></a>
		</div>
		
		<div id="termorr_zoom">
			<a style="color:white" href="javascript:show_map();hide_area('termorr');">Revenir à la carte</a>
			<img src="images/rpg/map/{SD_DIR}areas/zoom/termorr_zoom.{SD_EXT}"/>
			<a class="area_name" style="color:rgb(60,222,87)" id="termorr_part1" href="javascript:start_battle(4,13)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Entrée de la Mine</strong><br>Niveau {TERMORR_PART1_LVL}"><div style="margin-top:25px;margin-left:100px">Entrée de la Mine</div></a>
			<a class="area_name" style="color:rgb(60,222,87)" id="termorr_part2" href="javascript:start_battle(4,14)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Les Galeries</strong><br>Niveau {TERMORR_PART2_LVL}"><div style="margin-top:-30px;margin-left:80px">Les Galeries</div></a>
			<a class="area_name" style="color:rgb(60,222,87)" id="termorr_part3" href="javascript:start_battle(4,15)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Le Lac Mystique</strong><br>Niveau {TERMORR_PART3_LVL}"><div style="margin-top:120px;margin-left:-75px">Le Lac Mystique</div></a>
			<a class="area_name" style="color:rgb(60,222,87)" id="termorr_part4" href="javascript:start_battle(4,16)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="<strong>Ruines Proethians</strong><br>Niveau {TERMORR_PART4_LVL}">Ruines Proethians</a>
		</div>
	</body>
</html>