
/* Clip an image */
function clip_image(img, top, right, bottom, left) {
	img.style.clip='rect(' + top + 'px,' + right + 'px,' + bottom + 'px,' + left + ')';
}

function set_image_position(img, x, y, centerAsOrigin) {
	
	if(centerAsOrigin == true) {
		w = parseInt(img.width);
		h = parseInt(img.height);
		_x = parseInt(x);
		_y = parseInt(y);
		_x -= (w / 2);
		_y -= (h / 2);
		img.style.left = _x.toString() + "px";
		img.style.top = _y.toString() + "px";
	}
	else {
		img.style.left = x + "px";
		img.style.top = y + "px";
	}
}
