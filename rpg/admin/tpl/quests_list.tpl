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

		<title>Exitium - Liste des quêtes</title>
		
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
		
		<div style="margin:auto;text-align:center">
			<h2>Liste des quêtes</h2>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Type</th>
						<th>Date</th>
						<th>Disponible</th>
						<th>Unique</th>
						<th>Posts requis</th>
						<th>Forum</th>
						<th>Monstre</th>
						<th>BGM</th>
						<th>Fond de combat</th>
						<th>XP</th>
						<th>Ralz</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Nom</th>
						<th>Description</th>
						<th>Type</th>
						<th>Date</th>
						<th>Disponible</th>
						<th>Unique</th>
						<th>Posts requis</th>
						<th>Forum</th>
						<th>Monstre</th>
						<th>BGM</th>
						<th>Fond de combat</th>
						<th>XP</th>
						<th>Ralz</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN quest_bloc -->
				<tr>
					<td>{quest_bloc.NAME}</td>
					<td>{quest_bloc.DESC}</td>
					<td>{quest_bloc.TYPE}</td>
					<td>{quest_bloc.DATE}</td>
					<td>{quest_bloc.AVAILABLE}</td>
					<td>{quest_bloc.UNIQUE}</td>
					<td>{quest_bloc.POSTS}</td>
					<td>{quest_bloc.FORUM}</td>
					<td>{quest_bloc.MONSTER}</td>
					<td>{quest_bloc.BGM}</td>
					<td>{quest_bloc.BACKGROUND}</td>
					<td>{quest_bloc.XP}</td>
					<td>{quest_bloc.RALZ}</td>
				</tr>
				<!-- END quest_bloc -->
				</tbody>
			</table>
		
		</div>
	</body>
</html>