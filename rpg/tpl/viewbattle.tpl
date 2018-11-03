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

		<title>Exitium - Combat</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewbattle{SD_CSS}.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/tooltip.css" />
		<link rel="stylesheet" type="text/css" href="rpg/css/battleanims.css" />
		
		
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="rpg/js/imageutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/url.js"></script>
		<!--script type="text/javascript" src="soundmanager/script/soundmanager2.js"></script>
		<script type="text/javascript" src="rpg/js/sound.js"></script-->
		
		<!--script type="text/javascript">
		soundManager.setup({
		  url: '/soundmanager/swf/',
		  // optional: use 100% HTML5 mode where available
		  preferFlash: false,
		  waitForWindowLoad: true,
		  onready: function() {
			
		  },
		  ontimeout: function() {
			// Hrmm, SM2 could not start. Missing SWF? Flash blocked? Show an error, etc.?
		  }
		});
		</script-->
		<script type="text/javascript" src="rpg/js/animations.js"></script>
		<script type="text/javascript" src="rpg/js/{JAVASCRIPT_FILE}.js"></script>
		

		<script type="text/javascript">
			var mode = '';
			var bgm_path = '';
			
			var can_perform_action = true;
			
			var close_time = 4000;
			
			var update = true;
			var battle_over = false;
			var update_time = 500;
			var update_timer = null;
		
			function resize_window() {
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				/*document.body.style.width = larg.toString() + "px";*/
				document.body.style.height = haut.toString() + "px";
				document.getElementById('main').style.width = document.body.clientWidth.toString() + "px";
				document.getElementById('main').style.height = document.body.clientHeight.toString() + "px";
				
				document.getElementById('player_div').style.width = (document.body.clientWidth / 2).toString() + "px";
				document.getElementById('player_div').style.height = document.body.clientHeight.toString() + "px";
				
				document.getElementById('opponent_div').style.width = (document.body.clientWidth / 2).toString() + "px";
				document.getElementById('opponent_div').style.height = document.body.clientHeight.toString() + "px";
			}
			
			function set_buff_data(id, data) {
				var buff_atk = data['buff_atk'];
				var buff_def = data['buff_def'];
				var buff_spd = data['buff_spd'];
				var buff_flux = data['buff_flux'];
				var buff_res = data['buff_res'];
				var buff_crit = data['buff_crit'];
				
				var inner = '';
				if(buff_atk != undefined && buff_atk != 0) inner = inner + "<p>Attaque " + (buff_atk > 0 ? "+" : "-") + buff_atk + "</p>";
				if(buff_def != undefined && buff_def != 0) inner = inner + "<p>Défense " + (buff_def > 0 ? "+" : "-") + buff_def + "</p>";
				if(buff_spd != undefined && buff_spd != 0) inner = inner + "<p>Vitesse " + (buff_spd > 0 ? "+" : "-") + buff_spd + "</p>";
				if(buff_flux != undefined && buff_flux != 0) inner = inner + "<p>Flux " + (buff_flux > 0 ? "+" : "-") + buff_flux + "</p>";
				if(buff_res != undefined  && buff_res != 0) inner = inner + "<p>Résistance " + (buff_res > 0 ? "+" : "-") + buff_res + "</p>";
				if(buff_crit != undefined  && buff_crit != 0) inner = inner + "<p>Critique " + (buff_crit > 0 ? "+" : "-") + buff_crit + "%" + "</p>";
				
				document.getElementById(id).innerHTML = inner;
			}
			
			function set_player_data(type, data) {
				if(type == 'hp') {
					document.getElementById('player_hp').innerHTML = 'PV - ' + data[0] + '/' + data[1];
					clip_image(document.getElementById('player_hp_img'),0,202 * (data[0]/data[1]),10,0);
				}
				if(type == 'fp') {
					document.getElementById('player_fp').innerHTML = 'PF - ' + data[0] + '/' + data[1];
					clip_image(document.getElementById('player_fp_img'),0,202 * (data[0]/data[1]),10,0);
				}
			}
			
			function set_opponent_data(type, data) {
				if(type == 'hp') {
					clip_image(document.getElementById('opponent_hp_img'),0,202 * (data[0]/data[1]),10,0);
				}
				if(type == 'fp') {
					clip_image(document.getElementById('opponent_fp_img'),0,202 * (data[0]/data[1]),10,0);
				}
			}

			function set_general_data(type, data) {
				if(type == 'turn') {
					document.getElementById('battle_turn').innerHTML = 'Tour ' + data;
				}
				else if(type == 'msg_box') {
					document.getElementById('msg_box').innerHTML = '<span style="color:rgb(255,128,128)"><strong>Tour ' + data[0] + '</strong></span><br>' + data[1] + document.getElementById('msg_box').innerHTML;
				}
				else if(type == 'pvp_counter') {
					document.getElementById('pvp_counter').innerHTML = data;
				}
			}

			function read_json(j) {
				var undef;
				
				//alert(j);
				
				j = j.split('<!--');
				var json = JSON.parse(j[0]);
				for(k in json) {
					/* general info */
					if(k == 'general') {
						var turn = json[k]['turn'];
						var msg_box = json[k]['msg_box'];
						var pvp_info = json[k]['pvp_info'];
						var battle_time = json[k]['battle_time'];
						
						if(turn !== undef) set_general_data('turn', turn);
						if( (turn !== undef) && (msg_box !== undef) ) set_general_data('msg_box', [turn - 1, msg_box]);
						if(pvp_info !== undef) show_pvp_info(pvp_info);
						if(battle_time !== undef) set_general_data('pvp_counter', battle_time);
					}
					/* player info */
					else if(k == 'player1') {
						var jingle = json[k]['jingle'];
						var dead = json[k]['dead'];
						var hp = json[k]['hp'];
						var max_hp = json[k]['max_hp'];
						var fp = json[k]['fp'];
						var max_fp = json[k]['max_fp'];
						var run = json[k]['run'];
						
						var anim_path = json[k]['anim_path'];
						var anim_time = json[k]['anim_time'];
						var anim_width = json[k]['anim_width'];
						var anim_height = json[k]['anim_height'];
						var anim_frames = json[k]['anim_frames'];
						var anim_delay = json[k]['anim_delay'];
						var anim_sound = json[k]['anim_sound'];
						
						var buffs = json[k]['buffs'];
						if(buffs != undefined) set_buff_data('character_buffs', buffs);
						
						if( hp != undefined && max_hp != undefined ) set_player_data('hp', [hp, max_hp]);
						if( fp != undefined && max_fp != undefined ) set_player_data('fp', [fp, max_fp]);
						
						
						
						if(dead || run) {
							//if(update) update = false;
							battle_over = true;
							
							setTimeout(function() {custom_close(); }, close_time);
						}
						
						if( anim_path != undefined && anim_time != undefined && anim_width != undefined && anim_height != undefined && anim_frames != undefined && anim_delay != undefined)
							//play_animation2('player_anim', anim_path, anim_frames, anim_delay, anim_width, anim_height);
							play_animation5('player_anim_div', anim_path, anim_frames, anim_delay, anim_width, anim_height);
						
						if(anim_sound != undefined)
							//play_sound(anim_sound, anim_sound);
							play_effect(anim_sound, 'player1_audio');
							
						if(jingle != undefined) {
							stop_bgm();
							
							//play_sound(jingle, jingle);
							var _jingle = document.getElementById('jingle_audio');
							if(_jingle) {
								if(_jingle.canPlayType('audio/mpeg') != '')
									_jingle.src = 'rpg/sound/mp3/' + jingle + '.mp3';
								else if(_jingle.canPlayType('audio/ogg') != '')
									_jingle.src = 'rpg/sound/ogg/' + jingle + '.ogg';
								
								if(_jingle.src) {
									_jingle.load();
									_jingle.play(); 
								}
							}
							
						}
					}
					/* monster info */
					else if(k == 'player2') {
						var dead = json[k]['dead'];
						var hp = json[k]['hp'];
						var max_hp = json[k]['max_hp'];
						var fp = json[k]['fp'];
						var max_fp = json[k]['max_fp'];
						var run = json[k]['run'];
						
						var anim_path = json[k]['anim_path'];
						var anim_time = json[k]['anim_time'];
						var anim_width = json[k]['anim_width'];
						var anim_height = json[k]['anim_height'];
						var anim_frames = json[k]['anim_frames'];
						var anim_delay = json[k]['anim_delay'];
						var anim_sound = json[k]['anim_sound'];
						
						var buffs = json[k]['buffs'];
						if(buffs != undefined) set_buff_data('opponent_buffs', buffs);
						
						if( hp != undefined && max_hp != undefined) set_opponent_data('hp', [hp, max_hp]);
						if( fp != undefined && max_fp != undefined) set_opponent_data('fp', [fp, max_fp]);
						
						if(dead || run) {
							//if(update) update = false;
							battle_over = true;
							
							if(mode == 'event' && dead && !run) {
								setTimeout(function() { location.reload(); }, close_time);
							}
							else {
								setTimeout(function() {custom_close(); }, close_time);
							}
						}
						
						if( anim_path != undefined && anim_time != undefined && anim_width != undefined && anim_height != undefined && anim_frames != undefined && anim_delay != undefined )
						{	
							//play_animation2('opponent_anim', anim_path, anim_frames, anim_delay, anim_width, anim_height);
							play_animation5('opponent_anim_div', anim_path, anim_frames, anim_delay, anim_width, anim_height);
						}
						if(anim_sound != undefined)
							//play_sound(anim_sound, anim_sound);
							play_effect(anim_sound, 'player2_audio');
					}
					
				}
			}
			
			function close_menu() {
				document.getElementById('menu').style.visibility = 'hidden';
				can_perform_action = true;
			}

			function scrollDiv(divId, depl) {
			   var scroll_container = document.getElementById(divId);
			   scroll_container.scrollTop -= depl;
			}

			function open_item_menu(event) {
				if(!can_perform_action) return;
				
				can_perform_action = false;
				
				//create ajax object to request menu according to item
				var xhr = getXMLHttpRequest();

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
						//response from server
						if(string_starts_with(xhr.responseText, "not_connected")) {
							alert('Vous n\'êtes pas connecté !');
							can_perform_action = true;
						} else if(string_starts_with(xhr.responseText, "error")) {
							alert('Une erreur est survenue.');
							can_perform_action = true;
						} else {
							//moveElementAtMousePosition('menu', event, 0, 0);
							document.getElementById('menu').style.left = "225px";
							document.getElementById('menu').style.top = "20px";
							document.getElementById('menu').innerHTML = xhr.responseText;
							document.getElementById('menu').style.visibility = 'visible';
						}
					} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
						alert('Une erreur est survenue lors du traitement de la requête.');
						can_perform_action = true;
					}
				};

				xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=item_menu", true);
				xhr.send(null);
			}
			
			function show_pvp_info(message) {
				if(document.getElementById('pvp_info')) {
					document.getElementById('pvp_info').innerHTML = message;
					document.getElementById('pvp_info').style.display = "block";
				}
			}
			
			function hide_pvp_info() {
				if(document.getElementById('pvp_info'))
					document.getElementById('pvp_info').style.display = "none";
			}
			
			function start_bgm() {
				if(document.getElementById("bgm")) {
					var a = document.getElementById('bgm_audio');
					if(a) {
						a.play();
					}
				}
			}
			
			function stop_bgm() {
				if(document.getElementById("bgm")) {
					var a = document.getElementById('bgm_audio');
					if(a) {
						a.pause();
						a.currentTime = 0;
					}
				}
			}
			
			function play_effect(effect, audio_id) {
				
				var _effect = document.getElementById(audio_id);
				if(!_effect) return;
				
				if(_effect.canPlayType('audio/mpeg') != '') 
					_effect.src = effect + '.mp3';
				else if(_effect.canPlayType('audio/ogg') != '')
					_effect.src = effect + '.ogg';
				
				if(_effect.src) {
					_effect.load();
					_effect.play(); 
				}
			}
			
			function custom_close() {
				var bgm = document.getElementById('bgm');
				if(bgm) {
					var jingle = document.getElementById('jingle_audio');
					jingle.pause();
					jingle.src = '';
					var p1sound = document.getElementById('player1_audio');
					p1sound.pause();
					p1sound.src = '';
					var p2sound = document.getElementById('player2_audio');
					p2sound.pause();
					p2sound.src = '';
					
					bgm.innerHTML = '';
				}
				
				window.close();
			}
			
		</script>
	</head>

	<body onload="javascript:init_battle();javascript:set_sid('{SID}')">
		
		<!-- BEGIN background_music -->
		<div id="bgm">		
			<audio id="bgm_audio" autoplay loop>
				<source src="./rpg/sound/mp3/{background_music.BGM}.mp3" type="audio/mpeg">
				<source src="./rpg/sound/ogg/{background_music.BGM}.ogg" type="audio/ogg">
			</audio>
			<audio id="jingle_audio"></audio>
			<audio id="player1_audio"></audio>
			<audio id="player2_audio"></audio>
		</div>
		<!-- END background_music -->
		
		<div id="main">
			<div id="tooltip" style="z-index:201"></div>
			<div id="menu">
				<!-- menu affiché par AJAX -->
			</div>
			
			<table>
				<tr>
					<!-- player -->
					<td id="player_div">
						<table style="margin-left:15px">
							<tr>
								<td width="200" height="300">
									<div class="anim_multilayer" >
										<img class="avatar layer1" id="character_avatar" alt="" src="{USER_AVATAR}"/>
										<!--img id="player_anim" class="battle_anim layer2" style="border:none" alt=""/-->
										<div id="player_anim_div" class="battle_anim layer2"></div>
										<div style="z-index:3;color:white;background-color:blue;opacity:0.5">
											<span id="character_buffs" style="opacity:1">
												<!-- buffs affiché par AJAX -->
											</span>
										</div>
									</div>
								</td>
								<td valign="bottom">
									<div id="character_orbs">
										<!-- BEGIN orbs_bloc -->
										<img {orbs_bloc.ORB_TOOLTIP} id="character_orb{orbs_bloc.ORB_NB}" alt="" src="{orbs_bloc.ORB_IMG}" />
										<!-- END orbs_bloc -->
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="player_bars" style="font-family: Calibri, Arial">
										<div class="bars_multilayer" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Les points de vie de votre personnage.<br>Lorsqu'ils tombent à 0, votre personnage ne peut plus combattre.">
											<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty.{SD_EXT}"/>
											<img id="player_hp_img" onload="clip_image(this,0,202 * ({USER_HP}/{USER_MAX_HP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/PV.{SD_EXT}"/>
										</div>
										<p id="player_hp" style="display:inline;margin:0;padding:0">PV - {USER_HP}/{USER_MAX_HP}<p>
										<div class="bars_multilayer" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Les points de flux de votre personnage.<br>Nécessaire pour utiliser les skills.">
											<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty.{SD_EXT}"/>
											<img id="player_fp_img" onload="clip_image(this,0,202 * ({USER_FP}/{USER_MAX_FP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/PF.{SD_EXT}"/>
										</div>
										<p id="player_fp" style="display:inline;margin:0;padding:0">PF - {USER_FP}/{USER_MAX_FP}<p>
										<div class="bars_multilayer" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Les points d'expérience de votre personnage.<br>Gagnez des points d'expérience pour augmenter de niveau et devenir plus fort.">
											<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty2.{SD_EXT}"/>
											<img onload="clip_image(this,0,202 * ({USER_XP}/{USER_MAX_XP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/XP.{SD_EXT}"/>
										</div>
										<p style="display:inline;margin:0;padding:0">XP - {USER_XP}/{USER_MAX_XP}<p>
									</div>
								</td>
							</tr>
							<tr>
								<td width="150" align="left" valign="center">
									<div id="character_stats" style="margin-bottom:5px;font-family: Calibri, Arial">
										<p onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Augmente les dégâts physiques, la précision et les coups critiques.">Attaque : {USER_ATTACK}</p>
										<p onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Augmente la résistance aux dégâts, l'esquive critique et les points de vie.">Défense : {USER_DEFENSE}</p>
										<p onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Augmente l'esquive et un peu la précision et la précision magique.">Vitesse : {USER_SPEED}</p>
										<p onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Augmente les dégâts magiques, la précision magique, la puissance des skills et les coups critiques.">Flux : {USER_FLUX}</p>
										<p onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Augmente la résistance aux dégâts magiques, l'esquive critique et les points de flux.">Résistance : {USER_RESISTANCE}</p>
									</div>
								</td>
								<td>
									<div id="character_level">
										<img alt="" src="images/rpg/battle/{SD_DIR}character_level.{SD_EXT}"/>
										<p id="character_level_display">{USER_LEVEL}</p>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="character_actions" style="font-family: Calibri, Arial;font-size:16px;display:inline-block;margin-left:5px;text-align:left">
										<p onclick="javascript:attack()" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Attaque avec l'arme de base." style="position:relative;top:10px;left:10px;width:50px">Attaquer</p>
										<p onclick="javascript:defend()" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Réduit les dégâts de la prochaine attaque par 2." style="position:relative;top:30px;left:10px;width:50px">Défendre</p>
										<p onclick="javascript:open_skill_menu(event)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Utiliser une compétence." style="position:relative;top:50px;left:10px;width:50px">Skills</p>
										<!-- BEGIN items_allowed_bloc -->
										<p onclick="javascript:open_item_menu(event)" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Utiliser un objet." style="position:relative;top:-30px;left:170px;width:50px">Objets</p>
										<!-- END items_allowed_bloc -->
										<!-- BEGIN items_not_allowed_bloc -->
										<p style="position:relative;top:-30px;left:170px;width:50px;color:grey;cursor:auto">Objets</p>
										<!-- END items_not_allowed_bloc -->
										<!-- BEGIN run_allowed_bloc -->
										<p onclick="javascript:run()" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Fuir le combat." style="position:relative;left:170px;width:50px">Fuir</p>
										<!-- END run_allowed_bloc -->
										<!-- BEGIN run_not_allowed_bloc -->
										<p style="position:relative;left:170px;width:50px;color:grey;cursor:auto">Fuir</p>
										<!-- END run_not_allowed_bloc -->
									</div>
								</td>
							</tr>
						</table>
					</td>
					<!-- opponent -->
					<td id="opponent_div">
						<div id="opponent_name" style="font-family:'Advanced Dot Digital-7', 'Ace Futurism', Arial;font-size:22px;color:rgb(220,220,220)">{OPPONENT_NAME}</div>
						<div class="anim_multilayer" style="width:200px;height:300px;border:1px inset white;margin:auto;background-image:url('{OPPONENT_BACKGROUND}')">
							<img class="avatar layer1" id="opponent_avatar" src="{OPPONENT_AVATAR}" onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{OPPONENT_INFO}"/>
							<!--img id="opponent_anim" class="battle_anim layer2" style="border:none" alt=""/-->
							<div id="opponent_anim_div" class="battle_anim layer2"></div>
							<div style="z-index:3;color:white;background-color:blue;opacity:0.5">
								<span id="opponent_buffs" style="opacity:1">
									<!-- buffs affiché par AJAX -->
								</span>
							</div>
						</div>
						<div id="opponent_bars">
							<div class="bars_multilayer">
								<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty.{SD_EXT}"/>
								<img id="opponent_hp_img" onload="clip_image(this,0,202 * ({OPPONENT_HP}/{OPPONENT_MAX_HP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/PV.{SD_EXT}"/>
							</div>
							<div class="bars_multilayer">
								<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty.{SD_EXT}"/>
								<img id="opponent_fp_img"  onload="clip_image(this,0,202 * ({OPPONENT_FP}/{OPPONENT_MAX_FP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/PF.{SD_EXT}"/>
							</div>
						</div>
						<!-- message box -->
						<div style="height:179px;">
							<div id="msg_box"></div>
							<div style="width:50px;height:100px;position:relative;top:-135px;left:330px">
								<a href="javascript:scrollDiv('msg_box',30)"><img src="images/rpg/battle/{SD_DIR}Monter.{SD_EXT}"/></a>
								<a href="javascript:scrollDiv('msg_box',-30)"><img src="images/rpg/battle/{SD_DIR}Descendre.{SD_EXT}"/></a>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<!-- BEGIN pve_counter_bloc -->
			<div id="counter">
				<span id="battle_turn">Tour {BATTLE_TURN}</span>
			</div>
			<!-- END pve_counter_bloc -->
			<!-- BEGIN pvp_counter_bloc -->
			<div id="counter_pvp">
				<span id="battle_turn" style="display:inline-block;margin-top:40px">Tour {BATTLE_TURN}</span>
				<br><span id="pvp_counter" style="margin-top:60px"></span>
			</div>
			<!-- END pvp_counter_bloc -->
			<div id="pvp_info"></div>
		</div>
	</body>
</html>