var isSpawned = false;
var map = undefined;
var player = undefined;

var canUpdate = true;



/*var map = new Map("map1");
var player = new Character("yuki.png", 7, 0, DIRECTION.DOWN, {
		name : "Yuki Kazuki",
		admin: true,
		hp : 20,
		max_hp : 50,
		fp : 30,
		max_fp : 50
	});
map.addCharacter(player);
map.setPlayer(player);

map.addCharacter(new Character("vincent.png", 7,7, DIRECTION.DOWN, {
		name : "Vincent Blackwood",
		hp : 45,
		max_hp : 50,
		fp : 10,
		max_fp : 50
	}));
	
map.addCharacter(new Character("tsukasa.png", 7,9, DIRECTION.DOWN, {
		name : "Tsukasa Watanabe",
		hp : 90,
		max_hp : 100,
		fp : 60,
		max_fp : 100
	}));
	*/

//window.onload = function() {
function init_map() {
	var canvas = document.getElementById('canvas');
	var ctx = canvas.getContext('2d');
	ctx.font = 'normal 10pt Arial';
	
	// Gestion du clavier
	window.addEventListener('keyup', function(event) { Key.onKeyup(event); }, false);
	window.addEventListener('keydown', function(event) { Key.onKeydown(event); }, false);
	
	//rendering loop (25 fps)
	setInterval(function() {
		if(map && map.isReady()) map.draw(ctx);
	}, 40);
	
	//update loop (25 times per sec)
	setInterval(function() {
		//player realtime events
		if(player && map && map.isReady())
		{
			//local realtime events
			if(Key.isDown(Key.UP) || Key.isDown(Key.Z)) player.deplacer(DIRECTION.UP, map);
			if(Key.isDown(Key.DOWN) || Key.isDown(Key.S)) player.deplacer(DIRECTION.DOWN, map);
			if(Key.isDown(Key.LEFT) || Key.isDown(Key.Q)) player.deplacer(DIRECTION.LEFT, map);
			if(Key.isDown(Key.RIGHT) || Key.isDown(Key.D)) player.deplacer(DIRECTION.RIGHT, map);
			
			//network
			network_update();
		}
		
	}, 40);

	window.onkeydown = function(event) {
		var e = event || window.event;
		var key = e.which || e.keyCode;
		
		switch(key) {
			case Key.Space:
				if(player) player.displayData = !player.displayData;
				break;
			default : 
				//alert(key);
				// Si la touche ne nous sert pas, nous n'avons aucune raison de bloquer son comportement normal.
				return true;
		}

		return false;
	}
	
	self_spawn();
}

function self_spawn() {
	$.get('maptest.php', { sid: SID, mode: 'self_spawn'})
		.done(function(data) {
			var data = jQuery.parseJSON(data);
			
			//load map
			map = new Map(data.map);

			//create player
			player = new Character(data.charset, data.position.x, data.position.y, DIRECTION.DOWN, data.accountData);
			map.addCharacter(player);
			map.setPlayer(player);
			
			isSpawned = true;
		})
		.fail(function() {
			alert("Failed to spawn self");
		});
}

/*
* Update player (store position in database)
*/
function network_update() {
	if(!canUpdate) return;
	
	canUpdate = false;
	
	$.get('maptest.php', { sid: SID, mode: 'update', x: player.x, y: player.y })
		.done(function(data) {
			if(string_starts_with(data, 'error')) {
				alert('failed to save position');
				return;
			}
			
			//iterate over characters on map, and add them if not on map, or update them if needed
			data = jQuery.parseJSON(data);
			
			$.each(data.characters, function(index, value) {
				
				//if character not on map, put him
				if(!map.characterIsOnMap(value.id)) {
					map.addCharacter(new Character(value.charset, value.x, value.y, DIRECTION.DOWN, {
						id: value.id,
						name: value.name,
						admin: value.admin,
						hp : value.hp,
						max_hp : value.max_hp,
						fp : value.fp,
						max_fp : value.max_fp
					}));
				}
				//if already on map, update him
				else {
					var c = map.getCharacter(value.id);
					
					if(value.x < c.x) {
						c.deplacer(DIRECTION.LEFT, map);
					}
					if(value.x > c.x) {
						c.deplacer(DIRECTION.RIGHT, map);
					}
					if(value.y < c.y) {
						c.deplacer(DIRECTION.UP, map);
					}
					if(value.y > c.y) {
						c.deplacer(DIRECTION.DOWN, map);
					}
					
				}
				
			});
			
			
			canUpdate = true;
		})
		.fail(function() {
			alert("Failed to save position");
		});
}