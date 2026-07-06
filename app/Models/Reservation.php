<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;
use DateTime;

class Reservation extends Model
{
    protected string $table = 'reservations';
    protected array  $fillable = [
        'client_id', 'car_id', 'agency_id',
        'date_debut', 'date_fin', 'nb_jours',
        'prix_jour_snap', 'caution_snap', 'prix_total',
        'statut', 'motif_annulation', 'notes_client', 'notes_agence',
    ];

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_CONFIRMEE  = 'confirmee';
    const STATUT_ANNULEE    = 'annulee';
    const STATUT_TERMINEE   = 'terminee';
    const STATUT_REFUSEE    = 'refusee';

    // ── Création ──────────────────────────────────────────

    /**
     * Créer une réservation en calculant automatiquement
     * nb_jours, prix_jour_snap, caution_snap et prix_total.
     */
    public function createFromCar(array $data, array $car): int
    {
        $dateDebut = new DateTime($data['date_debut']);
        $dateFin   = new DateTime($data['date_fin']);
        $nbJours   = (int) $dateFin->diff($dateDebut)->days;

        return $this->create([
            'client_id'      => $data['client_id'],
            'car_id'         => $car['id'],
            'agency_id'      => $car['agency_id'],
            'date_debut'     => $data['date_debut'],
            'date_fin'       => $data['date_fin'],
            'nb_jours'       => $nbJours,
            'prix_jour_snap' => $car['prix_jour'],
            'caution_snap'   => $car['caution'],
            'prix_total'     => $nbJours * $car['prix_jour'],
            'statut'         => self::STATUT_EN_ATTENTE,
            'notes_client'   => $data['notes_client'] ?? null,
        ]);
    }

    // ── Requêtes avec jointures ───────────────────────────

    /** Toutes les réservations avec client, voiture et agence */
    public function allFull(): array
    {
        return $this->query($this->fullSelectSql() . " ORDER BY r.created_at DESC");
    }

    /** Réservations d'un client */
    public function byClient(int $clientId): array
    {
        return $this->query(
            $this->fullSelectSql() . " WHERE r.client_id = ? ORDER BY r.created_at DESC",
            [$clientId]
        );
    }

    /** Réservations d'une agence */
    public function byAgency(int $agencyId): array
    {
        return $this->query(
            $this->fullSelectSql() . " WHERE r.agency_id = ? ORDER BY r.created_at DESC",
            [$agencyId]
        );
    }

    /** Réservations d'une agence filtrées par statut */
    public function byAgencyAndStatut(int $agencyId, string $statut): array
    {
        return $this->query(
            $this->fullSelectSql() . " WHERE r.agency_id = ? AND r.statut = ? ORDER BY r.date_debut ASC",
            [$agencyId, $statut]
        );
    }

    /** Une réservation complète par ID */
    public function findFull(int $id): array|false
    {
        return $this->queryOne(
            $this->fullSelectSql() . " WHERE r.id = ?",
            [$id]
        );
    }

    /** Réservations actives d'une voiture (pour le calendrier) */
    public function activeByCar(int $carId): array
    {
        return $this->query(
            "SELECT date_debut, date_fin, statut
             FROM reservations
             WHERE car_id = ?
               AND statut IN ('en_attente', 'confirmee')
             ORDER BY date_debut",
            [$carId]
        );
    }

    // ── Actions de statut ─────────────────────────────────

    public function confirmer(int $id, ?string $note = null): bool
    {
        return $this->changerStatut($id, self::STATUT_CONFIRMEE, null, $note);
    }

    public function annuler(int $id, string $motif = ''): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE reservations
             SET statut = 'annulee', motif_annulation = ?
             WHERE id = ? AND statut IN ('en_attente', 'confirmee')"
        );
        $stmt->execute([$motif, $id]);
        return $stmt->rowCount() > 0;
    }

    public function refuser(int $id, string $motif = ''): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE reservations
             SET statut = 'refusee', motif_annulation = ?
             WHERE id = ? AND statut = 'en_attente'"
        );
        $stmt->execute([$motif, $id]);
        return $stmt->rowCount() > 0;
    }

    public function terminer(int $id): bool
    {
        return $this->changerStatut($id, self::STATUT_TERMINEE);
    }

    private function changerStatut(int $id, string $statut, ?string $motif = null, ?string $note = null): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE reservations
             SET statut = ?,
                 motif_annulation = COALESCE(?, motif_annulation),
                 notes_agence = COALESCE(?, notes_agence)
             WHERE id = ?"
        );
        $stmt->execute([$statut, $motif, $note, $id]);
        return $stmt->rowCount() > 0;
    }

    // ── Stats & dashboard ─────────────────────────────────

    public function stats(?int $agencyId = null): array
    {
        $where  = $agencyId ? "WHERE agency_id = {$agencyId}" : '';

        return $this->queryOne(
            "SELECT
                COUNT(*)                                        AS total,
                SUM(statut = 'en_attente')                      AS en_attente,
                SUM(statut = 'confirmee')                       AS confirmees,
                SUM(statut = 'annulee')                         AS annulees,
                SUM(statut = 'terminee')                        AS terminees,
                COALESCE(SUM(
                    CASE WHEN statut IN ('confirmee','terminee')
                    THEN prix_total ELSE 0 END), 0)             AS chiffre_affaires,
                COALESCE(AVG(
                    CASE WHEN statut IN ('confirmee','terminee')
                    THEN prix_total END), 0)                    AS panier_moyen
             FROM reservations {$where}"
        ) ?: [];
    }

    /** Réservations des 30 derniers jours, groupées par jour */
    public function parJour(?int $agencyId = null, int $jours = 30): array
    {
        $where  = $agencyId ? "AND agency_id = ?" : '';
        $params = [];
        if ($agencyId) $params[] = $agencyId;

        return $this->query(
            "SELECT DATE(created_at) AS jour, COUNT(*) AS total
             FROM reservations
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL {$jours} DAY)
               {$where}
             GROUP BY DATE(created_at)
             ORDER BY jour ASC",
            $params
        );
    }

    // ── Helpers ───────────────────────────────────────────

    /** Vérifier qu'une réservation appartient à un client */
    public function appartientAuClient(int $resaId, int $clientId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reservations WHERE id = ? AND client_id = ?"
        );
        $stmt->execute([$resaId, $clientId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Vérifier qu'une réservation appartient à une agence */
    public function appartientALAgence(int $resaId, int $agencyId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM reservations WHERE id = ? AND agency_id = ?"
        );
        $stmt->execute([$resaId, $agencyId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function fullSelectSql(): string
    {
        return "SELECT
                    r.*,
                    u.nom         AS client_nom,
                    u.prenom      AS client_prenom,
                    u.email       AS client_email,
                    u.telephone   AS client_telephone,
                    c.marque      AS car_marque,
                    c.modele      AS car_modele,
                    c.immatriculation,
                    c.photo       AS car_photo,
                    a.nom         AS agency_nom,
                    a.ville       AS agency_ville
                FROM reservations r
                JOIN users      u ON u.id = r.client_id
                JOIN cars       c ON c.id = r.car_id
                JOIN agencies   a ON a.id = r.agency_id";
    }
}
