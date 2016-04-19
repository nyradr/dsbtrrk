/*	Extract people if from a like page
 * 
 *	TODO : Test
 *	STATUS : tested work
*/


var fb_l_likes = [];
var fb_l_next = "";

//Extract the fb id of the url
function extractid(str){
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

// get get peoples id
function fb_l_extract(root){
	root = root.firstChild;
	
	for(var i = 0; i < root.childNodes.length; i++){
		var elem = root.childNodes[i];
		elem = elem.firstChild.firstChild.firstChild
		elem = elem.firstChild.firstChild.firstChild.firstChild;
		
		elem = elem.childNodes[1].firstChild.firstChild.firstChild;
		
		var id = extractid(elem.href);
		fb_l_likes.push(id);
	}
}

// get next page
function fb_l_getnext(root){
	if(root){
		var elem = root.firstChild;
		fb_l_next = elem.href;
	}
}

function draw(){
	alert(self.options.img);
	alert(fb_l_likes);
}

// send back to server
function sendToServ(){
	var form = send_CreateForm(fb_send_like);
	
	form.appendChild(send_CreateInput("img", self.options.img));
	form.appendChild(send_CreateInput("data", fb_l_likes));
	
	form.appendChild(send_CreateSubmit());
	
	document.getElementById("viewport").appendChild(form);
	
	form.submit();
}

// base function
function fb_like(){
	var root = document.getElementById("root");
	root = root.firstChild.firstChild.firstChild;
	
	fb_l_extract(root.firstChild);
	
	if(root.childNodes.length > 1)
		fb_l_getnext(root.lastChild);
	
	fb_l_likes = listToString(fb_l_likes);
	
	//draw();
	sendToServ();
	
	self.port.emit("fb_lk_data", fb_l_likes);
	self.port.emit("fb_lk_next", fb_l_next);
}

fb_like();
