CREATE TABLE Types_compte(
   Id_type_compte BIGINT AUTO_INCREMENT,
   Nom VARCHAR(30),
   PRIMARY KEY(Id_type_compte)
);

CREATE TABLE Villes(
   Id_ville BIGINT AUTO_INCREMENT,
   Nom VARCHAR(50),
   CP VARCHAR(50),
   PRIMARY KEY(Id_ville)
);

CREATE TABLE Competences(
   Id_competence BIGINT AUTO_INCREMENT,
   Nom VARCHAR(50),
   PRIMARY KEY(Id_competence)
);

CREATE TABLE Domaines(
   Id_domaine BIGINT AUTO_INCREMENT,
   Nom VARCHAR(50),
   PRIMARY KEY(Id_domaine)
);

CREATE TABLE Utilisateurs(
   Id_utilisateur BIGINT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Prenom VARCHAR(50),
   Genre VARCHAR(20),
   Email VARCHAR(100),
   Mdp VARCHAR(255),
   Telephone VARCHAR(20),
   Ecole VARCHAR(50),
   Cv BOOLEAN,
   Lettre_motivation BOOLEAN,
   Id_ville BIGINT NOT NULL,
   Id_type_compte BIGINT NOT NULL,
   PRIMARY KEY(Id_utilisateur),
   FOREIGN KEY(Id_ville) REFERENCES Villes(Id_ville),
   FOREIGN KEY(Id_type_compte) REFERENCES Types_compte(Id_type_compte)
);

CREATE TABLE Entreprises(
   Id_entreprise BIGINT AUTO_INCREMENT,
   Nom VARCHAR(50),
   Email VARCHAR(50),
   Telephone VARCHAR(20),
   Descriptif VARCHAR(50),
   Id_ville BIGINT NOT NULL,
   Id_utilisateur BIGINT NOT NULL,
   PRIMARY KEY(Id_entreprise),
   UNIQUE(Id_utilisateur),
   FOREIGN KEY(Id_ville) REFERENCES Villes(Id_ville),
   FOREIGN KEY(Id_utilisateur) REFERENCES Utilisateurs(Id_utilisateur)
);

CREATE TABLE Offres(
   Id_offre BIGINT AUTO_INCREMENT,
   Descriptif TEXT,
   Date_debut DATE,
   Date_fin DATE,
   Duree INT,
   Renumeration DECIMAL(10,2),
   Id_entreprise BIGINT NOT NULL,
   PRIMARY KEY(Id_offre),
   FOREIGN KEY(Id_entreprise) REFERENCES Entreprises(Id_entreprise)
);

CREATE TABLE Vouloir(
   Id_utilisateur BIGINT,
   Id_offre BIGINT,
   PRIMARY KEY(Id_utilisateur, Id_offre),
   FOREIGN KEY(Id_utilisateur) REFERENCES Utilisateurs(Id_utilisateur),
   FOREIGN KEY(Id_offre) REFERENCES Offres(Id_offre)
);

CREATE TABLE Postuler(
   Id_utilisateur BIGINT,
   Id_offre BIGINT,
   PRIMARY KEY(Id_utilisateur, Id_offre),
   FOREIGN KEY(Id_utilisateur) REFERENCES Utilisateurs(Id_utilisateur),
   FOREIGN KEY(Id_offre) REFERENCES Offres(Id_offre)
);

CREATE TABLE Exiger(
   Id_offre BIGINT,
   Id_competence BIGINT,
   PRIMARY KEY(Id_offre, Id_competence),
   FOREIGN KEY(Id_offre) REFERENCES Offres(Id_offre),
   FOREIGN KEY(Id_competence) REFERENCES Competences(Id_competence)
);

CREATE TABLE Exercer_dans(
   Id_entreprise BIGINT,
   Id_domaine BIGINT,
   PRIMARY KEY(Id_entreprise, Id_domaine),
   FOREIGN KEY(Id_entreprise) REFERENCES Entreprises(Id_entreprise),
   FOREIGN KEY(Id_domaine) REFERENCES Domaines(Id_domaine)
);

CREATE TABLE Noter_1(
   Id_utilisateur BIGINT,
   Id_offre BIGINT,
   Note INT NOT NULL,
   Commentaire TEXT NOT NULL,
   PRIMARY KEY(Id_utilisateur, Id_offre),
   FOREIGN KEY(Id_utilisateur) REFERENCES Utilisateurs(Id_utilisateur),
   FOREIGN KEY(Id_offre) REFERENCES Offres(Id_offre)
);

CREATE TABLE Noter_2(
   Id_utilisateur BIGINT,
   Id_entreprise BIGINT,
   Note INT NOT NULL,
   Commentaire TEXT NOT NULL,
   PRIMARY KEY(Id_utilisateur, Id_entreprise),
   FOREIGN KEY(Id_utilisateur) REFERENCES Utilisateurs(Id_utilisateur),
   FOREIGN KEY(Id_entreprise) REFERENCES Entreprises(Id_entreprise)
);
