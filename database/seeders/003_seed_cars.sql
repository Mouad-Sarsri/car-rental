-- agency_id : Casablanca(1), Rabat(2), Marrakech(3), Tanger(4)
INSERT INTO cars 
  (agency_id, marque, modele, immatriculation, annee, couleur, 
  nb_places, carburant, transmission, climatisation, prix_jour, 
  caution, statut, description, photo, created_at, updated_at) 
VALUES

-- ── Casablanca (agency_id = 1)
(1, 'Dacia', 'Sandero', 'A-12345-CA', '2022', 'Blanc', 5, 'essence', 'manuelle', 1, 250.00, 2000.00, 'disponible', NULL, 'dacia_sandero_blanc.jpeg', '2026-06-13 18:07:24', '2026-06-14 15:18:55'),
(1, 'Renault', 'Clio', 'B-23456-CA', '2021', 'Gris', 5, 'essence', 'manuelle', 1, 300.00, 2500.00, 'disponible', NULL, 'renault_clio_gris.jpeg', '2026-06-13 18:07:24', '2026-06-14 15:23:14'),
(1, 'Hyundai', 'Tucson', 'C-34567-CA', '2023', 'Noir', 5, 'diesel', 'automatique', 1, 600.00, 5000.00, 'disponible', NULL, 'hyundai_tucson_noir.jpg', '2026-06-13 18:07:24', '2026-06-14 15:26:46'),
(1, 'Toyota', 'RAV4', 'D-45678-CA', '2022', 'Blanc', 5, 'hybride', 'automatique', 1, 700.00, 6000.00, 'louee', NULL, 'toyota_rav4_blanc.jpeg', '2026-06-13 18:07:24', '2026-06-14 15:28:51'),
(1, 'Mercedes', 'Classe C', 'E-56789-CA', '2023', 'Noir', 5, 'diesel', 'automatique', 1, 1200.00, 8000.00, 'disponible', NULL, 'mercedes_classe_c_noir.jpg', '2026-06-13 18:07:24', '2026-06-14 15:31:04'),

-- Rabat (agency_id = 2) 
(2, 'Dacia', 'Logan', 'A-11111-RA', '2021', 'Blanc', 5, 'essence', 'manuelle', 1, 220.00, 1500.00, 'disponible', NULL, 'dacia_logan_blanc.jpg', '2026-06-13 18:07:24', '2026-06-14 15:33:29'),
(2, 'Volkswagen', 'Golf', 'B-22222-RA', '2022', 'Bleu', 5, 'essence', 'manuelle', 1, 380.00, 3000.00, 'disponible', NULL, 'volkswagen_golf_bleu.jpeg', '2026-06-13 18:07:24', '2026-06-14 15:34:36'),
(2, 'Ford', 'Kuga', 'C-33333-RA', '2023', 'Gris', 5, 'diesel', 'automatique', 1, 580.00, 4500.00, 'disponible', NULL, 'ford_kuga_gris.jpeg', '2026-06-13 18:07:24', '2026-06-14 15:36:18'),
(2, 'Kia', 'Sportage', 'D-44444-RA', '2022', 'Rouge', 5, 'diesel', 'automatique', 1, 550.00, 4000.00, 'maintenance', NULL, 'kia_sportage_rouge.jpg', '2026-06-13 18:07:24', '2026-06-14 15:38:44'),
(2, 'Peugeot', '3008', 'E-55555-RA', '2023', 'Blanc', 5, 'diesel', 'automatique', 1, 620.00, 5000.00, 'disponible', NULL, 'peugeot_3008_blanc.jpeg', '2026-06-13 18:07:24', '2026-06-14 15:40:32'),

-- Marrakech (agency_id = 3)
(3, 'Dacia', 'Duster', 'A-66666-MA', '2022', 'Sable', 5, 'diesel', 'manuelle', 1, 400.00, 3000.00, 'disponible', NULL, 'dacia_duster_sable.jpg', '2026-06-13 18:07:24', '2026-06-14 15:58:14'),
(3, 'Renault', 'Captur', 'B-77777-MA', '2021', 'Orange', 5, 'essence', 'manuelle', 1, 350.00, 2500.00, 'disponible', NULL, 'renault_captur_orange.jpg', '2026-06-13 18:07:24', '2026-06-14 15:59:50'),
(3, 'Toyota', 'Land Cruiser', 'C-88888-MA', '2022', 'Noir', 7, 'diesel', 'automatique', 1, 1500.00, 10000.00, 'disponible', NULL, 'toyota_land_cruiser_noir.jpg', '2026-06-13 18:07:24', '2026-06-14 15:12:22'),
(3, 'Suzuki', 'Jimny', 'D-99999-MA', '2023', 'Vert', 4, 'essence', 'manuelle', 1, 500.00, 4000.00, 'louee', NULL, 'suzuki_jimny_vert.jpg', '2026-06-13 18:07:24', '2026-06-14 16:07:16'),
(3, 'BMW', 'X5', 'E-10101-MA', '2023', 'Gris', 5, 'diesel', 'automatique', 1, 1800.00, 12000.00, 'disponible', NULL, 'bmw_x5_gris.jpeg', '2026-06-13 18:07:24', '2026-06-14 16:08:49'),

-- Tanger (agency_id = 4)
(4, 'Dacia', 'Sandero', 'A-11100-TA', '2021', 'Blanc', 5, 'essence', 'manuelle', 1, 230.00, 1800.00, 'disponible', NULL, 'dacia_sandero_blanc.jpeg', '2026-06-13 18:07:24', '2026-06-14 16:10:12'),
(4, 'Seat', 'Ibiza', 'B-22200-TA', '2022', 'Rouge', 5, 'essence', 'manuelle', 1, 280.00, 2000.00, 'disponible', NULL, 'seat_ibiza_rouge.jpg', '2026-06-13 18:07:24', '2026-06-14 16:11:39'),
(4, 'Nissan', 'Qashqai', 'C-33300-TA', '2022', 'Noir', 5, 'diesel', 'automatique', 1, 500.00, 4000.00, 'disponible', NULL, 'nissan_qashqai_noir.jpeg', '2026-06-13 18:07:24', '2026-06-14 16:13:49'),
(4, 'Toyota', 'Yaris', 'D-44400-TA', '2023', 'Bleu', 5, 'hybride', 'automatique', 1, 320.00, 2500.00, 'disponible', NULL, 'toyota_yaris_bleu.jpg', '2026-06-13 18:07:24', '2026-06-14 16:14:44'),
(4, 'Audi', 'A4', 'E-55500-TA', '2023', 'Gris', 5, 'diesel', 'automatique', 1, 900.00, 7000.00, 'maintenance', NULL, 'audi_a4_gris.jpg', '2026-06-13 18:07:24', '2026-06-14 16:15:48');