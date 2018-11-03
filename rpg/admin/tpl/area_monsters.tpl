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

		<title>Exitium - Liste des monstres par zone</title>
		
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
			<h2>Liste des monstres par zone</h2>
			
			<!-- BEGIN area_bloc -->
			<h1>{area_bloc.NAME}</h1>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Sous-Zone</th>
						<th>Monstre</th>
						<th>Taux de rencontre</th>
						<th>Drops</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Sous-Zone</th>
						<th>Monstre</th>
						<th>Taux de rencontre</th>
						<th>Drops</th>
					</tr>
				</tfoot>
				<tbody>
					<!-- BEGIN part_bloc -->
					<tr>
						<td>{area_bloc.part_bloc.NAME}</td>
						<td>{area_bloc.part_bloc.MONSTER}</td>
						<td>{area_bloc.part_bloc.RATE}%</td>
						<td>{area_bloc.part_bloc.DROPS}</td>
					</tr>
					<!-- END part_bloc -->
				</tbody>
			</table>
			
			<!-- END area_bloc -->
			
		</div>
	</body>
</html>