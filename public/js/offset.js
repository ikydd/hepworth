function findPos(obj) {
	var curleft = curtop = 0;
	
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
		} while ( obj = obj.offsetParent );
	
		return [curleft,curtop];
	}
}

function scrollEvent(){
  var side = document.getElementById('side-menu');
  if(side){
    fixElement(side);
  }
}

function fixElement(obj)
{	
	var diff = window.pageYOffset - 90;
	if ( diff > 0 ) {
    obj.style.position = 'fixed';
		obj.style.top = '38.5px';
	}	else {
    obj.style.position = 'static';
		obj.style.top = '0px';
	}
}

window.onscroll = scrollEvent;