CREATE DATABASE IF NOT EXISTS autoecole
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE autoecole;

CREATE TABLE eleves (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(100) NOT NULL,
                        prenom VARCHAR(100) NOT NULL,
                        email VARCHAR(150),
                        telephone VARCHAR(30),
                        adresse VARCHAR(255),
                        type_permis VARCHAR(10), -- B, A, AAC...
                        date_inscription DATE DEFAULT CURRENT_DATE
);

CREATE TABLE moniteurs (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           nom VARCHAR(100) NOT NULL,
                           prenom VARCHAR(100) NOT NULL,
                           email VARCHAR(150),
                           telephone VARCHAR(30),
                           specialite VARCHAR(50), -- Permis B, A…
                           date_embauche DATE
);

CREATE TABLE vehicules (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           immatriculation VARCHAR(20) NOT NULL,
                           modele VARCHAR(100) NOT NULL,
                           annee INT,
                           categorie VARCHAR(50) -- citadine, moto, utilitaire...
);