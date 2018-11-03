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

		<title>Exitium - Liste des quêtes actives</title>
		
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
			<h2>Liste des quêtes actives</h2>
			
			<table border="1" style="margin:auto">
				<thead>
					<tr>
						<th>ID de la quête</th>
						<th>Nom</th>
						<th>Initiateur</th>
						<th>Participants</th>
						<th>Forum</th>
						<th>Topic</th>
						<!--th>Démarrée</th-->
						<th>Ouverte</th>
						<th>Nombre de posts</th>
						<th>Posts requis</th>
						<th>ID Enigme</th>
						<th>Token de combat</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ID de la quête</th>
						<th>Nom</th>
						<th>Initiateur</th>
						<th>Participants</th>
						<th>Forum</th>
						<th>Topic</th>
						<!--th>Démarrée</th-->
						<th>Ouverte</th>
						<th>Nombre de posts</th>
						<th>Posts requis</th>
						<th>ID Enigme</th>
						<th>Token de combat</th>
					</tr>
				</tfoot>
				<tbody>
				<!-- BEGIN quest_bloc -->
				<tr>
					<td>{quest_bloc.ID}</td>
					<td>{quest_bloc.NAME}</td>
					<td>{quest_bloc.LEADER}</td>
					<td>{quest_bloc.MEMBERS}</td>
					<td>{quest_bloc.FORUM}</td>
					<td>{quest_bloc.TOPIC}</td>
					<!--td>{quest_bloc.STARTED}</td-->
					<td>{quest_bloc.OPENED}</td>
					<td>{quest_bloc.POSTS}</td>
					<td>{quest_bloc.REQUIRED_POSTS}</td>
					<td>{quest_bloc.RIDDLE_ID}</td>
					<td>{quest_bloc.BATTLE_TOKEN}</td>
				</tr>
				<!-- END quest_bloc -->
				</tbody>
			</table>
		
		</div>
	</body>
</html>