/*	Basic AJAX wrapper
*/

/*	Initialize ajax for every browser (even IE \o/)
*/
function initAjax(){
	var ajax = null;
	
	if (window.XMLHttpRequest)    //  Objet standard
		ajax = new XMLHttpRequest();     //  Firefox, Safari, ...
	else if (window.ActiveXObject)      //  Internet Explorer
		ajax = new ActiveXObject("Microsoft.XMLHTTP");
		
	return ajax;
}

function removeHostCode(text){
	//text = text.replace('\n<!-- Hosting24 Analytics Code -->', "");
	//text = text.replace('\n<script type="text/javascript" src="http://stats.hosting24.com/count.php"></script> ', "");
	//text = text.replace('\n<!-- End Of Analytics Code -->', "");
	var rm = text.substr(text.search("<!-- Hosting24"));
	
	return text.replace(rm, "");
}


/*	Process blocking http GET request
 * 	url 	: url of the requested page (with url arguments)
 * 	return	: string with the returned page
*/
function getRequestSync(url){
	var ajax = initAjax();
	
	ajax.open("GET", url, false);
	ajax.send(null);
	
	return removeHostCode(ajax.responseText);
}

/*	Process non blocking http GET request
 * 	url		: url of the requested page (with url arguments)
 * 	clb		: callcack function called when the page is loaded
 * 				function prototype must be : function name(ajax){...}
*/
function getRequestAsync(url, clb){
	var ajax = initAjax();
	
	ajax.onreadystatechange = function(){
		if(ajax.readyState == 4)
			clb(ajax);
	}
	
	ajax.open("GET", url, true);
	ajax.send(null);
}
