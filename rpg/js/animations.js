/*function play_animation(img_id, gif_name, duration, width, height) {
	
	if(document.getElementById(img_id)) {
		document.getElementById(img_id).src = gif_name;
		document.getElementById(img_id).style.width = width + "px";
		document.getElementById(img_id).style.height = height + "px";
		document.getElementById(img_id).style.top = "50%";
		document.getElementById(img_id).style.left = "50%";
		document.getElementById(img_id).style.marginLeft = (width / 2 * -1).toString() + "px";
		document.getElementById(img_id).style.marginTop = (height / 2 * -1).toString() + "px";
		document.getElementById(img_id).style.visibility = 'visible';
		
		setTimeout(function() { stop_animation(img_id); }, duration);
	}
}

function stop_animation(img_id) {

	if(document.getElementById(img_id)) {
		document.getElementById(img_id).style.visibility = 'hidden';
	}
}

function play_animation2(img_id, anim_path, frame_number, delay, width, height) {
	if(document.getElementById(img_id)) {
	
		document.getElementById(img_id).style.width = width + "px";
		document.getElementById(img_id).style.height = height + "px";
		document.getElementById(img_id).style.top = "50%";
		document.getElementById(img_id).style.left = "50%";
		document.getElementById(img_id).style.marginLeft = (width / 2 * -1).toString() + "px";
		document.getElementById(img_id).style.marginTop = (height / 2 * -1).toString() + "px";
		document.getElementById(img_id).style.visibility = 'visible';
		
		next_frame(img_id, anim_path, 1, frame_number, delay);
	}
}

function next_frame(img_id, anim_path, current_frame, frame_number, delay) {
	if(current_frame > frame_number) { stop_animation(img_id); return; }
	
	if(document.getElementById(img_id)) {
		document.getElementById(img_id).src = anim_path + current_frame + ".png";
		setTimeout(function() { next_frame(img_id, anim_path, current_frame + 1, frame_number, delay); }, delay);
	}
}*/







function play_animation4(canvas_id, img_path, frame_number, w, h, freq) {
	alert(canvas_id + ' ' + img_path + ' ' + frame_number + ' ' + w + ' ' + h + ' ' + freq);
	//alert(stage);
	
	var canvas = CE.defines(canvas_id).
			extend(Animation).
			ready(function() {
				canvas.Scene.call("AnimScene");
			});
	   

	canvas.Scene.new({
		name: "AnimScene",
		materials: {
			images: {
				//chara: "images/chara.png"
				anim_img: img_path
			}
		},
		ready: function() {
			//alert(stage);
			
		   var el = this.createElement(),
				animation = canvas.Animation.new({
					images: "anim_img",
					animations: {
						walk: {
							frames: [0, frame_number - 1],
							size: {
								width: w,
								height: h
							},
							frequence: freq
						}
					}
				});
			
			animation.add(el);
			animation.play("walk", "loop");
			
			this.getStage().append(el);
		},
		render: function() {
			
			this.getStage().refresh();
		}
	});

}

function play_animation5(div, img, frame_number, delay, width, height) {

	if(document.getElementById(div)) {
		document.getElementById(div).style.width = width + "px";
		document.getElementById(div).style.height = height + "px";
		document.getElementById(div).style.top = "50%";
		document.getElementById(div).style.left = "50%";
		document.getElementById(div).style.marginLeft = (width / 2 * -1).toString() + "px";
		document.getElementById(div).style.marginTop = (height / 2 * -1).toString() + "px";
		
		document.getElementById(div).style.backgroundImage = "url(" + img + ")";
		
		var image = new Image();
		
		image.onload = function() {
			document.getElementById(div).style.visibility = 'visible';
			
			next_sprite(document.getElementById(div), 1, frame_number, delay, width, height);
		}
		
		image.src = img;
	}
}

function next_sprite(div, current_frame, frame_number, delay, width, height) {
	if(current_frame > frame_number) {
		div.style.visibility = 'hidden';
		return;
	}
	
	var x = width * ((current_frame - 1) % 5);
	var y = height * Math.floor(((current_frame -1) / 5));
	var offsets = '-' + x + 'px -' + y + 'px';
    div.style.backgroundPosition = offsets;
	
	setTimeout(function() { next_sprite(div, current_frame + 1, frame_number, delay, width, height); }, delay);
}