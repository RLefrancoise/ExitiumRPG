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
		
		<link rel="stylesheet" type="text/css" href="rpg/css/viewcreateclan{SD_CSS}.css" />
		<script type="text/javascript">
			function close_window() {
				parent.document.getElementById('main_menu').style.visibility = 'visible';
				parent.document.getElementById('iframe').style.visibility = 'hidden';
				parent.document.getElementById('iframe').width = "0px";
				parent.document.getElementById('iframe').height = "0px";
				parent.hide_message();
			}
			
			function set_size() {
				parent.document.getElementById('iframe').width = "768px";
				parent.document.getElementById('iframe').height = "860px";
			}
			
			function create_clan() {
				var name = document.getElementById('name_input').value;
				var image_status = document.getElementById('image_upload_status').src;
				var image = image_status.split("/");
				image = image[image.length - 1];
				var desc = document.getElementById('clan_text').value;
				
				if(name == '') {
					parent.show_message('PasNOM.png');
					return;
				}
				else if(image == "OptionNON.png" || image == "OptionNON.gif") {
					parent.show_message('PasIMAGE.png');
					return;
				}
				else if(desc == '') {
					parent.show_message('PasTEXTE.png');
					return;
				}
				
				document.getElementById('create_button').style.visibility = 'hidden';
				document.myForm.submit();
			}
			
			function create_end(sError) {
				if(sError == 'create_ok') {
					parent.show_message('Clancree.png');
					parent.window.setTimeout("location=('viewclan.php');",3000); // redirection dans 3 secondes
					return;
				} else if(sError == 'no_money') {
					parent.show_message('PasARGENT.png');
				} else if(sError == 'no_clan_name') {
					parent.show_message('PasNOM.png');
				} else if(sError == 'no_clan_text') {
					parent.show_message('PasTEXTE.png');
				} else if(sError == 'name_already_used') {
					alert('Ce nom de clan est déjà utilisé, merci d\'en choisir un autre.');
				} else if(sError == 'already_has_clan') {
					alert('Vous avez déjà un clan ! Le fait que cette erreur arrive montre un dysfonctionnement du système ou une tentative de triche...');
				} else if(sError == 'bdd_error') {
					alert('Une erreur en rapport avec la base de données est survenue !');
				} else if(sError == 'upload_error') {
					alert('Une erreur est survenue pendant l\'upload de l\'image du clan !');
				} else if(sError == 'internal_error') {
					alert('Une erreur interne est survenue !');
				} else if(sError == 'dimension_error') {
					parent.show_message('PasBONNETAILLE.png');
				} else if(sError == 'no_image') {
					alert('Le fichier envoyé n\'est pas une image !');
				} else if(sError == 'wrong_extension') {
					alert('Le type de l\'image est incorrect. Les formats acceptés sont PNG, GIF et JPG.');
				} else if(sError == 'no_post') {
					alert('Le serveur a reçu un formulaire vide. Veuillez recommencer l\'opération.');
				} else if(sError == 'not_connected') {
					alert('Vous n\'êtes pas connecté !');
				} else {
					alert("Le serveur a retourné une value inconnue ! Error : " + sError);
				}
				
				document.getElementById('create_button').style.visibility = 'visible';
				
			}
			
			function get_file(){
				document.getElementById("upfile").click();
			}
			function sub(obj, sd_ext){
				var file = obj.value;
				var fileName = file.split("\\");
				if(fileName[fileName.length-1] != '') {
					if(sd_ext == 'png')
						document.getElementById('image_upload_status').src = 'images/rpg/clans/create/buttons/OptionOK.png';
					else
						document.getElementById('image_upload_status').src = 'images/rpg/clans/create/sd/buttons/OptionOK.gif';
				}
			}
		</script>
	</head>

	<body onload="set_size();javascript:set_sid('{SID}')">
		
		<form target="upload_iframe" action="createclan.php?sid={SID}" method="POST" enctype="multipart/form-data" name="myForm">
		
			<table id="create_table">
				<tr>
					<td colspan="2" align="center" valign="center"><input style="margin-left:-20px" id="name_input" type="text" name="clan_name"/></td>
				</tr>
				<tr>
					<td colspan="2" align="center" valign="center">
						
						<a style="margin-left:-20px" id="upload_button" href="javascript:get_file()"><img src="images/rpg/clans/create/{SD_DIR}buttons/open_image.{SD_EXT}"/></a>
						<!-- this is your file input tag, so i hide it!-->
						<!-- i used the onchange event to fire the form submission-->
						<div style='height: 0px;width: 0px; overflow:hidden;'><input id="upfile" name="clan_image" type="file" value="upload" onchange="sub(this, '{SD_EXT}')"/></div>
							
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center" valign="center"><img style="margin-left:-20px" id="image_upload_status" src="images/rpg/clans/create/{SD_DIR}buttons/OptionNON.{SD_EXT}"/></td>
				</tr>
				<tr>
					<td colspan="2" align="center" valign="center"><textarea style="margin-left:-20px" id="clan_text" name="clan_text" rows="20" cols="80"></textarea></td>
				</tr>
				<tr>
					<td align="center" valign="top" width="659">
						<a id="create_button" style="margin-left:100px;" href="javascript:create_clan()"></a>
					</td>
					<td align="center" valign="bottom" width="109">
						<a id="close_button" style="margin-bottom:25px;" href="javascript:close_window()"></a>
					</td>
				</tr>
			</table>
		
		</form>
		
		<iframe id="upload_iframe" width="0" height="0" style="visibility:hidden"></iframe>
		
	</body>
</html>