/*	Scan the main photo page to get the "Afficher tout url"
 * 	
 * 	STATUS : Tested (work)
*/

var fb_allurl = "";

function fb_getallurl(){
    var elem = document.getElementById("root");
    
    elem = elem.lastChild.firstChild;
    
    elem = elem.lastChild;
    elem = elem.firstChild;
    
    fb_allurl = elem.href;
    
    self.port.emit("fb_all_url", fb_allurl);
}

fb_getallurl();
