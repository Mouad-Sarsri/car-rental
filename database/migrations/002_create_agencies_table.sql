CREATE TABLE IF NOT EXISTS agencies (
    id INT NOT NULL AUTO_INCREMENT,
    manager_id INT NULL,
    nom VARCHAR(150) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    code_postal VARCHAR(10) NULL,
    pays VARCHAR(100) NOT NULL DEFAULT 'Maroc',
    telephone VARCHAR(20) NULL,
    email VARCHAR(191) NULL,
    description TEXT NULL,
    logo VARCHAR(255) NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_agencies_email (email),
    INDEX idx_agencies_ville (ville),
    INDEX idx_agencies_actif (actif),
    CONSTRAINT fk_agencies_manager
        FOREIGN KEY (manager_id)
        REFERENCES users (id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;
