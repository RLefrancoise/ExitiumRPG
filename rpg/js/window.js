function open_centered_popup(page, name, largeur,hauteur,options) {
  var top=(screen.height-hauteur)/2;
  var left=(screen.width-largeur)/2;
  return window.open(page,name,"top="+top+",left="+left+",width="+largeur+",height="+hauteur+","+options);
}

function open_pve_window(sid, area, area_part) {
	return open_centered_popup('startbattle.php?sid=' + sid + '&mode=pve&a=' + area + '&p=' + area_part, 'Combat', 800, 600, 'menubar=no,statusbar=no,location=no');
}

function open_battle_window(link) {
	open_centered_popup(link, 'Combat', 800, 600, 'menubar=no,statusbar=no,location=no');
}

function open_pvp_request_window(link) {
	open_centered_popup(link, 'PVP', 400, 300, 'menubar=no,statusbar=no,location=no');
}

function open_karma_window(link) {
	var c = confirm('Terminer le RP ?');
	if(c == true) {
		open_centered_popup(link, 'Mettre fin au RP', 400, 300, 'menubar=no,statusbar=no,location=no');
	}
}

function open_event_register_window(link) {
	open_centered_popup(link, 'Inscription à un event', 400, 300, 'menubar=no,statusbar=no,location=no');
}

function open_quest_window(quest_id) {
	var c = confirm('Démarrer cette quête ?');
	if(c == true) {
		open_centered_popup('./startquest.php?q=' + quest_id, 'Démarrer une quête', 400, 300, 'menubar=no,statusbar=no,location=no');
	}
}

function open_riddle_window(riddle_id) {
	open_centered_popup('./viewriddle.php?r=' + riddle_id, 'Répondre à une énigme.', 400, 300, 'menubar=no,statusbar=no,location=no');
}

function open_register_quest_window(topic_id) {
	var c = confirm('Participer à cette quête ?');
	if(c == true) {
		open_centered_popup('./questmanagement.php?mode=register&t=' + topic_id, 'Inscription à la quête.', 400, 300, 'menubar=no,statusbar=no,location=no');
	}
}

function open_close_quest_window(topic_id) {
	var c = confirm('Clôturer les inscriptions de cette quête ?');
	if(c == true) {
		open_centered_popup('./questmanagement.php?mode=close&t=' + topic_id, 'Cloture des inscriptions de la quête.', 400, 300, 'menubar=no,statusbar=no,location=no');
	}
}

/* rpg menu */
function show_rpgmenu() {
	hide_chatbox();
	
	if(document.getElementById('rpgmenu')) {
		document.getElementById('rpgmenu').style.display = "inline";
		document.getElementById('rpgmenu_div').style.top = "50px";
	}
}

function hide_rpgmenu() {
	if(document.getElementById('rpgmenu')) {
		document.getElementById('rpgmenu').style.display = "none";
		document.getElementById('rpgmenu_div').style.top = "200px";
	}
}

function toggle_rpgmenu_display() {
	if(document.getElementById('rpgmenu')) {
		if(document.getElementById('rpgmenu').style.display == "inline")
			hide_rpgmenu();
		else
			show_rpgmenu();
	}
}

/* Chatbox */
function show_chatbox() {
	hide_rpgmenu();
	
	if(document.getElementById('chatbox')) {
		document.getElementById('chatbox').style.display = "inline";
		document.getElementById('chatbox_div').style.top = "150px";
	}
}

function hide_chatbox() {
	if(document.getElementById('chatbox')) {
		document.getElementById('chatbox').style.display = "none";
		document.getElementById('chatbox_div').style.top = "400px";
	}
}

function toggle_chatbox_display() {
	if(document.getElementById('chatbox')) {
		if(document.getElementById('chatbox').style.display == "inline")
			hide_chatbox();
		else
			show_chatbox();
	}
}

function put_menu_and_chatbox() {
	var html = 	'<div id="rpgmenu_div" style="position:fixed;top:200px;left:-30px;z-index:5">\
					<iframe id="rpgmenu" src="viewrpgmenu.php" width="262" height="604" style="display:none;float:left;border:none"></iframe>\
					<a onmouseover="javascript:show_menu_button()" onmouseout="javascript:hide_menu_button()" href="javascript:toggle_rpgmenu_display()" style="width:45px;height:102px;margin:auto"><img src="images/ongletMenu.png" /></a>\
				</div>\
				<div id="chatbox_div" style="position:fixed;top:400px;left:-30px;z-index:5">\
					<iframe id="chatbox" src="chat/index.php" width="800" height="500" style="display:none;float:left"></iframe>\
					<a onmouseover="javascript:show_chat_button()" onmouseout="javascript:hide_chat_button()" href="javascript:toggle_chatbox_display()" style="width:45px;height:102px;margin:auto"><img src="images/ongletChat.png" /></a>\
				</div>';
				
	document.write(html);
}

function show_menu_button() {
	if(document.getElementById('rpgmenu').style.display == "none") {
		document.getElementById('rpgmenu_div').style.left = "0px";
	}
}

function hide_menu_button() {
	if(document.getElementById('rpgmenu').style.display == "none") {
		document.getElementById('rpgmenu_div').style.left = "-30px";
	}
}

function show_chat_button() {
	if(document.getElementById('chatbox').style.display == "none") {
		document.getElementById('chatbox_div').style.left = "0px";
	}
}

function hide_chat_button() {
	if(document.getElementById('chatbox').style.display == "none") {
		document.getElementById('chatbox_div').style.left = "-30px";
	}
}