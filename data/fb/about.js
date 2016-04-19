/*	Get the information on the "about" page of facebook
	To insert in the facebook "about" page, mobile version
	
	Send "end" to the module when finnish, no arguments
	
	The module should include before this one:
		./fb/curid.js
		./utils/send.js
	
	By Nyradr : nyradr@protonmail.com
	
	TODO	: add the other information (only generals for the moment)
				correction of search bug
				
	STATUS	: Tested (Work)
				bug on url.search("?"), use only on pure about url
*/

	//part name
var name = "";
	
	//generals
var birth = "";
var sexe = "";
var langues = "";
	
	//living places
var living = [];
var living_url = [];

	// workplace
var workplace = [];
var workplace_url = [];

//obtain information of an id (callback do the specific extraction job)
function getid(id, callback){
	var basic = document.getElementById(id);	//obtain the base with all the general informations
	
	if(basic){
		basic = basic.firstChild;	//go to the first node
		basic = basic.lastChild;	//go to the last node (second <div>)
		
		for(var i = 0; i < basic.childNodes.length; i++){
			if(basic.childNodes[i].title != null){
				try{
					var nodeval = basic.childNodes[i].firstChild.firstChild.firstChild.lastChild.firstChild; //get the node value
					var title = basic.childNodes[i].title;
					callback(title, nodeval);
				}catch(err){
					console.error("fb get by id error" + basic.childNodes[i].title);
				}
				
			}
		}
	}
};

function getiddes(id, callback){
	var basic = document.getElementById(id);	//obtain the base with all the general informations
	
	if(basic){
		basic = basic.firstChild;	//go to the first node
		basic = basic.lastChild;	//go to the last node (second <div>)
		
		for(var i = 0; i < basic.childNodes.length; i++){
			var elem = basic.childNodes[i].firstChild;
			
			if(elem.title != null){
				try{
					var nodeval = elem.firstChild.firstChild.firstChild.lastChild.firstChild.firstChild; //get the node value
					var title = elem.title;
					callback(title, nodeval);
				}catch(err){
					console.error("fb get by id error " + elem.title);
				}
				
			}
		}
	}
}

//Callback for getting the coords informations
//TODO
function coords (title, nodeval){
	if(title == "Ville actuelle" || title == "Ville d'origine"){
		var name = nodeval.innerHTML;
		if(name.search(',') >= 0)
			name = name.substr(0, name.search(','));
		
		living.push(name);
		living_url.push(nodeval.href);
	}
};

//Get the Coord information
//TODO
function getCoords(){
	getiddes("living", coords);
};


//Callback for getting the Generals informations
function generals(title, nv){
	var nodeval = nv.innerHTML;
	
	switch(title){
		case "Langues":
			langues = nodeval;
			break;
			
		case "Date de naissance":
			birth = nodeval;
			break;
			
		case "Sexe":
			sexe = nodeval;
			break;
	}
};

//Get the Generals informations
function getGenerals(){
	getid("basic-info", generals);
};

function getPlace(id){
	var root = document.getElementById(id);
	
	if(root){
		root = root.firstChild.lastChild;
		root = root.childNodes;
		
		for(var i = 0; i < root.length; i++){
			
			var elem = root[i].firstChild.childNodes[1].firstChild.firstChild.firstChild;
			if(elem.nodeName == "SPAN")
				elem = elem.firstChild;
			
			var name = elem.innerHTML;
			if(name.search(',') >= 0)
				name = name.substr(0, name.search(','));
			
			workplace.push(name);
			workplace_url.push(elem.href);
		}
	}
}

function getScol(){
	getPlace("education");
}

function getWork(){
	getPlace("work");
}

function getName(){
	var elem = document.getElementById("root");
	elem = elem.firstChild.firstChild;
	elem = elem.childNodes[1].firstChild.childNodes[1];
	elem = elem.firstChild.firstChild;
	
	name = elem.innerHTML;
}



//Draw informations in alert (Debug only)
function draw(){
	alert("id : " + fb_curid);
	alert("sexe : " + sexe);
	alert("birth : " + birth);
	alert("lang : " + langues);
	alert("work : " + workplace);
	alert("workurl : " + workplace_url);
	alert("living : " + living);
	alert("livingurl : " + living_url);
};

//Send collected informations to the server
function sendToServ (){
	var form = send_CreateForm(fb_send_about);
	
	form.appendChild(send_CreateInput("id", fb_curid));
	form.appendChild(send_CreateInput("name", name));
	form.appendChild(send_CreateInput("sexe", sexe));
	form.appendChild(send_CreateInput("birth", birth));
	form.appendChild(send_CreateInput("lang", langues));
	form.appendChild(send_CreateInput("living", listToString(living)));
	form.appendChild(send_CreateInput("work", listToString(workplace)));
	
	form.appendChild(send_CreateSubmit());
	
	document.getElementById('viewport').appendChild(form);
	
	form.submit();
};

//main for getting facebook about page informations
function fb_about(){
	getGenerals();
	getCoords();
	getName();
	getWork();
	getScol();
	
		//draw();
	sendToServ();
	
	self.port.emit("fb_ab_work", workplace_url);
	self.port.emit("fb_ab_place", living_url);
}

fb_about();	//start script
