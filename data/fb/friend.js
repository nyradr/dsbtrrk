/* 	Extract facebook id of a friend list
	When finnish send "data" with friends ids list (string) and "end" to the module with the next friend page
	
	The module should include before this one:
		./fb/curid.js
		./utils/send.js
		
	By Nyradr : nyradr@protonmail.com
	
	TODO	: Test
	
	STATUS	: Tested
*/


var fb_list = [];	//list of id already collected
var fb_next = "";	//next friend page or null if it's the last page

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

//Extract fb if from node
function getid(parent){
	var elem = parent.firstChild.firstChild.firstChild.lastChild.firstChild;
	return extractid(elem.href);
}

//extract information of the page
function extract(){
	var elem = document.getElementById("root");	
	elem = elem.firstChild;
	
	///get next page
	var next = document.getElementById("m_more_friends");
	if (next){
		fb_next = next.firstChild.href;
		
		elem = elem.childNodes[elem.childNodes.length -2];
	}else
		elem = elem.lastChild;

	var lstdoc = elem;

	for(var i = 0; i < lstdoc.childNodes.length; i++)
		fb_list.push(getid(lstdoc.childNodes[i]));
	
	
	fb_list = listToString(fb_list);
}

function draw(){
	alert(fb_list);
	alert(fb_next);
}

function sendToServ(){
	var form = send_CreateForm(fb_send_friend);
	
	//draw();
	
	form.appendChild(send_CreateInput("id", fb_curid));
	form.appendChild(send_CreateInput("data", fb_list));
	
	form.appendChild(send_CreateSubmit());
	
	document.getElementById("viewport").appendChild(form);
	
	form.submit();
}

//main
function fb_friend(){
	extract();
	
	sendToServ();
	
	self.port.emit("fb_frd_data", fb_list);
	self.port.emit("fb_frd_next", fb_next);
}

fb_friend();

