DROP TABLE IF EXISTS Pause;
DROP TABLE IF EXISTS Horaire;
DROP TABLE IF EXISTS User;

CREATE TABLE User(
	Id INT PRIMARY KEY AUTO_INCREMENT,
	Nom VARCHAR(30) NOT NULL,
	Prenom VARCHAR(30) NOT NULL,
	email VARCHAR(50),
	Genre VARCHAR(5),
	Code VARCHAR(6) NOT NULL,
	Santos TINYINT(4) DEFAULT 0
);

CREATE TABLE Horaire(
    Id INT PRIMARY KEY AUTO_INCREMENT,
	IdUser INT NOT NULL,
    Datage Date NOT NULL,
	HDebut TIME NOT NULL,
	HFin TIME,
	Coupure TIME DEFAULT '00:00:00',
	Decouchage TINYINT(4) DEFAULT 0,
    FOREIGN KEY (IdUser) REFERENCES User(Id)
);

CREATE TABLE Pause(
    Id INT PRIMARY KEY AUTO_INCREMENT,
    IdHoraire INT NOT NULL,
    HDebut TIME NOT NULL,
    HFin TIME,
    FOREIGN KEY(IdHoraire) REFERENCES Horaire(Id)
);

INSERT INTO User VALUES(1, "Bronsin", "Stéphane", "stefetlo@hotmail.com", "Mr", "092715", 1);
INSERT INTO User VALUES(2, "Bronsin", "Baptiste", "baptiste.bronsin@outlook.com", "Mr", "010209", 1);
INSERT INTO User VALUES(3, "Nocenti", "Gary", "garynadege@live.fr", "Mr", "111000", 1);

INSERT INTO Horaire VALUES(1, 1, '2022-03-01', '05:00:00', '17:30:00', '01:15:00', 1);
INSERT INTO Horaire VALUES(2, 1, '2022-03-02', '05:00:00', '15:30:00', '01:15:00', 0);
INSERT INTO Horaire VALUES(3, 1, '2022-03-03', '05:25:00', '20:12:00', '01:30:00', 1);
INSERT INTO Horaire VALUES(4, 1, '2022-03-04', '05:10:00', '15:51:00', '01:15:00', 0);