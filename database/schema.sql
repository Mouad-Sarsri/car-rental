SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ── users ─────────────────────────────────────────────────
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(100)    NOT NULL,
    prenom      VARCHAR(100)    NOT NULL,
    email       VARCHAR(191)    NOT NULL,
    password    VARCHAR(255)    NOT NULL,
    role        ENUM('client', 'agency_manager', 'super_manager')
                                NOT NULL DEFAULT 'client',
    telephone   VARCHAR(20)     NULL,
    avatar      VARCHAR(255)    NULL,
    actif       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    INDEX idx_users_role (role),
    INDEX idx_users_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── agencies ──────────────────────────────────────────────
DROP TABLE IF EXISTS agencies;
CREATE TABLE agencies (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    manager_id  INT UNSIGNED    NULL,
    nom         VARCHAR(150)    NOT NULL,
    adresse     VARCHAR(255)    NOT NULL,
    ville       VARCHAR(100)    NOT NULL,
    code_postal VARCHAR(10)     NULL,
    pays        VARCHAR(100)    NOT NULL DEFAULT 'Maroc',
    telephone   VARCHAR(20)     NULL,
    email       VARCHAR(191)    NULL,
    description TEXT            NULL,
    logo        VARCHAR(255)    NULL,
    actif       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_agencies_email (email),
    INDEX idx_agencies_ville (ville),
    INDEX idx_agencies_actif (actif),
    CONSTRAINT fk_agencies_manager
        FOREIGN KEY (manager_id) REFERENCES users (id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── cars ──────────────────────────────────────────────────
DROP TABLE IF EXISTS cars;
CREATE TABLE cars (
    id              INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    agency_id       INT UNSIGNED        NOT NULL,
    marque          VARCHAR(100)        NOT NULL,
    modele          VARCHAR(100)        NOT NULL,
    immatriculation VARCHAR(20)         NOT NULL,
    annee           YEAR                NOT NULL,
    couleur         VARCHAR(50)         NULL,
    nb_places       TINYINT UNSIGNED    NOT NULL DEFAULT 5,
    carburant       ENUM('essence','diesel','hybride','electrique')
                                        NOT NULL DEFAULT 'diesel',
    transmission    ENUM('manuelle','automatique')
                                        NOT NULL DEFAULT 'manuelle',
    climatisation   TINYINT(1)          NOT NULL DEFAULT 1,
    prix_jour       DECIMAL(10,2)       NOT NULL,
    caution         DECIMAL(10,2)       NOT NULL DEFAULT 0.00,
    statut          ENUM('disponible','louee','maintenance','inactive')
                                        NOT NULL DEFAULT 'disponible',
    description     TEXT                NULL,
    photo           VARCHAR(255)        NULL,
    created_at      TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP
                                        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cars_immat (immatriculation),
    INDEX idx_cars_agency (agency_id),
    INDEX idx_cars_statut (statut),
    INDEX idx_cars_prix (prix_jour),
    CONSTRAINT fk_cars_agency
        FOREIGN KEY (agency_id) REFERENCES agencies (id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── reservations ──────────────────────────────────────────
DROP TABLE IF EXISTS reservations;
CREATE TABLE reservations (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    client_id       INT UNSIGNED    NOT NULL,
    car_id          INT UNSIGNED    NOT NULL,
    agency_id       INT UNSIGNED    NOT NULL,
    date_debut      DATE            NOT NULL,
    date_fin        DATE            NOT NULL,
    nb_jours        SMALLINT UNSIGNED NOT NULL,
    prix_jour_snap  DECIMAL(10,2)   NOT NULL,
    caution_snap    DECIMAL(10,2)   NOT NULL DEFAULT 0.00,
    prix_total      DECIMAL(10,2)   NOT NULL,
    statut          ENUM('en_attente','confirmee','annulee','terminee','refusee')
                                    NOT NULL DEFAULT 'en_attente',
    motif_annulation VARCHAR(255)   NULL,
    notes_client    TEXT            NULL,
    notes_agence    TEXT            NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
                                    ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_resa_client (client_id),
    INDEX idx_resa_car (car_id),
    INDEX idx_resa_agency (agency_id),
    INDEX idx_resa_statut (statut),
    INDEX idx_resa_dates (date_debut, date_fin),
    CONSTRAINT fk_resa_client
        FOREIGN KEY (client_id) REFERENCES users (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_resa_car
        FOREIGN KEY (car_id) REFERENCES cars (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_resa_agency
        FOREIGN KEY (agency_id) REFERENCES agencies (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT chk_dates CHECK (date_fin > date_debut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── migrations ────────────────────────────────────────────
DROP TABLE IF EXISTS migrations;
CREATE TABLE migrations (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    filename    VARCHAR(255)    NOT NULL,
    executed_at TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_migration_file (filename)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
