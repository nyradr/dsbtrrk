var person;			// represent searched person
var selectPers;		// represent selected person (on the graph)
var sig;			// sigma graph

/*	get the result (or the only one) of an XPath query on XML document
*/
function xpath1res(xpath, xml){
	return xml.evaluate(xpath, xml, null, XPathResult.ANY_TYPE, null);
}

/*	get table of all result of an XPath query on XML document
*/
function xpathares(xpath, xml){
	var resx = xml.evaluate(xpath, xml, null, XPathResult.ANY_TYPE, null);
	var rest = [];
	
	while(e = resx.iterateNext())
		rest.push(e);
	return rest;
}

/*	Set the search error message
 * 	build it under the id : "search-error"
 * 	xml	: xml document representing the person
 * 	id	: person id
*/
function visibleSearchError(xml, id){
	var doc = document.getElementById("search-error");	// get the DOM element of the div
	
	if(xml == null && id.length > 0)		// no valid xml and an id -> error
		doc.style.visibility = "visible";
	else
		doc.style.visibility = "hidden";
}

/*	Build the rane indication
 * 	id	: id where to build it
*/
function buildNoteRange(id){
	var graphInfoNode = document.getElementById("graph-info-note");
	
	graphInfoNode.max = person.getMaxRef();
	graphInfoNode.value = "1";
	
	graphInfoNode.addEventListener("input", function (e){	
		document.getElementById("graph-info-note-val").innerHTML = "(" + e.target.value + ")";
		applyNoteFilter();
	});
}

function getNoteValue(){
	var graphInfoNote = document.getElementById("graph-info-note");
	
	return parseInt(graphInfoNote.value);
}

function getFriendC(){
	var graphInfoFriend = document.getElementById("graph-info-friend");
	return graphInfoFriend.checked;
}

function getLikedC(){
	var graphInfoLiked = document.getElementById("graph-info-liked");
	return graphInfoLiked.checked;
}

function getLikeonC(){
	var graphInfoLikeon = document.getElementById("graph-info-likeon");
	return graphInfoLikeon.checked;
}


function applyNoteFilter(){
	
	sig = buildSigma(person, 
		"graph-container",
		onSigmaEvent
		);
		
	buildRefs(person, "refs");
}

/*	callback when sigma event if fired
 * 	e	: sigma event
*/
function onSigmaEvent(e){
	if(e.data.node.id == "nc")
		selectPers = person;
	else
		selectPers = new Pers(e.data.node.label, true);
	selectPers.drawPersInfo("graph-info-pinf");
}

/*	Build new sigma graphic and delete old elements in the container
 * 	pers	: person object
 * 	note	: graph presision filter. 0 -> all; max -> only 1 element
 * 	cont	: container id
 * 	onEvent	: sigma callback function
*/
function buildSigma(pers, cont, onEvent){
	var contE = document.getElementById(cont)
	contE.innerHTML = "";
	
	var g = pers.buildSigma(
		getNoteValue(), 
		getFriendC(), 
		getLikedC(), 
		getLikeonC()
		);
	
	var sig = new sigma({
		graph: g,
		renderer: {
			container: contE,
			type: "canvas"
		}
	});
	
	sig.bind("overNode clickNode doubleClickNode rightClickNode", onEvent);
	
	return sig;
}

function getPersonIdstr(url){
	if(url){
		var persInfo = getRequestSync(url);
		
		if(persInfo.indexOf("NOTHING") < 0){			
			if(window.DOMParser){
				var parser = new DOMParser();
				return parser.parseFromString(persInfo, "text/xml");;
			}else{
				var doc = new ActiveXObject("Microsoft.XMLDOM");
				doc.async = false;
				return doc.loadXML(persInfo);
			}
		}
	}
	
	return null;
}

/*	Build list of referenced persons
*/
function buildRefs(pers, id){
	var doc = document.getElementById(id);
	doc.innerHTML = "";
	
	if(pers.referenced){
		var url = "getpersidstr.php?id=";
		var args = "";
		
		var filtered = pers.filterRefInf(
			getNoteValue(),
			getFriendC(),
			getLikedC(),
			getLikeonC()
			);
		
		filtered.forEach(function (r){
			if(args.length > 0)
				args += ",";
			args += r.id;
		});
		
		var xml = getPersonIdstr(url + args);
		var refsid = xpathares("/idstr/id" , xml);
		
		refsid.forEach(function (ref){
			
			var elem = document.createElement("div");
			var link = document.createElement("a");
			link.href = "persons.php?id=" + ref.innerHTML;
			link.innerHTML = ref.innerHTML;
			elem.appendChild(link);
			
			doc.appendChild(elem);
		});
	}
}
