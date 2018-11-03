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

		<title>Exitium - Liste des objets</title>
		
		<!--link rel="stylesheet" type="text/css" href="rpg/css/viewwarehouse{SD_CSS}.css" /-->
		<link rel="stylesheet" type="text/css" href="{ROOT}rpg/css/tooltip.css" />
		
		<script type="text/javascript" src="{ROOT}rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/session.js"></script>
		<!--script type="text/javascript" src="../../rpg/js/window.js"></script-->
		<script type="text/javascript">
		
			function delete_item(type, id) {
				var c = confirm('Supprimer cet objet ?');
				if(c == true) {
				
					var xhr = getXMLHttpRequest();
			 
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4) {
							if( (xhr.status == 200 || xhr.status == 0) ) {
							
								if(string_starts_with(xhr.responseText, "not_connected")) {
									alert('Vous n\'êtes pas connecté !');
								} else if(string_starts_with(xhr.responseText, "error")) {
									alert('Une erreur est survenue !');
								} else if(string_starts_with(xhr.responseText, "delete_ok")) {
									alert('L\'objet a été supprimé.');
									location.reload();
								} else {
									alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
								}
								
							} else {
								alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
							}
						}
						
					};
					
					xhr.open("GET", "item_action.php?sid=" + SID + "&id=" + id + "&type=" + type + "&mode=delete", true);
					xhr.send(null);
				}
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;margin-left:auto;margin-right:auto;text-align:center">
			<h2>Liste des objets</h2>
			
			<h1>Seringues</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>Utilisable en dehors des combats</th>
						<th>PV</th>
						<th>PV Max</th>
						<th>PF</th>
						<th>PF Max</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>Utilisable en dehors des combats</th>
						<th>PV</th>
						<th>PV Max</th>
						<th>PF</th>
						<th>PF Max</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN syringe_bloc -->
				<tr>
					<td>{syringe_bloc.NAME}<br><a href="item_form.php?mode=edit&type=syringe&id={syringe_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('syringe', {syringe_bloc.ID})">Supprimer</a></td>
					<td>{syringe_bloc.DESC}</td>
					<td>{syringe_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{syringe_bloc.IMG}" /></td>
					<td>{syringe_bloc.USABLE_OUTSIDE_BATTLE}</td>
					<td>{syringe_bloc.PV}</td>
					<td>{syringe_bloc.MAX_PV}</td>
					<td>{syringe_bloc.PF}</td>
					<td>{syringe_bloc.MAX_PF}</td>
					<td>{syringe_bloc.ATTACK}</td>
					<td>{syringe_bloc.DEFENSE}</td>
					<td>{syringe_bloc.SPEED}</td>
					<td>{syringe_bloc.FLUX}</td>
					<td>{syringe_bloc.RESISTANCE}</td>
				</tr>
				<!-- END syringe_bloc -->
				</tbody>
			</table>
			
			<h1>Hauts</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN clothes_bloc -->
				<tr>
					<td>{clothes_bloc.NAME}<br><a href="item_form.php?mode=edit&type=clothes&id={clothes_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('clothes', {clothes_bloc.ID})">Supprimer</a></td>
					<td>{clothes_bloc.DESC}</td>
					<td>{clothes_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{clothes_bloc.IMG}" /></td>
					<td>{clothes_bloc.PV}</td>
					<td>{clothes_bloc.PF}</td>
					<td>{clothes_bloc.ATTACK}</td>
					<td>{clothes_bloc.DEFENSE}</td>
					<td>{clothes_bloc.SPEED}</td>
					<td>{clothes_bloc.FLUX}</td>
					<td>{clothes_bloc.RESISTANCE}</td>
					<td>{clothes_bloc.LEVEL}</td>
				</tr>
				<!-- END clothes_bloc -->
				</tbody>
			</table>
			
			<h1>Bas</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN leggings_bloc -->
				<tr>
					<td>{leggings_bloc.NAME}<br><a href="item_form.php?mode=edit&type=leggings&id={leggings_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('leggings', {leggings_bloc.ID})">Supprimer</a></td>
					<td>{leggings_bloc.DESC}</td>
					<td>{leggings_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{leggings_bloc.IMG}" /></td>
					<td>{leggings_bloc.PV}</td>
					<td>{leggings_bloc.PF}</td>
					<td>{leggings_bloc.ATTACK}</td>
					<td>{leggings_bloc.DEFENSE}</td>
					<td>{leggings_bloc.SPEED}</td>
					<td>{leggings_bloc.FLUX}</td>
					<td>{leggings_bloc.RESISTANCE}</td>
					<td>{leggings_bloc.LEVEL}</td>
				</tr>
				<!-- END leggings_bloc -->
				</tbody>
			</table>
			
			<h1>Gants</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN gloves_bloc -->
				<tr>
					<td>{gloves_bloc.NAME}<br><a href="item_form.php?mode=edit&type=gloves&id={gloves_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('gloves', {gloves_bloc.ID})">Supprimer</a></td>
					<td>{gloves_bloc.DESC}</td>
					<td>{gloves_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{gloves_bloc.IMG}" /></td>
					<td>{gloves_bloc.PV}</td>
					<td>{gloves_bloc.PF}</td>
					<td>{gloves_bloc.ATTACK}</td>
					<td>{gloves_bloc.DEFENSE}</td>
					<td>{gloves_bloc.SPEED}</td>
					<td>{gloves_bloc.FLUX}</td>
					<td>{gloves_bloc.RESISTANCE}</td>
					<td>{gloves_bloc.LEVEL}</td>
				</tr>
				<!-- END gloves_bloc -->
				</tbody>
			</table>
			
			<h1>Bottes</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Niveau requis</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN shoes_bloc -->
				<tr>
					<td>{shoes_bloc.NAME}<br><a href="item_form.php?mode=edit&type=shoes&id={shoes_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('shoes', {shoes_bloc.ID})">Supprimer</a></td>
					<td>{shoes_bloc.DESC}</td>
					<td>{shoes_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{shoes_bloc.IMG}" /></td>
					<td>{shoes_bloc.PV}</td>
					<td>{shoes_bloc.PF}</td>
					<td>{shoes_bloc.ATTACK}</td>
					<td>{shoes_bloc.DEFENSE}</td>
					<td>{shoes_bloc.SPEED}</td>
					<td>{shoes_bloc.FLUX}</td>
					<td>{shoes_bloc.RESISTANCE}</td>
					<td>{shoes_bloc.LEVEL}</td>
				</tr>
				<!-- END shoes_bloc -->
				</tbody>
			</table>
			
			<h1>Objets Spéciaux</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>Effet</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>Effet</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN specials_bloc -->
				<tr>
					<td>{specials_bloc.NAME}<br><a href="item_form.php?mode=edit&type=specials&id={specials_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('specials', {specials_bloc.ID})">Supprimer</a></td>
					<td>{specials_bloc.DESC}</td>
					<td>{specials_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{specials_bloc.IMG}" /></td>
					<td>{specials_bloc.EFFECT}</td>
				</tr>
				<!-- END specials_bloc -->
				</tbody>
			</table>
			
			<h1>Sets</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Haut</th>
						<th>Bas</th>
						<th>Gants</th>
						<th>Bottes</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Haut</th>
						<th>Bas</th>
						<th>Gants</th>
						<th>Bottes</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN sets_bloc -->
				<tr>
					<td>{sets_bloc.NAME}<br><a href="item_form.php?mode=edit&type=sets&id={sets_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('sets', {sets_bloc.ID})">Supprimer</a></td>
					<td>{sets_bloc.DESC}</td>
					<td>{sets_bloc.PRICE}</td>
					<td>{sets_bloc.CLOTH}</td>
					<td>{sets_bloc.LEGGINGS}</td>
					<td>{sets_bloc.GLOVES}</td>
					<td>{sets_bloc.SHOES}</td>
					<td>{sets_bloc.PV}</td>
					<td>{sets_bloc.PF}</td>
					<td>{sets_bloc.ATTACK}</td>
					<td>{sets_bloc.DEFENSE}</td>
					<td>{sets_bloc.SPEED}</td>
					<td>{sets_bloc.FLUX}</td>
					<td>{sets_bloc.RESISTANCE}</td>
				</tr>
				<!-- END sets_bloc -->
				</tbody>
			</table>
			
			<h1>Orbes</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>PV (%)</th>
						<th>PF (%)</th>
						<th>Effet</th>
						<th>Déclenchement</th>
						<th>Slots</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Image</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>PV (%)</th>
						<th>PF (%)</th>
						<th>Effet</th>
						<th>Déclenchement</th>
						<th>Slots</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN orbs_bloc -->
				<tr>
					<td>{orbs_bloc.NAME}<br><a href="item_form.php?mode=edit&type=orbs&id={orbs_bloc.ID}">Modifier</a><br><a href="javascript:delete_item('orbs', {orbs_bloc.ID})">Supprimer</a></td>
					<td>{orbs_bloc.DESC}</td>
					<td>{orbs_bloc.PRICE}</td>
					<td><img src="{ROOT}images/rpg/icons/{orbs_bloc.IMG}" /></td>
					<td>{orbs_bloc.ATTACK}</td>
					<td>{orbs_bloc.DEFENSE}</td>
					<td>{orbs_bloc.SPEED}</td>
					<td>{orbs_bloc.FLUX}</td>
					<td>{orbs_bloc.RESISTANCE}</td>
					<td>{orbs_bloc.PV}</td>
					<td>{orbs_bloc.PF}</td>
					<td>{orbs_bloc.EFFECT}</td>
					<td>{orbs_bloc.TRIGGER}</td>
					<td>{orbs_bloc.SLOTS}</td>
				</tr>
				<!-- END orbs_bloc -->
				</tbody>
			</table>
		
		</div>
	</body>
</html>