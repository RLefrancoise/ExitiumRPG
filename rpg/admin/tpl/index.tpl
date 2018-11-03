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

		<title>Exitium - Administration</title>
		
		<!--link rel="stylesheet" type="text/css" href="rpg/css/viewwarehouse{SD_CSS}.css" /-->
		<link rel="stylesheet" type="text/css" href="../../rpg/css/tooltip.css" />
		
		<script type="text/css">
			.categories {
				text-size:16px;
			}
		</script>
		<script type="text/javascript" src="../../rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="../../rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="../../rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="../../rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="../../rpg/js/session.js"></script>
		<!--script type="text/javascript" src="../../rpg/js/window.js"></script-->
		<script type="text/javascript">
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;height:1024px;margin:auto;text-align:center">
			<h2>Panneau d'administration du système RPG</h2>
			
			<table border="1" style="margin:auto;text-align:center">
				<tr style="background-color:grey">
					<td class="categories"><strong>Quêtes</strong></td>
					<td class="categories"><strong>Monstres</strong></td>
					<td class="categories"><strong>Objets</strong></td>
					<td class="categories"><strong>Carte</strong></td>
					<td class="categories"><strong>Events &amp; Batailles</strong></td>
					<td class="categories"><strong>Marché Noir</strong></td>
					<td class="categories"><strong>Système</strong></td>
				</tr>
				<tr>
					<td>
						<a>Créer une quête</a><br>
						<a>Modifier une quête</a><br>
						<a>Supprimer une quête</a><br>
						<a href="quests_list.php">Voir les quêtes</a><br>
						<a href="active_quests_list.php">Voir les quêtes en cours</a>
					</td>
					
					<td>
						<a href="monster_form.php?mode=add">Ajouter un monstre</a><br>
						<a href="monsters_list.php">Voir les monstres</a><br>
						<a>Modifier les drops</a><br>
						<a href="img_upload_form.php?mode=monster">Uploader une image</a>
					</td>
					
					<td>
						<a href="item_form.php?mode=add&type=syringe">Ajouter une seringue</a><br>
						<a href="item_form.php?mode=add&type=clothes">Ajouter un haut</a><br>
						<a href="item_form.php?mode=add&type=leggings">Ajouter un bas</a><br>
						<a href="item_form.php?mode=add&type=gloves">Ajouter des gants</a><br>
						<a href="item_form.php?mode=add&type=shoes">Ajouter des bottes</a><br>
						<a href="item_form.php?mode=add&type=specials">Ajouter un objet spécial</a><br>
						<a href="item_form.php?mode=add&type=sets">Ajouter un set</a><br>
						<a href="item_form.php?mode=add&type=orbs">Ajouter une orbe</a><br>
						<a href="items_list.php">Voir les objets</a><br>
						<a href="img_upload_form.php?mode=icon">Uploader une icone</a>
					</td>
					
					<td>
						<a>Ajouter un monstre à une zone</a><br>
						<a>Modifier les monstres des zones</a><br>
						<a href="area_monsters.php">Voir les monstres de chaque zone</a><br>
						<a href="area_list.php">Voir les zones</a>
					</td>
					
					<td>
						<a>Démarrer un event</a><br>
						<a>Supprimer un event</a><br>
						<a>Démarrer une bataille</a><br>
						<a>Supprimer une bataille</a>
					</td>
					<td>
						<a>Ajouter un objet au marché noir</a><br>
						<a href="market_items.php">Voir les objets du marché noir</a>
					</td>
					<td>
						<a href="config_edit.php">Lire/Modifier le fichier de configuration</a><br>
						<a href="reset_char.php">Reset un personnage</a><br>
						<a href="reset_char.php?mode=all">Reset tous les personnages</a>
					</td>
				</tr>
			</table>
		
		</div>
	</body>
</html>