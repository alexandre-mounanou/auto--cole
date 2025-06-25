CREATE DATABASE IF NOT EXISTS auto_ecole;
USE auto_ecole;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    type_utilisateur ENUM('candidat', 'moniteur', 'administrateur') NOT NULL
);

CREATE TABLE IF NOT EXISTS candidats (
    id INT PRIMARY KEY,
    date_permis DATE,
    date_code DATE,
    est_etudiant BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id) REFERENCES utilisateurs(id)
);

CREATE TABLE IF NOT EXISTS ecoles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS etudiants_ecoles (
    candidat_id INT,
    ecole_id INT,
    PRIMARY KEY (candidat_id, ecole_id),
    FOREIGN KEY (candidat_id) REFERENCES candidats(id),
    FOREIGN KEY (ecole_id) REFERENCES ecoles(id)
);

CREATE TABLE IF NOT EXISTS moniteurs (
    id INT PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES utilisateurs(id)
);

CREATE TABLE IF NOT EXISTS vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(255) NOT NULL,
    modele VARCHAR(255) NOT NULL,
    immatriculation VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS formations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE TABLE IF NOT EXISTS lecons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidat_id INT NOT NULL,
    moniteur_id INT NOT NULL,
    vehicule_id INT NOT NULL,
    date_heure_debut DATETIME NOT NULL,
    date_heure_fin DATETIME NOT NULL,
    type_lecon VARCHAR(255),
    FOREIGN KEY (candidat_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (moniteur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id),
    CONSTRAINT uc_lecon UNIQUE (candidat_id, date_heure_debut),
    CONSTRAINT uc_moniteur_lecon UNIQUE (moniteur_id, date_heure_debut),
    CONSTRAINT uc_vehicule_lecon UNIQUE (vehicule_id, date_heure_debut)
);

CREATE TABLE IF NOT EXISTS examens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidat_id INT NOT NULL,
    type_examen VARCHAR(255) NOT NULL,
    date_examen DATETIME NOT NULL,
    resultat BOOLEAN,
    FOREIGN KEY (candidat_id) REFERENCES utilisateurs(id)
);


