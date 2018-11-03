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

		<title>Exitium - Liste des objets du marché noir</title>
		
		<!--link rel="stylesheet" type="text/css" href="rpg/css/viewwarehouse{SD_CSS}.css" /-->
		<link rel="stylesheet" type="text/css" href="{ROOT}rpg/css/tooltip.css" />
		
		<script type="text/javascript" src="{ROOT}rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/eventutils.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/tooltip.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="{ROOT}rpg/js/session.js"></script>
		<!--script type="text/javascript" src="../../rpg/js/window.js"></script-->
		<script type="text/javascript">
		</script>
	</head>

	<body onload="javascript:set_sid('{SID}');">
		
		<div id="tooltip"></div>
		
		<div style="width:1280px;margin-left:auto;margin-right:auto;text-align:center">
			<h2>Liste des objets du marché noir</h2>
			
			
			<h1>Seringues</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN syringe_bloc -->
					<tr>
						<td>{syringe_bloc.NAME}</td>
						<td>{syringe_bloc.DESC}</td>
						<td>{syringe_bloc.PRICE}</td>
						<td>{syringe_bloc.PLACE}</td>
					</tr>
					<!-- END syringe_bloc -->
				</tbody>
			</table>
			
			<h1>Objets Speciaux</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN specials_bloc -->
					<tr>
						<td>{specials_bloc.NAME}</td>
						<td>{specials_bloc.DESC}</td>
						<td>{specials_bloc.PRICE}</td>
						<td>{specials_bloc.PLACE}</td>
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
						<th>Place</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN sets_bloc -->
					<tr>
						<td>{sets_bloc.NAME}</td>
						<td>{sets_bloc.DESC}</td>
						<td>{sets_bloc.PRICE}</td>
						<td>{sets_bloc.PLACE}</td>
					</tr>
					<!-- END sets_bloc -->
				</tbody>
			</table>
			
			<h1>Equipements</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Prix</th>
						<th>Place</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN equips_bloc -->
					<tr>
						<td>{equips_bloc.NAME}</td>
						<td>{equips_bloc.DESC}</td>
						<td>{equips_bloc.PRICE}</td>
						<td>{equips_bloc.PLACE}</td>
					</tr>
					<!-- END equips_bloc -->
				</tbody>
			</table>
			
		</div>
	</body>
</html>