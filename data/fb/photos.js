/*	Scan one photos page
 * 
 * 	submodules to include before this one : 
 * 		./fb/curid.js
 *		./utils/send.js
 * 
 * 	By nyradr : nyradr@protonmail.com
 * 	
 * 	TODO : Test
 * 	STATUS : Tested (work)
*/


// datas
var fb_list = [];	// photos pages urls
var fb_next = "";	// next page url

// extract photos
function fb_phs_extractsPhotos(){
	var elem = document.getElementById("root");
	elem = elem.lastChild.firstChild.firstChild.firstChild.firstChild;
	
	for(var i = 0; i < elem.childNodes.length -1; i++){
		fb_list.push(elem.childNodes[i].href);
	}
}

// extract next page url
function fb_phs_extractNextpage(){
	var elem = document.getElementById("m_more_item");
	
	if(elem){
		elem = elem.firstChild;
		fb_next = elem.href;
	}
}

function fb_photos(){
	fb_phs_extractsPhotos();
	fb_phs_extractNextpage();
	
	//alert(fb_next);
	
	self.port.emit("fb_phs_imgs", fb_list);	//send back to plugin
	self.port.emit("fb_phs_next", fb_next);	
}

fb_photos();
