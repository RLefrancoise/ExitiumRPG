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

		<title>Exitium - Liste des zones</title>
		
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
			<h2>Liste des zones</h2>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Niveau</th>
						<th>BGM</th>
						<th>Background</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Niveau</th>
						<th>BGM</th>
						<th>Background</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN area_bloc -->
					<tr>
						<td>{area_bloc.NAME}</td>
						<td>{area_bloc.DESC}</td>
						<td>{area_bloc.LEVEL}</td>
						<td>{area_bloc.BGM}</td>
						<td>{area_bloc.BACKGROUND}</td>
					</tr>
					<!-- END area_bloc -->
				</tbody>
			</table>
			
			<h2>Liste des sous-zones</h2>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Zone</th>
						<th>Niveau Min.</th>
						<th>Niveau Max.</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Zone</th>
						<th>Niveau Min.</th>
						<th>Niveau Max.</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN part_bloc -->
					<tr>
						<td>{part_bloc.NAME}</td>
						<td>{part_bloc.AREA}</td>
						<td>{part_bloc.MINLEVEL}</td>
						<td>{part_bloc.MAXLEVEL}</td>
					</tr>
					<!-- END part_bloc -->
				</tbody>
			</table>
		</div>
	</body>
</html>