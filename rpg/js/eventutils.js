function moveElementAtMousePosition(id, e, offX, offY) {
	var elem = document.getElementById(id);
	var x = 0;
	var y = 0;
	
	if(e == undefined)
        e = event;

    if( e.pageX != undefined){ // gecko, konqueror,
        x = e.pageX;
        y = e.pageY;
    }else if(event != undefined && event.x != undefined && event.clientX == undefined){ // ie4 ?
        x = event.x;
        y = event.y;
    }else if(e.clientX != undefined ){ // IE6,  IE7, IE5.5
        if(document.documentElement){
            x = e.clientX + ( document.documentElement.scrollLeft || document.body.scrollLeft);
            y = e.clientY + ( document.documentElement.scrollTop || document.body.scrollTop);
        }else{
            x = e.clientX + document.body.scrollLeft;
            y = e.clientY + document.body.scrollTop;
        }
    }else{
        x = 0;
        y = 0;
    }
	
	x = x + offX;
	y = y + offY;
	
	if(elem.style){
        elem.style.left = x +"px";
        elem.style.top = y +"px";
    }else{
        elem.left = x;
        elem.top = y;
    }
}