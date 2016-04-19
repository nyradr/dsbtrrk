var butt = document.getElementById("m");
butt.addEventListener("click", function f(e){
	self.port.emit("cs", "end");
}, false);
			
self.port.emit("m", "yop");

document.getElementById("l").innerHTML = "end";
