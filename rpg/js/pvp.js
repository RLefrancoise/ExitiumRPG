function init_battle() {
	mode = 'pvp';
	update_timer = setInterval(function() { update_battle(); }, update_time); //update every second
	
	//update_battle();
}

function update_battle() {
	if(!update) return;
	
	update = false;
	
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			
			if(battle_over) return;
			
			update = true;
			
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				show_pvp_info('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				show_pvp_info('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "no_battle")) {
				//alert("Ce combat n'existe pas.");
				show_pvp_info('Fin du combat ou combat non trouvé.');
				update = false;
				window.clearInterval(update_timer);
				//window.close();
				setTimeout(function() {window.close(); }, close_time);
			} else if(string_starts_with(xhr.responseText, "battle_over")) {
				update = false;
				window.clearInterval(update_timer);
				//window.close();
			} else if(string_starts_with(xhr.responseText, "delete_ok")) {
				update = false;
				window.clearInterval(update_timer);
				//window.close();
			} else if(string_starts_with(xhr.responseText, "interrupted")) {
				show_pvp_info('Le combat a été interrompu suite à une erreur.');
				update = false;
				window.clearInterval(update_timer);
				setTimeout(function() {window.close(); }, close_time);
				//window.close();
			} else if(string_starts_with(xhr.responseText, "cancelled")) {
				show_pvp_info('Le combat a été interrompu suite car un des joueurs n\' à pas jouer pendant un certain temps.');
				update = false;
				window.clearInterval(update_timer);
				setTimeout(function() {window.close(); }, close_time);
				//window.close();
			} else if(string_starts_with(xhr.responseText, "update_ok")) {
				
			} else if(string_starts_with(xhr.responseText, "{")) {
				read_json(xhr.responseText);
			} else {
				alert(xhr.responseText);
				//update = false;
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			update = true;
			show_pvp_info('Erreur lors de la requête.');
			alert(xhr.responseText);
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=get_pvp_status", true);
	xhr.send(null);
}


function attack() {
	if(!can_perform_action) return;
	
	can_perform_action = false;
	
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			can_perform_action = true;
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "no_battle")) {
				alert("Ce combat n'existe pas.");
				update = false;
				window.clearInterval(update_timer);
				window.close();
			} else if(string_starts_with(xhr.responseText, "action_ok")) {
				//update_battle();
			} else if(string_starts_with(xhr.responseText, "played_already")) {
				alert("Vous avez déjà joué votre tour.");
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
			can_perform_action = true;
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=pvp&a=attack", true);
	xhr.send(null);
}

function defend() {
	if(!can_perform_action) return;
	
	can_perform_action = false;
	
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			can_perform_action = true;
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "no_battle")) {
				alert("Ce combat n'existe pas.");
				update = false;
				window.clearInterval(update_timer);
				window.close();
			} else if(string_starts_with(xhr.responseText, "action_ok")) {
				//update_battle();
			} else if(string_starts_with(xhr.responseText, "played_already")) {
				alert("Vous avez déjà joué votre tour.");
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
			can_perform_action = true;
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=pvp&a=defend", true);
	xhr.send(null);
}

function skill(skill_nb) {
	
	close_menu();
	
	if(skill_nb < 1 || skill_nb > 4) return;
	
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "no_battle")) {
				alert("Ce combat n'existe pas.");
				update = false;
				window.clearInterval(update_timer);
				window.close();
			} else if(string_starts_with(xhr.responseText, "cant_use")) {
				alert('Vous ne pouvez pas utiliser ce skill.');
			} else if(string_starts_with(xhr.responseText, "action_ok")) {
				//update_battle();
			} else if(string_starts_with(xhr.responseText, "played_already")) {
				alert("Vous avez déjà joué votre tour.");
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=pvp&a=skill&s=" + skill_nb, true);
	xhr.send(null);
	
}

function item(item_slot) {
	close_menu();
	
	alert('action impossible en pvp');
}

function run() {
	if(!can_perform_action) return;
	
	alert('action impossible en pvp');
}



function open_skill_menu(event) {
	if(!can_perform_action) return;
	
	can_perform_action = false;
	
	//create ajax object to request menu according to item
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
				can_perform_action = true;
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
				can_perform_action = true;
			} else {
				//moveElementAtMousePosition('menu', event, 0, -100);
				document.getElementById('menu').style.left = "225px";
				document.getElementById('menu').style.top = "20px";
				document.getElementById('menu').innerHTML = xhr.responseText;
				document.getElementById('menu').style.visibility = 'visible';
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
			can_perform_action = true;
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=skill_menu&bm=pvp", true);
	xhr.send(null);
}