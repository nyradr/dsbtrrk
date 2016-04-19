/*	Scanner fb for trrk

	By nyradr : nyradr@protonmail.com

	TODO 	: Test multi id scan,
	* 			add photos page scan
	STATUS 	: Tested (Works)
*/

//requirements
var tabs = require("sdk/tabs");

//constants
var mfb = "https://m.facebook.com/";	//base url for facebook mobile

var curfb = null;	//pointeur sur une instance de fbscan

function FbScan(deeps){
	this.loged = false;		//indique si on est loger
	
	this.deeps = deeps;		//profondeur de la recherche
	this.curdeep = 0;
	
	this.idstack = [];		//id déja traiter
	this.istack = 0;		//avancement dans la stack
	this.ideep = 0			//nombre de gens dans cette profondeur
	this.max = 5;
	
	this.placeScanned = [];	// stack of place scanned
	this.workScanned = [];	// stack of work scanned
	
	this.curid;				//id en cours de traitement
	
	curfb = this;
}

// add id to the spoted id list
FbScan.prototype.addId = function(id){

	if(id.search("profile.php") < 0)
		if(this.idstack.indexOf(id) == -1){
			this.idstack.push(id);
			console.error("Ajout " + id + " wait stack");
		}
}

function strToList(str){
	var lst = [];
	var tmp = "";
	
	for(var i = 0; i < str.length; i++){
		if(str[i] == '\\'){
			lst.push(tmp);
			tmp = "";
		}else
			tmp += str[i];
	}
	
	lst.push(tmp);
	
	return lst;
}

// add id (from url) to the spoted id list
FbScan.prototype.addIdListStr = function(list){
	var tmp = "";
	
	for(var i = 0; i < list.length; i++){
		if(list[i] == '\\'){
			this.addId(tmp);
			tmp = "";
		}else
			tmp += list[i]; 
	}
	
	this.addId(tmp);
}

// get the next id to scan
FbScan.prototype.peekId = function(){
	if(this.istack < this.idstack.length && this.istack < this.max){	// limitation du nombre de scan
		this.istack++;
		
		if(this.istack == this.ideep){
			this.deep = this.idstack.length;
			this.curdeep++;
		}
		
		return this.idstack[this.istack -1];
	}else{
		return null;
	}
}

// get the currend scaned id
FbScan.prototype.getId = function(){
	if(this.istack < this.idstack.length)
		return this.idstack[this.istack];
};

/*	Login to fb
*/
FbScan.prototype.login = function(mail, pass){
	tabs.open({
		url: "https://www.facebook.com",
		onReady: function(tab){
			tab.attach({
				contentScriptFile: "./fb/login.js",
				contentScriptOptions:{
					mail: mail,
					pass: pass
				}
			});
		},
		onAttach: function(worker){
			worker.port.on("end", function(){
				
			});
		}
	});
	
	this.loged = true;
};

/*  Recursivly scan like page
*/
FbScan.prototype.like = function(url, img, lsov){
	var end = false;
	
	tabs.open({
		url: url,
		
		onReady: function(tab){
			if(tab.url == url){
				var worker = tab.attach({
					contentScriptFile: ['./utils/send.js', './fb/like.js'],
					contentScriptOptions: {
						img: img
					}
				});
				
				worker.port.on("fb_lk_data", function(mess){
					console.error("fb_lk_data : " + mess);
					curfb.addIdListStr(mess);
				});
				
				worker.port.on("fb_lk_next", function(mess){
					console.error("fb_lk_next : " + mess);
					if(mess != "" && mess != null)
						curfb.like(mess, img, lsov);
					else{
						tab.close();
						lsov();
						end = true;
					}
				});
				
			}else{
				tab.close();
				
				if(end)
					lsov();
			}
		}
	});
}

/*	Scan photo page
*/
FbScan.prototype.photo = function(murl, idper, spov){
	
	tabs.open({
		url: murl,
		
		onReady: function(tab){
			if(tab.url.search(mfb) >= 0){
				var worker = tab.attach({
					contentScriptFile: ["./utils/send.js", "./fb/photo.js"],
					contentScriptOptions: {
						idpers: idper
					}
				});
				
				var imgurl = "";
				
				function likeScanOver(){
					tab.close();
					spov();
				}
				
				worker.port.on("fb_ph_img", function(mess){	//should be executed before fb_ph_like
					console.error("fb_ph_img : " + mess);
					imgurl = mess;
				});
				
				worker.port.on("fb_ph_like", function(mess){ //get the like page
					console.error("fb_ph_like : " + mess);
					
					if(mess != "" && mess != null)
						curfb.like(mess, imgurl, likeScanOver);
					else
					likeScanOver();
				});
			}else{
				tab.close();
			}
		}
	});
}



