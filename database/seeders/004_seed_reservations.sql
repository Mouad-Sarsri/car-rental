
-- Clients : Sara(5), Mohammed(6), Nadia(7), Hassan(8), Leila(9), Anas(10)

INSERT INTO reservations
  (client_id, car_id, agency_id,
   date_debut, date_fin, nb_jours,
   prix_jour_snap, caution_snap, prix_total,
   statut, notes_client)
VALUES

-- ── Réservations terminées ────────────────────────────────
(5,  1, 1, '2025-01-05', '2025-01-08',  3, 250.00, 2000.00,  750.00, 'terminee',   'Arrivée tardive prévue'),
(6,  7, 2, '2025-01-10', '2025-01-15',  5, 380.00, 3000.00, 1900.00, 'terminee',   NULL),
(7, 11, 3, '2025-02-01', '2025-02-05',  4, 400.00, 3000.00, 1600.00, 'terminee',   'Besoin siège enfant'),
(8,  2, 1, '2025-02-14', '2025-02-16',  2, 300.00, 2500.00,  600.00, 'terminee',   NULL),
(9, 16, 4, '2025-03-01', '2025-03-07',  6, 230.00, 1800.00, 1380.00, 'terminee',   NULL),
(10, 6, 2, '2025-03-10', '2025-03-12',  2, 220.00, 1500.00,  440.00, 'terminee',   NULL),

-- ── Réservations confirmées (en cours ou à venir) ─────────
(5,  4, 1, '2025-04-20', '2025-04-25',  5, 700.00, 6000.00, 3500.00, 'confirmee',  'GPS requis si possible'),
(6, 14, 3, '2025-04-22', '2025-04-28',  6, 500.00, 4000.00, 3000.00, 'confirmee',  NULL),
(7,  8, 2, '2025-05-01', '2025-05-04',  3, 580.00, 4500.00, 1740.00, 'confirmee',  'Livraison à l\'aéroport'),
(8, 18, 4, '2025-05-10', '2025-05-15',  5, 500.00, 4000.00, 2500.00, 'confirmee',  NULL),

-- ── Réservations en attente ───────────────────────────────
(9,  3, 1, '2025-05-20', '2025-05-23',  3, 600.00, 5000.00, 1800.00, 'en_attente', 'Premier séjour à Casablanca'),
(10,10, 2, '2025-05-25', '2025-05-30',  5, 620.00, 5000.00, 3100.00, 'en_attente', NULL),
(5, 13, 3, '2025-06-01', '2025-06-10',  9,1500.00,10000.00,13500.00, 'en_attente', 'Excursion désert, 4x4 nécessaire'),

-- ── Réservations annulées ─────────────────────────────────
(6, 17, 4, '2025-02-20', '2025-02-22',  2, 280.00, 2000.00,  560.00, 'annulee',    NULL),
(7,  5, 1, '2025-03-15', '2025-03-18',  3,1200.00, 8000.00, 3600.00, 'annulee',    NULL),

-- ── Réservation refusée ────────────────────────────────────
(8, 15, 3, '2025-04-10', '2025-04-12',  2,1800.00,12000.00, 3600.00, 'refusee',    NULL);
