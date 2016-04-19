/*	Extract information from photo page
 * 
 * 	TODO : Tagged and place
 * 	STATUS : test, work but can block tab an can close it
*/

var fb_datepost = ""
var fb_img = "";
var fb_like = "";


function fb_extractId(str){
	var id = "";
	var i = 1;
	
	if(str.search("https") >= 0)
		i = "https://m.facebook.com/".length;
	
	while(str[i] != "?"){
		id += str[i];
		i++;
	}
	return id;
}

// extract image url
function fb_ph_extractUrl(){
	var elem = document.getElementById("root");
	elem = elem.firstChild.firstChild.firstChild.firstChild.firstChild.firstChild;
	
	fb_img = elem.src;
}

// extract post photo date
function fb_ph_extractDate(){
	var elem = document.getElementById("voice_replace_id");
	elem = elem.parentNode.parentNode.parentNode;
	elem = elem.childNodes[1];
	elem = elem.firstChild.firstChild.firstChild;
	
	elem = elem.firstChild;
	
	fb_datepost = elem.innerHTML;
}

function fb_ph_extractLike(){
	try{
	
		var elem = document.getElementById("root");
		elem = elem.firstChild.firstChild;
		elem = elem.lastChild.lastChild;
		elem = elem.firstChild.firstChild.firstChild.firstChild;
	
		fb_like = elem.href;
	}catch(err){
		console.error("Err like");
	}
}


function draw(){
	alert(self.options.idpers);
	alert(fb_datepost);
	alert(fb_img);
}

// send data to server
function fb_sendToServ(){
	var form = send_CreateForm(fb_send_photo);
	
	form.appendChild(send_CreateInput("idpers", self.options.idpers));
	form.appendChild(send_CreateInput("date", fb_datepost));
	form.appendChild(send_CreateInput("img", fb_img));
	
	form.appendChild(send_CreateSubmit());
	
	document.getElementById('viewport').appendChild(form);
	
	form.submit();
}

// main function for extracting photos informations
function fb_photo(){
	fb_ph_extractDate();
	fb_ph_extractLike();
	fb_ph_extractUrl();
	
	//draw();
	fb_sendToServ();
	
	self.port.emit("fb_ph_img", fb_img);
	self.port.emit("fb_ph_like", fb_like);
	//self.port.emit("fb_ph_tag", fb_tagged);
}

fb_photo();



