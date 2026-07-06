<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;
use DateTime;

class ReservationController extends Controller
{
    private Reservation $resaModel;
    private Car         $carModel;
    private Agency      $agencyModel;

    public function __construct()
    {
        $this->resaModel   = new Reservation();
        $this->carModel    = new Car();
        $this->agencyModel = new Agency();
    }

    // ── Client : ses réservations ─────────────────────────

    public function myReservations(): void
    {
        $this->requireRole('client');

        $reservations = $this->resaModel->byClient($this->authId());
        $this->render('reservations/my', [
            'reservations' => $reservations,
            'flash'        => $this->getFlash(),
        ]);
    }

    // ── Client : créer une réservation ────────────────────

    public function showCreate(): void
    {
        $this->requireRole('client');

        $carId = (int) $this->get('car_id', 0);
        if (!$carId) {
            $this->redirect('/cars');
        }

        $car = $this->carModel->findWithAgency($carId);
        if (!$car || $car['statut'] !== 'disponible') {
            $this->redirectWithError('/cars', 'Cette voiture n\'est pas disponible.');
            return;
        }

        $this->render('reservations/create', [
            'car'        => $car,
            'date_debut' => $this->get('date_debut', ''),
            'date_fin'   => $this->get('date_fin', ''),
        ]);
    }

    public function store(): void
    {
        $this->requireRole('client');

        $carId     = (int) $this->post('car_id', 0);
        $dateDebut = $this->post('date_debut', '');
        $dateFin   = $this->post('date_fin', '');
        $notes     = $this->post('notes_client', '');

        // Validation des dates
        $errors = $this->validateDates($dateDebut, $dateFin);

        $car = null;
        if (empty($errors)) {
            $car = $this->carModel->findWithAgency($carId);
            if (!$car) {
                $errors['car'][] = 'Voiture introuvable.';
            } elseif (!$this->carModel->isDisponible($carId, $dateDebut, $dateFin)) {
                $errors['dates'][] = 'Cette voiture n\'est pas disponible pour la période sélectionnée.';
            }
        }

        if (!empty($errors)) {
            $car = $car ?? $this->carModel->findWithAgency($carId);
            $this->render('reservations/create', [
                'car'        => $car,
                'errors'     => $errors,
                'date_debut' => $dateDebut,
                'date_fin'   => $dateFin,
            ]);
            return;
        }

        $resaId = $this->resaModel->createFromCar([
            'client_id'    => $this->authId(),
            'date_debut'   => $dateDebut,
            'date_fin'     => $dateFin,
            'notes_client' => $notes,
        ], $car);

        $this->redirectWithSuccess(
            "/reservations/{$resaId}",
            'Votre réservation a été soumise. L\'agence va la confirmer sous peu.'
        );
    }

    // ── Client : voir une réservation ─────────────────────

    public function show(int $id): void
    {
        $this->requireAuth();

        $resa = $this->resaModel->findFull($id);
        if (!$resa) {
            $this->render('errors/404'); return;
        }

        // Vérifier les droits d'accès
        $this->checkAccess($resa);

        $this->render('reservations/show', [
            'resa'  => $resa,
            'flash' => $this->getFlash(),
        ]);
    }

    // ── Client : annuler sa réservation ──────────────────

    public function cancel(int $id): void
    {
        $this->requireRole('client');

        $resa = $this->resaModel->findOrFail($id);

        if (!$this->resaModel->appartientAuClient($id, $this->authId())) {
            $this->render('errors/403'); return;
        }

        if (!in_array($resa['statut'], ['en_attente', 'confirmee'], true)) {
            $this->redirectWithError('/reservations', 'Cette réservation ne peut pas être annulée.');
            return;
        }

        $motif = $this->post('motif', 'Annulée par le client.');
        $this->resaModel->annuler($id, $motif);

        $this->redirectWithSuccess('/reservations', 'Réservation annulée.');
    }

    // ── Manager : réservations de son agence ──────────────

    public function agencyIndex(): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $agencyId = $this->resolveAgencyId();
        $statut   = $this->get('statut', '');

        $reservations = $statut
            ? $this->resaModel->byAgencyAndStatut($agencyId, $statut)
            : $this->resaModel->byAgency($agencyId);

