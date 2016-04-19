-- Represent fb person
CREATE TABLE Pers (
		id 	INTEGER AUTO_INCREMENT PRIMARY KEY,	-- unique id
		idstr 	VARCHAR(64) UNIQUE,			-- fb identifier
		rname	VARCHAR(64),				-- person name
		sexe	BOOL,					-- sexe (true=M)
		birth	DATETIME,				-- birth date
		lang	TEXT					-- spoken languages (text)
		);

-- Friend relation between two persons
CREATE TABLE Friend (
		idpers	INTEGER,				-- first peson
		idfrd	INTEGER,				-- second person
		FOREIGN KEY (idpers) REFERENCES Pers(id),
		FOREIGN KEY (idfrd) REFERENCES Pers(id)
		);

-- image posted
CREATE TABLE Photo(
		id		INTEGER AUTO_INCREMENT PRIMARY KEY,-- unique id
		postby		INTEGER,			-- poster id
		datep		DATETIME,			-- date of the post
		img		VARCHAR(255),			-- image url
		FOREIGN KEY (postby) REFERENCES Pers(id)
		);

-- person who like a photo
CREATE TABLE Phlike(
		img		INTEGER,			-- image id
		pers		INTEGER,			-- person id
		PRIMARY KEY (img, pers),
		FOREIGN KEY (img) REFERENCES Photo(id),
		FOREIGN KEY (pers) REFERENCES Pers(id)
		);

-- place in the world
CREATE TABLE Place(
		id		INTEGER AUTO_INCREMENT PRIMARY KEY,	-- unique id
		pname		VARCHAR(64) UNIQUE,		-- place name
		typeent		VARCHAR(64)			-- type
		);
		
-- place where person live(ed)
CREATE TABLE Live(
		idpers	INTEGER,	-- person id
		idplace INTEGER,	-- place id
		PRIMARY KEY (idpers, idplace),
		FOREIGN KEY (idpers) REFERENCES Pers(id),
		FOREIGN KEY (idplace) REFERENCES Place(id)
		);
		
-- Work, assotiation, school
CREATE TABLE Workplace(
		id		INTEGER AUTO_INCREMENT PRIMARY KEY,	-- unique id
		wname		VARCHAR(64) UNIQUE,		-- workplace name
		typeent		VARCHAR(64)			-- type (school, office, ...)
		);
		
-- Place where people work
CREATE TABLE Workat(
		idpers		INTEGER,			-- person id
		idwork		INTEGER,			-- workplace id
		PRIMARY KEY (idpers, idwork),
		FOREIGN KEY (idpers) REFERENCES Pers(id),
		FOREIGN KEY (idwork) REFERENCES Workplace(id)
		);
