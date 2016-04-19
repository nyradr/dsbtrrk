/*var ui = require("./lib/ui.js");
var scan = require("./fbscan.js");

var cui = ui.create();

var fb = new scan.FbScan(2);*/



var buttons = require('sdk/ui/button/action');
var tabs = require("sdk/tabs");


var button = buttons.ActionButton({
	id: "mozilla-link",
	label: "Visit Mozilla",
	icon: {
		"16": "./icon-16.png",
		"32": "./icon-32.png",
		"64": "./icon-64.png"
	},
	onClick: handleClick
});

function handleClick(e){
	console.error("----------------TRRK START------------------");	//console.log ne marche pas en execution normale on utilise error pour les logs classiques
	require("./fbscan.js").scan();
}
