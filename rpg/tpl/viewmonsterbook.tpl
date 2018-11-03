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

		<title>Exitium - Bestiaire</title>
		
		<!--link rel="stylesheet" type="text/css" href="rpg/css/viewmonsterbook{SD_CSS}.css" /-->
		<script type="text/javascript" src="rpg/js/stringutils.js"></script>
		<script type="text/javascript" src="rpg/js/oXHR.js"></script>
		<script type="text/javascript" src="rpg/js/session.js"></script>
		<script type="text/javascript" src="rpg/js/windows.js"></script>
	</head>

	<body onload="javascript:set_sid('{SID}')">
		<script type="text/javascript">
		   put_menu_and_chatbox('{SID}');
		</script>
		
		<div style="width:800px;margin:auto;text-align:center">
			<!-- BEGIN area_bloc -->
			<div style="margin-bottom:10px;">
				<fieldset>
					<legend>{area_bloc.AREA_NAME}</legend>
					<!-- BEGIN monster_bloc -->
					<div style="display:inline-block;background-color:rgba(220,220,220,128);border:1px dotted black;width:750px;margin-bottom:2px;">
						<h4>{area_bloc.monster_bloc.MONSTER_NAME}</h4><br>
						<img style="float:left;margin-right:10px;" src="images/rpg/battle/monsters/{area_bloc.monster_bloc.MONSTER_IMG}" />
						<div style="text-align:left;padding-top:10px;">
							<span><strong>Niveau :</strong> {area_bloc.monster_bloc.MONSTER_LEVEL}</span><br>
							<span><strong>PV :</strong> {area_bloc.monster_bloc.MONSTER_PV}</span><br>
							<span><strong>PF :</strong> {area_bloc.monster_bloc.MONSTER_PF}</span><br>
							<span><strong>Attaque :</strong> {area_bloc.monster_bloc.MONSTER_ATTACK}</span><br>
							<span><strong>Défense :</strong> {area_bloc.monster_bloc.MONSTER_DEFENSE}</span><br>
							<span><strong>Vitesse :</strong> {area_bloc.monster_bloc.MONSTER_SPEED}</span><br>
							<span><strong>Flux :</strong> {area_bloc.monster_bloc.MONSTER_FLUX}</span><br>
							<span><strong>Résistance :</strong> {area_bloc.monster_bloc.MONSTER_RESISTANCE}</span><br><br>
							<span><strong>Exp :</strong> {area_bloc.monster_bloc.MONSTER_EXP}</span><br>
							<span><strong>Ralz :</strong> {area_bloc.monster_bloc.MONSTER_RALZ}</span><br>
							<span><strong>Zone :</strong> {area_bloc.monster_bloc.MONSTER_AREA_PART}</span><br>
							<span><strong>Rencontres :</strong> {area_bloc.monster_bloc.MONSTER_ENCOUNTERS}</span><br>
							<span><strong>Victoires :</strong> {area_bloc.monster_bloc.MONSTER_WINS}</span><br>
							<span><strong>Défaites :</strong> {area_bloc.monster_bloc.MONSTER_LOSES}</span>
						</div>
					</div>
					<!-- END monster_bloc -->
					<!-- BEGIN unknown_monster_bloc -->
					<div style="display:inline-block;background-color:rgba(220,220,220,128);border:1px dotted black;width:750px;margin-bottom:2px;">
						<h4>??????</h4><br>
						<div style="display:inline-block;width:200px;height:300px;background-color:black;float:left;margin-right:10px;"></div>
						<div style="text-align:left;padding-top:10px;">
							<span><strong>Niveau :</strong> ???</span><br>
							<span><strong>PV :</strong> ???</span><br>
							<span><strong>PF :</strong> ???</span><br>
							<span><strong>Attaque :</strong> ???</span><br>
							<span><strong>Défense :</strong> ???</span><br>
							<span><strong>Vitesse :</strong> ???</span><br>
							<span><strong>Flux :</strong> ???</span><br>
							<span><strong>Résistance :</strong> ???</span><br><br>
							<span><strong>Exp :</strong> ???</span><br>
							<span><strong>Ralz :</strong> ???</span><br>
							<span><strong>Zone :</strong> ???</span>
						</div>
						
					</div>
					<!-- END unknown_monster_bloc -->
				</fieldset>
			</div>
			<!-- END area_bloc -->
		
		</div>
	</body>
</html>