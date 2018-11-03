<div id="skills_types" style="width:600px;height:50px;">
	<span class="onglet_0 onglet" id="type_physical" onclick="javascript:change_onglet('physical');">Physique</span>
	<span class="onglet_0 onglet" id="type_magical" onclick="javascript:change_onglet('magical');">Magique</span>
	<span class="onglet_0 onglet" id="type_buff" onclick="javascript:change_onglet('buff');">Buffs</span>
	<span class="onglet_0 onglet" id="type_heal" onclick="javascript:change_onglet('heal');">Heal</span>
	<span class="onglet_0 onglet" id="type_help" onclick="javascript:change_onglet('help');">Soutien</span>
	<span class="onglet_0 onglet" onclick="javascript:hide_skills_menu()">Fermer la fenêtre</span>
</div>

<div style="width:600px;height:350px;color:white;">
	<div class="skills_list" id="skills_physical">
		<!-- BEGIN skills_physical_bloc -->
		<a onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{skills_physical_bloc.DESC}<br><em>Cout : {skills_physical_bloc.PF} PF</em><br><em>Cooldown : {skills_physical_bloc.CD} Tours</em>" style="display:inline-block;vertical-align:middle;background:#dddddd;border:1px solid black;text-decoration:none;color:black;width:160px;height:30px;margin:2px;line-height:30px;" href="javascript:learn_skill({SLOT}, '{skills_physical_bloc.TYPE}')">{skills_physical_bloc.NAME}</a>
		<!-- END skills_physical_bloc -->
	</div>
	
	<div class="skills_list" id="skills_magical">
		<!-- BEGIN skills_magical_bloc -->
		<a onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{skills_magical_bloc.DESC}<br><em>Cout : {skills_magical_bloc.PF} PF</em><br><em>Cooldown : {skills_magical_bloc.CD} Tours</em>" style="display:inline-block;vertical-align:middle;background:#dddddd;border:1px solid black;text-decoration:none;color:black;width:160px;height:30px;margin:2px;line-height:30px;" href="javascript:learn_skill({SLOT}, '{skills_magical_bloc.TYPE}')">{skills_magical_bloc.NAME}</a>
		<!-- END skills_magical_bloc -->
	</div>
	
	<div class="skills_list" id="skills_buff">
		<!-- BEGIN skills_buff_bloc -->
		<a onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{skills_buff_bloc.DESC}<br><em>Cout : {skills_buff_bloc.PF} PF</em><br><em>Cooldown : {skills_buff_bloc.CD} Tours</em>" style="display:inline-block;vertical-align:middle;background:#dddddd;border:1px solid black;text-decoration:none;color:black;width:160px;height:30px;margin:2px;line-height:30px;" href="javascript:learn_skill({SLOT}, '{skills_buff_bloc.TYPE}')">{skills_buff_bloc.NAME}</a>
		<!-- END skills_buff_bloc -->
	</div>
	
	<div class="skills_list" id="skills_heal">
		<!-- BEGIN skills_heal_bloc -->
		<a onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{skills_heal_bloc.DESC}<br><em>Cout : {skills_heal_bloc.PF} PF</em><br><em>Cooldown : {skills_heal_bloc.CD} Tours</em>" style="display:inline-block;vertical-align:middle;background:#dddddd;border:1px solid black;text-decoration:none;color:black;width:160px;height:30px;margin:2px;line-height:30px;" href="javascript:learn_skill({SLOT}, '{skills_heal_bloc.TYPE}')">{skills_heal_bloc.NAME}</a>
		<!-- END skills_heal_bloc -->
	</div>
	
	<div class="skills_list" id="skills_help">
		<!-- BEGIN skills_help_bloc -->
		<a onmouseover="tooltip.show(this)" onmouseout="tooltip.hide(this)" title="{skills_help_bloc.DESC}<br><em>Cout : {skills_help_bloc.PF} PF</em><br><em>Cooldown : {skills_help_bloc.CD} Tours</em>" style="display:inline-block;vertical-align:middle;background:#dddddd;border:1px solid black;text-decoration:none;color:black;width:160px;height:30px;margin:2px;line-height:30px;" href="javascript:learn_skill({SLOT}, '{skills_help_bloc.TYPE}')">{skills_help_bloc.NAME}</a>
		<!-- END skills_help_bloc -->
	</div>
</div>