/*	scan photos page and goes to the next page until the end
*/
FbScan.prototype.photos = function(id, murl, psov){
	
	tabs.open({
		url: murl,
		
		onReady: function(tab){
			if(tab.url.search(mfb) >= 0){
				
				var photoUrl = [];
				var restPhotos = 0;
				var nextPage = "";
				var ifunct = 0;
				
				var photosScanOver = function (){
					tab.close();
					psov();
				}
				
				var photoScanOver = function(){
					restPhotos++;
					console.error("PHOTO :" + restPhotos + "/" + photoUrl.length);
					
					if(restPhotos >= photoUrl.length){
						if(nextPage != "" && nextPage != null)
							curfb.photos(id, nextPage, photosScanOver);
						else
							photosScanOver();
					}else{
						curfb.photo(photoUrl[restPhotos], id, photoScanOver);
					}
				}
				
				var worker = tab.attach({
					contentScriptFile: ['./utils/send.js', './fb/curid.js', './fb/photos.js']
				});
				
				worker.port.on('fb_phs_imgs', function(mess){
					console.error("fb_phs_imgs : " + mess);
					console.error("----- : " + id);
					
					if(mess.length > 0){
						for(i = 0; i < mess.length; i++)
							photoUrl.push(mess[i]);
						
						curfb.photo(mess[0], id, photoScanOver);
					}else
						photoScanOver();
						
				});
				
				worker.port.on('fb_phs_next', function(mess){
					console.error("fb_phs_next : " + mess);
					nextPage = mess;
				});
			}else{
				tab.close();
				//sov();
			}
		}
	});
}

/*	Launch photos scan
*/
FbScan.prototype.startPhotos = function(sov){
	var id = this.curid;
	
	tabs.open({
		url: mfb + id + "/photos",
		onReady: function(tab){
			
			function photosScanOver(){
				tab.close();
				sov();
			}
			
			if(tab.url.search(mfb) >= 0){
				var worker = tab.attach({
					contentScriptFile: './fb/mainphoto.js'
				});
				
				worker.port.on("fb_all_url", function(mess){
					console.error("fb_all_url : " + mess);
					curfb.photos(id, mess, photosScanOver);
				});
			}
		}
	});
}


/* Get the about informations
*/
FbScan.prototype.about = function(sov){
	tabs.open({
		url: mfb + this.curid + "/about",	//open the curid about page
		
		onReady: function(tab){				//when a tab change website
			if(tab.url.search(mfb) >= 0){	//if it's facebook
				var worker = tab.attach({
					contentScriptFile: ["./utils/send.js", "./fb/curid.js", "./fb/about.js"]	//open the script files
				});
				
				worker.port.on("fb_ab_place", function(mess){
					console.error("fb_ab_place : " + mess);
					// TODO place scan
				});
				
				worker.port.on("fb_ab_work", function(mess){
					console.error("fb_ab_work : " + mess);
					// TODO
				});
				
			}else{
				tab.close();
				sov();
			}  
		}
	});
}

/*	Get a friend page informations
*/
FbScan.prototype.friendpage = function(url, sov){
	var end = false;

	tabs.open({
		url: url,
		onReady: function (tab){
			if(tab.url.search(mfb) >= 0){
				var worker = tab.attach({
					contentScriptFile: ["./utils/send.js", "./fb/curid.js", "./fb/friend.js"]
				});
			
				worker.port.on("fb_frd_data", function(mess){
					console.error("fb_frd_data : " + mess);
					curfb.addIdListStr(mess);
				});
			
				worker.port.on("fb_frd_next", function(mess){
					console.error("fb_frd_next : " + mess);
					if(mess != "")
						curfb.friendpage(mess, sov);
					else{
						tab.close();
						
						end = true;
						sov();
					}
				});
			}else{
				tab.close();
				
				if(end)
					sov();
			}
		}
	});
}

/*	Begin the friend scan
*/
FbScan.prototype.friend = function(sov){
	this.friendpage(mfb + this.curid + "/friends", sov);
}


/*	full scan of a person
*/
FbScan.prototype.persScan = function(){
	console.error("Entry scan : " + this.curdeep + "/" + this.deeps + " for next : " + this.getId());

	do{
		this.curid = this.peekId();
	}while(this.curid == "");
	
	if(this.curid == null){
		console.error("End of scan");
		return;
	}
	
	console.error("Scan of : " + this.curid);
	
	// warning scanOver not executed a the same time as persScan, only asynchrone callback
	var nfunct = 3;		//number of scan functions
	var ifunct = 0;		//actual scan function executed
	
	var scanOver = function(){	//callback when a scan is over
		ifunct++;
		console.error("funct : " + ifunct + "/" + nfunct);
		
		if (ifunct >= nfunct)		
			curfb.persScan();
	};
	
	this.about(scanOver);
	this.friend(scanOver);
	this.startPhotos(scanOver);
}



/*	start the scan
*/
FbScan.prototype.start = function(startid, mail, pass){
	//this.login(mail, pass);
	
	this.addId(startid);
	this.ideep = 2;			// profondeur de recherche de 1, 2 : beaucoup de personnes, 3 enormement, 4 probablement tous
	
	this.persScan();
}

function scan(){
	var scan = new FbScan(2);
	
	//TODO : mettre les identifiants à la main et en clair	(provisoire)
	//			dans l'ordre : id du moint de départ != de celui du compte, email du compte, mot de passe 
	scan.start("mylene.loury.3", "fscan@gmx.fr", "FbScanner");
}

exports.scan = scan;
