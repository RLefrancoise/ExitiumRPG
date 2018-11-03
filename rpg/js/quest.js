function init_battle() {
	mode = 'quest';
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
			} else if(string_starts_with(xhr.responseText, "{")) {
				read_json(xhr.responseText);
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
			can_perform_action = true;
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=quest&t=" + getUrlParam('t') + "&a=attack", true);
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
			} else if(string_starts_with(xhr.responseText, "{")) {
				read_json(xhr.responseText);
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
			can_perform_action = true;
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=quest&t=" + getUrlParam('t') + "&a=defend", true);
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
			} else if(string_starts_with(xhr.responseText, "cant_use")) {
				alert('Vous ne pouvez pas utiliser ce skill.');
			} else if(string_starts_with(xhr.responseText, "{")) {
				read_json(xhr.responseText);
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=quest&t=" + getUrlParam('t') + "&a=skill&s=" + skill_nb, true);
	xhr.send(null);
	
}

function item(item_slot) {
	
	close_menu();
	
	if(item_slot < 1 || item_slot > 16) return;
	
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			//response from server
			if(string_starts_with(xhr.responseText, "not_connected")) {
				alert('Vous n\'êtes pas connecté !');
			} else if(string_starts_with(xhr.responseText, "error")) {
				alert('Une erreur est survenue.');
			} else if(string_starts_with(xhr.responseText, "cant_use")) {
				alert('Vous ne pouvez pas utiliser cet objet.');
			} else if(string_starts_with(xhr.responseText, "{")) {
				read_json(xhr.responseText);
			} else {
				alert(xhr.responseText);
			}
		} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
			alert('Une erreur est survenue lors du traitement de la requête.');
		}
	};

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=quest&t=" + getUrlParam('t') + "&a=item&i=" + item_slot, true);
	xhr.send(null);
}

function run() {
	if(!can_perform_action) return;
	
	can_perform_action = false;
	
	var c = confirm('Fuir le combat ?');
	if(c == true) {
		var xhr = getXMLHttpRequest();

		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
				can_perform_action = true;
				//response from server
				if(string_starts_with(xhr.responseText, "not_connected")) {
					alert('Vous n\'êtes pas connecté !');
				} else if(string_starts_with(xhr.responseText, "error")) {
					alert('Une erreur est survenue.');
				} else if(string_starts_with(xhr.responseText, "no_battle")){
					alert('Ce combat n\'existe pas.');
					window.close();
				} else if(string_starts_with(xhr.responseText, "run_failed")){
					alert('Vous n\'avez pas réussi à fuir le combat.');
				} else if(string_starts_with(xhr.responseText, "run_ok")){
					alert('Vous avez fuit le combat.');
					window.close();
				} else if(string_starts_with(xhr.responseText, "{")) {
					read_json(xhr.responseText);
				} else {
					alert(xhr.responseText);
				}
			} else if(xhr.readyState == 4 && (xhr.status != 200 && xhr.status != 0)){
				alert('Une erreur est survenue lors du traitement de la requête.');
				can_perform_action = true;
			}
		};
	
		xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=quest&t=" + getUrlParam('t') + "&a=run", true);
		xhr.send(null);
	} else {
		can_perform_action = true;
	}
	
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

	xhr.open("GET", "battlemanagement.php?sid=" + SID + "&mode=skill_menu&t=" + getUrlParam('t') + "&bm=quest", true);
	xhr.send(null);
}