-- Ajouter les colonnes manquantes à la table Postuler
ALTER TABLE Postuler ADD COLUMN Lettre_motivation LONGTEXT NOT NULL DEFAULT "";
ALTER TABLE Postuler ADD COLUMN Date_candidature DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE Postuler ADD COLUMN Statut VARCHAR(50) DEFAULT "En attente";

-- Ajouter des contraintes de clé étrangère si elles n'existent pas
ALTER TABLE Postuler 
ADD CONSTRAINT fk_postuler_utilisateur FOREIGN KEY (Id_utilisateur) REFERENCES Utilisateurs(Id_utilisateur) ON DELETE CASCADE,
ADD CONSTRAINT fk_postuler_offre FOREIGN KEY (Id_offre) REFERENCES Offres(Id_offre) ON DELETE CASCADE;

-- Ajouter une clé primaire composite
ALTER TABLE Postuler ADD PRIMARY KEY (Id_utilisateur, Id_offre);
