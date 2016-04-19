function getPerson(infosUrl){
    var persInfo = null;
	if(infosUrl){
		
		persInfo = getRequestSync(infosUrl);
		
		if(!persInfo.contains("NOTHING")){			
			if(window.DOMParser){
				var parser = new DOMParser();
				return parser.parseFromString(persInfo, "text/xml");;
			}else{
				var doc = new ActiveXObject("Microsoft.XMLDOM");
				doc.async = false;
				return doc.loadXML(persInfo);
			}
		}
		
		return null;
	}
}

function Ref(xml){
	this.xml = xml;
	
	if(this.xml){
		this.id = xml.getAttribute("id");
		this.total = xml.getAttribute("total");
		
		this.friend = xml.childNodes[0].getAttribute("friend") == "1";
		this.likeon = parseInt(xml.childNodes[1].innerHTML);
		this.liked = parseInt(xml.childNodes[2].innerHTML);
	}
}

function Pers (id, light){
	this.light = light;
	
	var url = "getpers.php?id=" + id;
	if(this.light)
		url += "&light";
	
	this.xml = getPerson(url);
	
	if(this.xml != null){		
		this.id = id;	//!! ID facebook ou database
		this.idstr = this.xml.documentElement.getAttribute("idstr");
		this.name = xpath1res("//name", this.xml).iterateNext().innerHTML;
		this.sexe = xpath1res("//sexe", this.xml).iterateNext().innerHTML;
		this.birth = xpath1res("//birth", this.xml).iterateNext().innerHTML;
		this.lang = xpath1res("//lang", this.xml).iterateNext().innerHTML;
		
		if(!this.light){
			this.referenced = xpathares("//referenced/ref", this.xml);
			
			for(var i = 0; i < this.referenced.length; i++){
				this.referenced[i] = new Ref(this.referenced[i]);
			}
			
			this.places = xpathares("//places/place", this.xml);
			for(var i = 0; i < this.places.length; i++)
				this.places[i] = this.places[i].innerHTML;
			
			this.works = xpathares("//works/work", this.xml);
			for(var i = 0; i < this.works.length; i++)
				this.works[i] = this.works[i].innerHTML;
		}else
			this.referenced = null;
	}
}

Pers.prototype.getMaxRef = function(){
	if(this.xml){
		var res = xpath1res("//ref[not(@total <= preceding-sibling::ref/@total) and not(@total <=following-sibling::ref/@total)]", this.xml);
		res = res.iterateNext();
		
		if(res)
			return res.getAttribute("total");
	}	
	return null;
}

Pers.prototype.filterRefInf = function (ftotal, friend, liked, likeon){
	if(this.xml && !this.ligth){
		var refs = [];
		
		this.referenced.forEach(function (n){

			if(	n.total > ftotal &&
				(friend && !n.friend || !friend) && 
				(liked && n.liked > 0 || !liked) &&
				(likeon && n.likeon > 0 || !likeon)
			){
				refs.push(n);
			}
		});
		
		return refs;
	}
	
	return null;
}

Pers.prototype.buildSigma = function(ftotal, friend, liked, likeon){
	if(this.xml && !this.light){
		var graph = {
			nodes: [],
			edges: []
		};
		
		graph.nodes.push({
			id: "nc",
			label: this.idstr,
			x: 0.5,
			y: 0.5,
			size: 1,
			color: '#666'
		});
		
		var refs = this.filterRefInf(ftotal, friend, liked, likeon);
		
		for(var i = 0; i < refs.length; i++){
			var ref = refs[i];
			var rad = (2 * i * Math.PI) / refs.length;
			var len = (1 / ref.total);
			var id = "n" + ref.id;
			
			graph.nodes.push({
				id: id,
				label: ref.id,
				x: 0.5 + Math.cos(rad) * len,
				y: 0.5 + Math.sin(rad) * len,
				size: 1,
				color:  '#666'
			});
			
			graph.edges.push({
				id: "e" + i,
				source: "nc",
				target: id,
				size: 1,
				color: '#ccc',
				hover_color: '#000'
			});
		}
		
		return graph;
	}
	
	return null;
}

Pers.prototype.drawPersInfo = function(divid){
	var div = document.getElementById(divid);
	div.innerHTML = "";
	
	if(div && this.xml){
		var did = document.createElement("p");
		did.innerHTML = "ID facebook : <a href='persons.php?id=" + this.idstr + "'>" + this.idstr + "</a>";
		div.appendChild(did);
		
		var dimg = document.createElement("p");
		dimg.innerHTML = "<a href='RechercheImages.php?id=" + this.idstr + "'>Images</a>";
		div.appendChild(dimg);
		
		if(this.birth){
			var dname = document.createElement("p");
			dname.innerHTML = "Nom : " + this.name;
			div.appendChild(dname);
			
			var dbirth = document.createElement("p");
			dbirth.innerHTML = "Date de naissance : " + this.birth;
			div.appendChild(dbirth);
			
			var dsexe = document.createElement("p");
			dsexe.innerHTML = "Sexe : ";
			if(this.sexe == 0)
				dsexe.innerHTML += "Femme";
			else
				dsexe.innerHTML += "Homme";
			div.appendChild(dsexe);
			
			if (!this.light){
				if(this.places.length > 0){
					
					var places = document.createElement("p");
					
					var args = "";
					this.places.forEach(function (e){
						if(places.length > 0)
							args += ", ";
						args += e;
					});
					
					places.innerHTML = "A vécu à : " + args;
					
					div.appendChild(places);
				}
				
				if (this.works.length > 0){
					var works = document.createElement("p");
					
					var args = "";
					this.works.forEach(function (e){
						if(args.length > 0)
							args += ", ";
						args += e;
					});
					
					works.innerHTML = "A travailler à : " + args;
					
					div.appendChild(works);
				}
			}
		}else{
			var ainf = document.createElement("p");
			ainf.innerHTML = "Aucune information disponible";
			div.appendChild(ainf);
		}
		/*
		if(this != person){
			var alink = document.createElement("p");
			alink.innerHTML = "<a href=\"comparaisonPersonne.php?id1=" + person.idstr + "&id2=" + this.id + "\">Liens avec " + person.idstr + "</a>";
			
			div.appendChild(alink);
		}*/
	}
}
