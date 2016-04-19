/*	Get the current page id
	For identification of the person loocked
	
	By nyradr : nyradr@protonmail.com
	
	TODO	: search("?") bug to correct
	
	STATUS	: TESTED (Work in normal case)
*/
var fb_curid;

//get the id
function fb_getAccId(){
	var url = document.URL;
	url = url.substr("https://m.facebook.com/".length);
	var end = url.search("/");
	
	if(end >= 0){
		url = url.substring(0, end);
		fb_curid = url;
	}
	else
		url = null;
	
	return url;
}

fb_getAccId();

//alert("curid load " + fb_curid);
