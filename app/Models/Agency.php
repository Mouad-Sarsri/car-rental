<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Agency extends Model
{
    protected string $table = 'agencies';
    protected array  $fillable = [
        'manager_id', 'nom', 'adresse', 'ville',
        'code_postal', 'pays', 'telephone', 'email',
        'description', 'logo', 'actif',
    ];

    // ── Requêtes métier ───────────────────────────────────

    /** Toutes les agences avec le nom du manager */
    public function allWithManager(): array
    {
        return $this->query(
            "SELECT a.*,
                    u.nom       AS manager_nom,
                    u.prenom    AS manager_prenom,
                    u.email     AS manager_email,
                    u.telephone AS manager_telephone
             FROM agencies a
             LEFT JOIN users u ON u.id = a.manager_id
             ORDER BY a.nom"
        );
    }

    /** Une agence avec son manager */
    public function findWithManager(int $id): array|false
    {
        return $this->queryOne(
            "SELECT a.*,
                    u.nom       AS manager_nom,
                    u.prenom    AS manager_prenom,
                    u.email     AS manager_email
             FROM agencies a
             LEFT JOIN users u ON u.id = a.manager_id
             WHERE a.id = ?",
            [$id]
        );
    }

    /** Agence d'un manager donné */
    public function findByManager(int $managerId): array|false
    {
        return $this->findBy('manager_id', $managerId);
    }

    /** Agences actives uniquement */
    public function allActive(): array
    {
        return $this->query(
            "SELECT * FROM agencies WHERE actif = 1 ORDER BY ville, nom"
        );
    }

    /** Liste des villes distinctes */
    public function villes(): array
    {
        return $this->query(
            "SELECT DISTINCT ville FROM agencies WHERE actif = 1 ORDER BY ville"
        );
    }

    /** Nombre de voitures par agence */
    public function withCarCount(): array
    {
        return $this->query(
            "SELECT a.*,
                    u.nom       AS manager_nom,
                    u.prenom    AS manager_prenom,
                    COUNT(c.id)                                          AS total_cars,
                    SUM(c.statut = 'disponible')                         AS cars_disponibles,
                    SUM(c.statut = 'louee')                              AS cars_louees,
                    SUM(c.statut = 'maintenance')                        AS cars_maintenance
             FROM agencies a
             LEFT JOIN cars c ON c.agency_id = a.id
             LEFT JOIN users u ON u.id = a.manager_id
             GROUP BY a.id
             ORDER BY a.nom"
        );
    }

    /** Stats d'une agence : CA, réservations, etc. */
    public function stats(int $agencyId): array
    {
        $row = $this->queryOne(
            "SELECT
                COUNT(DISTINCT c.id)                            AS total_voitures,
                COUNT(DISTINCT r.id)                            AS total_reservations,
                SUM(r.statut = 'en_attente')                    AS resa_en_attente,
                SUM(r.statut = 'confirmee')                     AS resa_confirmees,
                COALESCE(SUM(
                    CASE WHEN r.statut IN ('confirmee','terminee')
                    THEN r.prix_total ELSE 0 END), 0)           AS chiffre_affaires
             FROM agencies a
             LEFT JOIN cars c ON c.agency_id = a.id
             LEFT JOIN reservations r ON r.agency_id = a.id
             WHERE a.id = ?",
            [$agencyId]
        );
        return $row ?: [];
    }

    public function toggleActif(int $id): void
    {
        $this->db->prepare(
            "UPDATE agencies SET actif = NOT actif WHERE id = ?"
        )->execute([$id]);
    }
}
