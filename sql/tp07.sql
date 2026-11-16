CREATE DATABASE IF NOT EXISTS tp07 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE tp07;

DROP TABLE IF EXISTS ville;
DROP TABLE IF EXISTS pays;

CREATE TABLE pays (
  id_pays INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- latitude / longitude : requises pour la partie 3 (Open-Meteo)
CREATE TABLE ville (
  id_ville INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  id_pays INT NOT NULL,
  latitude DECIMAL(9,6) NOT NULL,
  longitude DECIMAL(9,6) NOT NULL,
  FOREIGN KEY (id_pays) REFERENCES pays(id_pays)
) ENGINE=InnoDB;

INSERT INTO pays (id_pays, nom) VALUES
(1,'Tunisie'),(2,'France'),(3,'Maroc'),(4,'Algérie');

INSERT INTO ville (nom, id_pays, latitude, longitude) VALUES
('Djerba',1,33.875000,10.857500),
('Douz',1,33.466700,9.016700),
('Gabès',1,33.881500,10.098200),
('Kef',1,36.174200,8.704900),
('Sfax',1,34.740600,10.760300),
('Tunis',1,36.806500,10.181500),
('Zarzis',1,33.503900,11.112200),
('Paris',2,48.856600,2.352200),
('Lyon',2,45.764000,4.835700),
('Marseille',2,43.296500,5.369800),
('Casablanca',3,33.573100,-7.589800),
('Rabat',3,34.020900,-6.841600),
('Alger',4,36.753800,3.058800),
('Oran',4,35.696900,-0.633100);
