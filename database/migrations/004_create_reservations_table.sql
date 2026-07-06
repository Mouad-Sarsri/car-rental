CREATE TABLE IF NOT EXISTS reservations (
    id INT NOT NULL AUTO_INCREMENT,
    client_id INT NOT NULL,
    car_id INT NOT NULL,
    agency_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours SMALLINT NOT NULL,
    prix_jour_snap  DECIMAL(10,2) NOT NULL COMMENT 'Prix/jour au moment de la résa',
    caution_snap DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'annulee', 'terminee', 'refusee')
        NOT NULL DEFAULT 'en_attente',
    motif_annulation VARCHAR(255) NULL,
    notes_client TEXT NULL,
    notes_agence TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
       ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_resa_client (client_id),
    INDEX idx_resa_car (car_id),
    INDEX idx_resa_agency (agency_id),
    INDEX idx_resa_statut (statut),
    INDEX idx_resa_dates (date_debut, date_fin),
    CONSTRAINT fk_resa_client
        FOREIGN KEY (client_id)
        REFERENCES users (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_resa_car
        FOREIGN KEY (car_id)
        REFERENCES cars (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_resa_agency
        FOREIGN KEY (agency_id)
        REFERENCES agencies (id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT chk_dates CHECK (date_fin > date_debut)
) ENGINE=InnoDB;
