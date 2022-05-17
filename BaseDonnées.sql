DROP TABLE IF EXISTS Pause;
DROP TABLE IF EXISTS Horaire;
DROP TABLE IF EXISTS User;

CREATE TABLE User(
	Id INT PRIMARY KEY,
	Nom VARCHAR(30) NOT NULL,
	Prenom VARCHAR(30) NOT NULL,
	email VARCHAR(50),
	Genre VARCHAR(5),
	Code VARCHAR(6) NOT NULL,
	Santos TINYINT(4) DEFAULT 0
);

CREATE TABLE Horaire(
	IdUser INT NOT NULL,
    Datage Date NOT NULL,
	HDebut TIME NOT NULL,
	HFin TIME,
	Coupure TIME DEFAULT '00:00:00',
	Decouchage TINYINT(4) DEFAULT 0,
	PRIMARY KEY (IdUser, Datage),
    FOREIGN KEY (IdUser) REFERENCES User(Id)
);

CREATE TABLE Pause(
    Id INT PRIMARY KEY AUTO_INCREMENT,
    IdUser INT NOT NULL,
    HDebut TIME NOT NULL,
    HFin TIME,
    FOREIGN KEY (IdUser) REFERENCES User(Id),
);