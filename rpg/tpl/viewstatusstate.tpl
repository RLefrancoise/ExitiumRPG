<!-- pv -->
<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Les points de vie de votre personnage.<br>Lorsqu'ils tombent à 0, votre personnage ne peut plus combattre.">
	<div class="bars_multilayer" style="display:inline-block">
		<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty.{SD_EXT}"/>
		<img onload="clip_image(this,0,202 * ({USER_HP}/{USER_MAX_HP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/PV.{SD_EXT}"/>
	</div>
	<p style="display:inline;margin:0;padding:0">PV - {USER_HP}/{USER_MAX_HP}<p>
</div>

<!-- pf -->
<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Les points de flux de votre personnage.<br>Nécessaire pour utiliser les skills.">
	<div class="bars_multilayer" style="display:inline-block">
		<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty.{SD_EXT}"/>
		<img onload="clip_image(this,0,202 * ({USER_FP}/{USER_MAX_FP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/PF.{SD_EXT}"/>
	</div>
	<p style="display:inline;margin:0;padding:0">PF - {USER_FP}/{USER_MAX_FP}<p>
</div>

<!-- xp -->
<div onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="Les points d'expérience de votre personnage.<br>Gagnez des points d'expérience pour augmenter de niveau et devenir plus fort." >
	<div class="bars_multilayer" style="display:inline-block">
		<img class="layer1" alt="" src="images/rpg/status/{SD_DIR}barres/empty2.{SD_EXT}"/>
		<img onload="clip_image(this,0,202 * ({USER_XP}/{USER_MAX_XP}),10,0);" class="layer2" alt="" src="images/rpg/status/{SD_DIR}barres/XP.{SD_EXT}"/>
	</div>
	<p style="display:inline;margin:0;padding:0">XP - {USER_XP}/{USER_MAX_XP}<p>
</div>