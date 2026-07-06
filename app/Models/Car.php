<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Car extends Model
{
    protected string $table = 'cars';
    protected array  $fillable = [
        'agency_id', 'marque', 'modele', 'immatriculation',
        'annee', 'couleur', 'nb_places', 'carburant', 'transmission',
        'climatisation', 'prix_jour', 'caution', 'statut',
        'description', 'photo',
    ];

    // ── Requêtes avec jointures ───────────────────────────

    /** Toutes les voitures avec le nom de l'agence */
    public function allWithAgency(): array
    {
        return $this->query(
            "SELECT c.*, a.nom AS agency_nom, a.ville AS agency_ville
             FROM cars c
             JOIN agencies a ON a.id = c.agency_id
             ORDER BY a.nom, c.marque, c.modele"
        );
    }

    /** Voitures d'une agence */
    public function byAgency(int $agencyId): array
    {
        return $this->query(
            "SELECT c.*, a.nom AS agency_nom, a.ville AS agency_ville
             FROM cars c
             JOIN agencies a ON a.id = c.agency_id
             WHERE c.agency_id = ?
             ORDER BY c.marque, c.modele",
            [$agencyId]
        );
    }

    /** Une voiture avec son agence */
    public function findWithAgency(int $id): array|false
    {
        return $this->queryOne(
            "SELECT c.*, a.nom AS agency_nom, a.ville AS agency_ville,
                    a.telephone AS agency_tel, a.email AS agency_email
             FROM cars c
             JOIN agencies a ON a.id = c.agency_id
             WHERE c.id = ?",
            [$id]
        );
    }

    // ── Disponibilité ─────────────────────────────────────

    /**
     * Voitures disponibles pour une période donnée.
     * Exclut les voitures avec une réservation confirmée/en_attente
     * qui chevauche la période demandée.
     */
    public function disponibles(string $dateDebut, string $dateFin, ?int $agencyId = null): array
    {
        $sql = "SELECT c.*, a.nom AS agency_nom, a.ville AS agency_ville
                FROM cars c
                JOIN agencies a ON a.id = c.agency_id
                WHERE c.statut = 'disponible'
                  AND a.actif = 1
                  AND c.id NOT IN (
                      SELECT DISTINCT r.car_id
                      FROM reservations r
                      WHERE r.statut IN ('en_attente', 'confirmee')
                        AND r.date_debut < ?
                        AND r.date_fin   > ?
                  )";

        $params = [$dateFin, $dateDebut];

        if ($agencyId !== null) {
            $sql     .= " AND c.agency_id = ?";
            $params[] = $agencyId;
        }

        $sql .= " ORDER BY c.prix_jour ASC";

        return $this->query($sql, $params);
    }

    /**
     * Vérifier si une voiture est disponible pour une période.
     * Permet d'exclure une réservation existante (pour modification).
     */
    public function isDisponible(int $carId, string $dateDebut, string $dateFin, int $excludeResaId = 0): bool
    {
        $sql = "SELECT COUNT(*) FROM reservations
                WHERE car_id = ?
                  AND statut IN ('en_attente', 'confirmee')
                  AND date_debut < ?
                  AND date_fin   > ?";

        $params = [$carId, $dateFin, $dateDebut];

        if ($excludeResaId > 0) {
            $sql    .= " AND id != ?";
            $params[] = $excludeResaId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() === 0;
    }

    // ── Recherche & filtres ───────────────────────────────

    /**
     * Recherche multicritères pour la liste publique.
     * Tous les paramètres sont optionnels.
     */
    public function search(array $filters = []): array
    {
        $sql    = "SELECT c.*, a.nom AS agency_nom, a.ville AS agency_ville
                   FROM cars c
                   JOIN agencies a ON a.id = c.agency_id
                   WHERE c.statut = 'disponible' AND a.actif = 1";
        $params = [];

        if (!empty($filters['ville'])) {
            $sql     .= " AND a.ville = ?";
            $params[] = $filters['ville'];
        }
        if (!empty($filters['agency_id'])) {
            $sql     .= " AND c.agency_id = ?";
            $params[] = (int) $filters['agency_id'];
        }
        if (!empty($filters['marque'])) {
            $sql     .= " AND c.marque = ?";
            $params[] = $filters['marque'];
        }
        if (!empty($filters['carburant'])) {
            $sql     .= " AND c.carburant = ?";
            $params[] = $filters['carburant'];
        }
        if (!empty($filters['transmission'])) {
            $sql     .= " AND c.transmission = ?";
            $params[] = $filters['transmission'];
        }
        if (!empty($filters['prix_max'])) {
            $sql     .= " AND c.prix_jour <= ?";
            $params[] = (float) $filters['prix_max'];
        }
        if (!empty($filters['places_min'])) {
            $sql     .= " AND c.nb_places >= ?";
            $params[] = (int) $filters['places_min'];
        }
        if (isset($filters['climatisation']) && $filters['climatisation'] !== '') {
            $sql     .= " AND c.climatisation = ?";
            $params[] = (int) $filters['climatisation'];
        }

        // Si une période est fournie, appliquer la disponibilité
        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $sql    .= " AND c.id NOT IN (
                            SELECT DISTINCT r.car_id
                            FROM reservations r
                            WHERE r.statut IN ('en_attente', 'confirmee')
                              AND r.date_debut < ?
                              AND r.date_fin   > ?
                        )";
            $params[] = $filters['date_fin'];
            $params[] = $filters['date_debut'];
        }

        $sort = match ($filters['sort'] ?? '') {
            'prix_asc'  => 'c.prix_jour ASC',
            'prix_desc' => 'c.prix_jour DESC',
            'annee'     => 'c.annee DESC',
            default     => 'a.ville ASC, c.prix_jour ASC',
        };
        $sql .= " ORDER BY {$sort}";

        return $this->query($sql, $params);
    }

    /** Liste des marques distinctes */
    public function marques(): array
    {
        return $this->query(
            "SELECT DISTINCT marque FROM cars WHERE statut != 'inactive' ORDER BY marque"
        );
    }

    // ── Stats ─────────────────────────────────────────────

    public function countByStatut(): array
    {
        return $this->query(
            "SELECT statut, COUNT(*) AS total FROM cars GROUP BY statut"
        );
    }

    public function updateStatut(int $id, string $statut): void
    {
        $this->db->prepare(
            "UPDATE cars SET statut = ? WHERE id = ?"
        )->execute([$statut, $id]);
    }
}
