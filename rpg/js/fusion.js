var fusion_skill1 = null;
var fusion_skill2 = null;

function get_fusion_skills(material_nb) {
	if(material_nb != 1 && material_nb != 2) return;
	
	var xhr = getXMLHttpRequest();
	 
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
		
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else {
				if(material_nb == 1)
					document.getElementById('fusion_skills1').innerHTML = xhr.responseText;
				else
					document.getElementById('fusion_skills2').innerHTML = xhr.responseText;
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
		}
	};

	xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=get_fusion_skills&m=" + material_nb, true);
	xhr.send(null);
}

function select_fusion_skill(material_nb, skill_slot, obj) {
	if(material_nb == 1) {
		fusion_skill1 = skill_slot;
		var elems = document.getElementById('fusion_skills1').getElementsByClassName('fusion_skill_link');
		for(var i = 0 ; i < elems.length ; i++) {
			elems[i].style.color = "white";
		}
	}
	else if(material_nb == 2) {
		fusion_skill2 = skill_slot;
		var elems = document.getElementById('fusion_skills2').getElementsByClassName('fusion_skill_link');
		for(var i = 0 ; i < elems.length ; i++) {
			elems[i].style.color = "white";
		}
	}
	
	obj.style.color = "cyan";
	get_fusion_result();
}

function get_fusion_result() {
	if(fusion_skill1 == null || fusion_skill2 == null) {
		return;
	}
	
	if(fusion_skill1 == fusion_skill2) {
		alert("Vous devez choisir deux skills différents.");
		return;
	}
	
	var xhr = getXMLHttpRequest();
	 
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
		
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "same_skills")) {
				alert("Vous ne pouvez pas fusionner un skill avec lui même.");
			} else {
				document.getElementById('fusion_menu2').innerHTML = xhr.responseText;
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
		}
	};

	xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=get_fusion_result&s1=" + fusion_skill1 + "&s2=" + fusion_skill2, true);
	xhr.send(null);
}

function fuse_skill() {
	if(fusion_skill1 == null || fusion_skill2 == null) {
		alert("Vous devez d'abord choisir les skills à fusionner.");
		return;
	}
	
	if(fusion_skill1 == fusion_skill2) {
		alert("Vous devez choisir deux skills différents.");
		return;
	}
	
	var xhr = getXMLHttpRequest();
	 
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
		
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "same_skills")) {
				alert("Vous ne pouvez pas fusionner un skill avec lui même.");
			} else if(string_starts_with(xhr.responseText, "forbidden_fusion")) {
				alert("Cette fusion est interdite.");
			} else if(string_starts_with(xhr.responseText, "fuse_ok")) {
				close_fusion_menu();
				reload_part('state');
				reload_part('skills');
				alert("La fusion a réussie.");
				
			} else {
				alert('Le serveur a retourné une valeur inconnue : ' + xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
		}
	};

	xhr.open("GET", "updateplayerstatus.php?sid=" + SID + "&mode=fuse_skills&s1=" + fusion_skill1 + "&s2=" + fusion_skill2, true);
	xhr.send(null);
}

function reset_fusion() {
	fusion_skill1 = null;
	fusion_skill2 = null;
	
	document.getElementById('fusion_skills1').innerHTML = '';
	document.getElementById('fusion_skills2').innerHTML = '';
	
	document.getElementById('fusion_menu2').innerHTML = '';
}