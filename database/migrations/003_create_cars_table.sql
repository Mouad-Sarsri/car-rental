CREATE TABLE IF NOT EXISTS cars (
    id INT NOT NULL AUTO_INCREMENT,
    agency_id INT UNSIGNED NOT NULL,
    marque VARCHAR(100) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    immatriculation VARCHAR(20) NOT NULL,
    annee YEAR NOT NULL,
    couleur VARCHAR(50) NULL,
    nb_places TINYINT UNSIGNED NOT NULL DEFAULT 5,
    carburant ENUM('essence', 'diesel', 'hybride', 'electrique')
        NOT NULL DEFAULT 'diesel',
    transmission ENUM('manuelle', 'automatique')
        NOT NULL DEFAULT 'manuelle',
    climatisation TINYINT(1) NOT NULL DEFAULT 1,
    prix_jour DECIMAL(10,2) NOT NULL,
    caution DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    statut ENUM('disponible', 'louee', 'maintenance', 'inactive')
        NOT NULL DEFAULT 'disponible',
    description TEXT NULL,
    photo VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cars_immat (immatriculation),
    INDEX idx_cars_agency (agency_id),
    INDEX idx_cars_statut (statut),
    INDEX idx_cars_prix (prix_jour),
    CONSTRAINT fk_cars_agency
        FOREIGN KEY (agency_id)
        REFERENCES agencies (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;