        $stats = $this->resaModel->stats($agencyId);

        $this->render('manager/reservations/index', [
            'reservations' => $reservations,
            'stats'        => $stats,
            'statut'       => $statut,
            'flash'        => $this->getFlash(),
        ]);
    }

    // ── Manager : confirmer ───────────────────────────────

    public function confirm(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $resa = $this->resaModel->findOrFail($id);
        $this->checkAgencyAccess($resa);

        if ($resa['statut'] !== 'en_attente') {
            $this->redirectWithError('/manager/reservations', 'Cette réservation ne peut pas être confirmée.');
            return;
        }

        $note = $this->post('notes_agence', null);
        $this->resaModel->confirmer($id, $note);

        $this->redirectWithSuccess('/manager/reservations', 'Réservation confirmée.');
    }

    // ── Manager : refuser ─────────────────────────────────

    public function refuse(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $resa = $this->resaModel->findOrFail($id);
        $this->checkAgencyAccess($resa);

        if ($resa['statut'] !== 'en_attente') {
            $this->redirectWithError('/manager/reservations', 'Cette réservation ne peut pas être refusée.');
            return;
        }

        $motif = $this->post('motif', 'Refusée par l\'agence.');
        $this->resaModel->refuser($id, $motif);

        $this->redirectWithSuccess('/manager/reservations', 'Réservation refusée.');
    }

    // ── Manager : terminer / clôturer ─────────────────────

    public function terminate(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $resa = $this->resaModel->findOrFail($id);
        $this->checkAgencyAccess($resa);

        if ($resa['statut'] !== 'confirmee') {
            $this->redirectWithError('/manager/reservations', 'Seules les réservations confirmées peuvent être clôturées.');
            return;
        }

        $this->resaModel->terminer($id);

        // Remettre la voiture en disponible
        $this->carModel->updateStatut($resa['car_id'], 'disponible');

        $this->redirectWithSuccess('/manager/reservations', 'Réservation clôturée. Voiture remise en disponible.');
    }

    // ── Super manager : toutes les réservations ───────────

    public function adminIndex(): void
    {
        $this->requireRole('super_manager');

        $reservations = $this->resaModel->allFull();
        $stats        = $this->resaModel->stats();

        $this->render('admin/reservations/index', [
            'reservations' => $reservations,
            'stats'        => $stats,
            'flash'        => $this->getFlash(),
        ]);
    }

    // ── Helpers ───────────────────────────────────────────

    private function validateDates(string $dateDebut, string $dateFin): array
    {
        $errors = [];

        if (empty($dateDebut)) {
            $errors['date_debut'][] = 'La date de début est obligatoire.';
        }
        if (empty($dateFin)) {
            $errors['date_fin'][] = 'La date de fin est obligatoire.';
        }

        if (empty($errors)) {
            $debut = new DateTime($dateDebut);
            $fin   = new DateTime($dateFin);
            $today = new DateTime('today');

            if ($debut < $today) {
                $errors['date_debut'][] = 'La date de début ne peut pas être dans le passé.';
            }
            if ($fin <= $debut) {
                $errors['date_fin'][] = 'La date de fin doit être postérieure à la date de début.';
            }
        }

        return $errors;
    }

    private function checkAccess(array $resa): void
    {
        $role = $this->authRole();
        $id   = $this->authId();

        $ok = match ($role) {
            'super_manager'  => true,
            'agency_manager' => $this->resaModel->appartientALAgence($resa['id'], $this->resolveAgencyId()),
            'client'         => (int) $resa['client_id'] === $id,
            default          => false,
        };

        if (!$ok) {
            http_response_code(403);
            $this->render('errors/403');
            exit;
        }
    }

    private function checkAgencyAccess(array $resa): void
    {
        if ($this->authRole() === 'super_manager') return;

        $agencyId = $this->resolveAgencyId();
        if ((int) $resa['agency_id'] !== $agencyId) {
            http_response_code(403);
            $this->render('errors/403');
            exit;
        }
    }

    private function resolveAgencyId(): int
    {
        if ($this->authRole() === 'super_manager') {
            return (int) $this->get('agency_id', 0);
        }
        $agency = $this->agencyModel->findByManager($this->authId());
        return $agency ? (int) $agency['id'] : 0;
    }
}
