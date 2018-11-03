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

		<title>Exitium - Profil de {USER_STATUS}</title>
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewplayer{SD_CSS}.css" />
		<script type="text/javascript" src="rpg/js/windows.js"></script>
		<script type="text/javascript">
			function center_display() {
				var larg = 0;
				var haut = 0;
				
				larg = (window.innerWidth);
				haut = (window.innerHeight);
				
				document.getElementById('status_div').style.margin = "auto";
				
				if(larg > 1359) {
					document.getElementById('status_div').style.left = "50%";
					document.getElementById('status_div').style.marginLeft = "-679px";
				}
				else {
					document.getElementById('status_div').style.left = "0";
				}
				
				if(haut > 618) {
					document.getElementById('status_div').style.top = "50%";
					document.getElementById('status_div').style.marginTop = "-309px";
				}
				else {
					document.getElementById('status_div').style.top = "5px";
				}
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');center_display()" onresize="center_display()">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<!-- BEGIN background_music -->
		<object type="application/x-shockwave-flash" data="./dewplayer/dewplayer.swf?mp3=./rpg/sound/mp3/Status.mp3&amp;autostart=1&amp;autoreplay=true" width="0" height="0"></object>
		<!-- END background_music -->
		
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
									<td rowspan="2"><img id="character_avatar" alt="" src="{USER_AVATAR}" /></td>
									<td colspan="2">
											<div id="character_equipment">
												<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}equipment_bg.{SD_EXT}"/>
												<span id="character_equipment_text" class="layer2">
														<span>Arme : {USER_WEAPON}</span><br>
														<span>Haut : {USER_CLOTH}</span><br>
														<span>Bas : {USER_LEGGINGS}</span><br>
														<span>Gants : {USER_GLOVES}</span><br>
														<span>Bottes : {USER_SHOES}</span><br>
												</span>
											</div>
									</td>
								</tr>
								
								<tr>
									<td align="center" valign="center">
										<div style="margin-left:-50px;height:133px">
											<img style="margin-top:10px" alt="" src="images/rpg/status/{SD_DIR}character_level.{SD_EXT}"/>
											<p id="character_level_display">{USER_LEVEL}</p>
										</div>
									</td>
								</tr>
								
								<!--tr valign="middle">
									<td colspan="2" width="400px">
										<div id="character_state">
											
										</div>
										
									</td>
									
									
									<td>
										<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Vos points de karma. Confèrent un bonus d'expérience.<br>Faites du RP pour gagner plus d'expérience !" style="margin:auto;text-align:center">
											<p style="display:inline;margin:0;padding:0">Karma - Bonus {USER_KARMA_BONUS}%<p>
											<!-- BEGIN karma_bloc -->
											<!--img alt="" src="images/rpg/status/icons/{karma_bloc.KARMA_IMAGE}.png"/-->
											<!-- END karma_bloc -->
										<!--/div>
									</td>
								</tr-->
								
								
								<!--tr>
									<td colspan="3" align="center">
										<table cellspacing="20" style="margin-top:-30px">
											<tr class="character_status_window_text">
												<td valign="center">
													<div id="character_stats">
														
													</div>
												</td>
												
												<td valign="center">
													<div id="character_skills">
														
													</div>
												</td>
												
												<td valign="top">
													<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Votre niveau. Gagnez des points d'expérience pour passer au niveau supérieur et devenir plus fort." id="character_level">
														<img style="margin-top:40px" alt="" src="images/rpg/status/character_level.png"/>
														<p id="character_level_display">{USER_LEVEL}</p>
													</div>
												</td>
												
											</tr>
										</table>
									</td>
								</tr-->
								
								<!--tr>
									<td colspan="3" valign="center" align="center">
										
										<div id="character_inventory">
												
										</div>
									</td>
								</tr-->
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
										<div id="user_main_info">
											<div style="display:inline-block;margin-left:20px;line-height:28px">
												<p>Pseudo : {USERNAME}</p>
												<p>Age : {USER_AGE}</p>
												<p>Sexe : {USER_GENDER}</p>
												<p>Lieu : {USER_FROM}</p>
												<p>Organisation : {USER_ORGANISATION}</p>
												<p>Rang : {USER_RANK}</p>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td valign="center" align="center">
										<div style="display:inline-block">
											<p style="margin-left:-250px;font-size:20px"><a style="color:white;text-decoration:none" href="{USER_INTRO_LINK}">Fiche de présentation</a></p>
										</div>
									</td>
								</tr>
								<tr>
									<td align="center" valign="center">
										<div id="user_forum_info">
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
						
					</td>
				</tr>
				
			</table>
			
		</div>
		
	</body>
</html>