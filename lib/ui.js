/*	Représents the basic ui of trrk
	Control panel, configuration
	
	By Nyradr : nyradr@protonmail.com
*/

//requirement
var buttons = require("sdk/ui/button/toggle");
var panels = require("sdk/panel");

//ui vars
var ui_button;
var ui_panel;

//config vars
var cfg_isActive;

//When ui_button is clicked
function handleChange(state){
	if(state.checked){
		ui_panel.show({
			position: ui_button
		});
	}
}

//When the panel is hidden
function handleHide(){
	ui_button.state("window", {checked: false});
}

//When the panel send "cs" message
function onChangeState(mess){
	console.log("Change " + mess);
}

function isActive(){
	return cfg_isActive;
}
exports.isActive = isActive;

function create(){
	cfg_isActive = true;

	ui_button = buttons.ToggleButton({
		id: "trrk_but",
		label: "Trrk",
		icon: {"16": "./icon-16.png"},
		onChange: handleChange
	});

	ui_panel = panels.Panel({
		contentURL: "./ui/panel.html",
		contentScriptFile: "./ui/panel.js",
		onHide: handleHide
	});
	
	ui_panel.port.on("cs", onChangeState);
}
exports.create = create;
