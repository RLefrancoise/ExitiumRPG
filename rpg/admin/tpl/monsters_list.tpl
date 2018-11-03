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

		<title>Exitium - Liste des monstres</title>
		
		<!--link rel="stylesheet" type="text/css" href="rpg/css/viewwarehouse{SD_CSS}.css" /-->
		<link rel="stylesheet" type="text/css" href="{ROOT}rpg/css/tooltip.css" />
		
		<script type="text/javascript" src="{ROOT}rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/session.js"></script>
		<!--script type="text/javascript" src="../../rpg/js/window.js"></script-->
		<script type="text/javascript">
		
			function delete_monster(id) {
				var c = confirm('Supprimer ce monstre ?');
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
									alert('Le monstre a été supprimé.');
									location.reload();
								} else {
									alert("Le serveur a retourné une valeur inconnue : " + xhr.responseText);
								}
								
							} else {
								alert("Une erreur est survenue lors de la requête : " + xhr.responseText);
							}
						}
						
					};
					
					xhr.open("GET", "delete_monster.php?sid=" + SID + "&id=" + id, true);
					xhr.send(null);
				}
			}
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;margin-left:auto;margin-right:auto;text-align:center">
			<h2>Liste des monstres</h2>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Image</th>
						<th>BGM</th>
						<th>Niveau</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Ralz</th>
						<th>Comportement</th>
						<th>Skills</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Image</th>
						<th>BGM</th>
						<th>Niveau</th>
						<th>PV</th>
						<th>PF</th>
						<th>Attaque</th>
						<th>Défense</th>
						<th>Vitesse</th>
						<th>Flux</th>
						<th>Résistance</th>
						<th>Ralz</th>
						<th>Comportement</th>
						<th>Skills</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN monster_bloc -->
				<tr>
					<td>{monster_bloc.NAME}<br><a href="monster_form.php?mode=edit&id={monster_bloc.ID}">Modifier</a><br><a href="javascript:delete_monster({monster_bloc.ID})">Supprimer</a></td>
					<td><img src="{ROOT}/images/rpg/battle/monsters/{monster_bloc.IMG}" /></td>
					<td>{monster_bloc.BGM}</td>
					<td>{monster_bloc.LEVEL}</td>
					<td>{monster_bloc.PV}</td>
					<td>{monster_bloc.PF}</td>
					<td>{monster_bloc.ATTACK}</td>
					<td>{monster_bloc.DEFENSE}</td>
					<td>{monster_bloc.SPEED}</td>
					<td>{monster_bloc.FLUX}</td>
					<td>{monster_bloc.RESISTANCE}</td>
					<td>{monster_bloc.RALZ}</td>
					<td>{monster_bloc.BEHAVIOR}</td>
					<td>{monster_bloc.SKILLS}</td>
				</tr>
				<!-- END monster_bloc -->
				</tbody>
			</table>
		
		</div>
	</body>
</html>