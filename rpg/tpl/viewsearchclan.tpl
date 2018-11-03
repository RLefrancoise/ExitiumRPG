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
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewsearchclan{SD_CSS}.css" />
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript">
			function close_window() {
				parent.document.getElementById('main_menu').style.visibility = 'visible';
				parent.document.getElementById('iframe').style.visibility = 'hidden';
				parent.document.getElementById('iframe').width = "0px";
				parent.document.getElementById('iframe').height = "0px";
				parent.hide_message();
			}
			
			function set_size() {
				parent.document.getElementById('iframe').width = "881px";
				parent.document.getElementById('iframe').height = "802px";
			}
			
			function scrollDiv(divId, depl) {
			   var scroll_container = document.getElementById(divId);
			   scroll_container.scrollTop -= depl;
			}
			
			function go_to_clan(id) {
				parent.location.href = 'viewclanpage.php?sid=' + SID + '&id=' + id;
			}
			
			function search() {
				var xhr = getXMLHttpRequest();
		 
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						if(xhr.status == 200 || xhr.status == 0) {
							if(string_starts_with(xhr.responseText, "not_connected")) {
								alert('Vous n\'êtes pas connecté !');
							}
							else if(string_starts_with(xhr.responseText, "error")) {
								alert('Une erreur est survenue !');
							}
							else {
								document.getElementById("clan_list_display").innerHTML = xhr.responseText;
							}
						}
						else {
							alert("XHR a échoué à traiter la requête.");
						}
					}
				};
				
				var name = encodeURIComponent(document.getElementById("search_input").value);
				
				xhr.open("GET", "searchclan.php?sid=" + SID + "&name=" + name, true);
				xhr.send(null);
			}
		</script>
	</head>

	<body onload="set_size();javascript:set_sid('{SID}')">
		<table id="search_table">
			<tr>
				<td align="left" valign="center">
					<table>
						<tr>
							<td align="center" valign="center"><img style="margin-left:30px;margin-top:10px" src="images/rpg/clans/search/{SD_DIR}search_text.{SD_EXT}"/></td>
							<td align="center" valign="center"><input id="search_input" type="text" name="search_name" size="28"/></td>
							<td align="center" valign="center"><a href="javascript:search()"><img src="images/rpg/clans/search/{SD_DIR}buttons/ok_button.{SD_EXT}"/></a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" valign="center" height="19">
					<table>
						<tr>
							<td align="left" valign="center" width="250"><img src="images/rpg/clans/search/{SD_DIR}name_text.{SD_EXT}"/></td>
							<td align="center" valign="center" width="250"><img src="images/rpg/clans/search/{SD_DIR}level_text.{SD_EXT}"/></td>
							<td align="right" valign="center" width="250"><img src="images/rpg/clans/search/{SD_DIR}members_text.{SD_EXT}"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" valign="center" height="505">
					<div id="clan_list">
						<div id="clan_list_display">
							
							<table cellspacing="20">
								<!-- BEGIN clan_list -->
								<tr style="margin-top:20px;margin-bottom:20px">
									<td align="left" valign="center" width="269"><a class="clan_link" href="javascript:go_to_clan({clan_list.CLAN_ID})">{clan_list.CLAN_NAME}</a></td>
									<td align="center" valign="center"  width="269">{clan_list.CLAN_LEVEL}</td>
									<td align="right" valign="center"  width="269"><span style="margin-right:30px">{clan_list.CLAN_MEMBERS_NUMBER}</span></td>
								</tr>
								<!-- END clan_list -->
							</table>
							
						</div>
						<div style="height:50px;width:807px">
							<a href="javascript:scrollDiv('clan_list_display',30)"><img src="images/rpg/clans/search/{SD_DIR}buttons/Monter.{SD_EXT}"/></a>
							<a href="javascript:scrollDiv('clan_list_display',-30)"><img src="images/rpg/clans/search/{SD_DIR}buttons/Descendre.{SD_EXT}"/></a>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td align="right" valign="bottom"><a id="close_button" style="margin-right:20px;margin-bottom:20px" href="javascript:close_window()"></a></td>
			</tr>
		</table>
	</body>
</html>