<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class CarController extends Controller
{
    private Car    $carModel;
    private Agency $agencyModel;

    public function __construct()
    {
        $this->carModel    = new Car();
        $this->agencyModel = new Agency();
    }

    // ── Catalogue public ──────────────────────────────────

    /** Liste publique avec filtres */
    public function index(): void
    {
        $filters = [
            'ville'        => $this->get('ville', ''),
            'agency_id'    => $this->get('agency_id', ''),
            'marque'       => $this->get('marque', ''),
            'carburant'    => $this->get('carburant', ''),
            'transmission' => $this->get('transmission', ''),
            'prix_max'     => $this->get('prix_max', ''),
            'places_min'   => $this->get('places_min', ''),
            'climatisation'=> $this->get('climatisation', ''),
            'date_debut'   => $this->get('date_debut', ''),
            'date_fin'     => $this->get('date_fin', ''),
            'sort'         => $this->get('sort', ''),
        ];

        // Nettoyer les filtres vides
        $filters = array_filter($filters, fn($v) => $v !== '');

        $cars     = $this->carModel->search($filters);
        $agencies = $this->agencyModel->allActive();
        $villes   = $this->agencyModel->villes();
        $marques  = $this->carModel->marques();

        $this->render('cars/index', [
            'cars'     => $cars,
            'agencies' => $agencies,
            'villes'   => $villes,
            'marques'  => $marques,
            'filters'  => $filters,
        ]);
    }

    /** Fiche détail d'une voiture */
    public function show(int $id): void
    {
        $car = $this->carModel->findWithAgency($id);
        if (!$car) {
            $this->render('errors/404'); return;
        }

        $reservationsActives = $this->carModel->query(
            "SELECT date_debut, date_fin FROM reservations
             WHERE car_id = ? AND statut IN ('en_attente','confirmee')
             ORDER BY date_debut",
            [$id]
        );

        $this->render('cars/show', [
            'car'                 => $car,
            'reservations_actives'=> $reservationsActives,
            'flash'               => $this->getFlash(),
        ]);
    }

    // ── Gestion par manager ───────────────────────────────

    /** Liste des voitures de l'agence du manager connecté */
    public function managerIndex(): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $agencyId = $this->resolveAgencyId();
        $cars     = $this->carModel->byAgency($agencyId);

        $this->render('manager/cars/index', [
            'cars'  => $cars,
            'flash' => $this->getFlash(),
        ]);
    }

    /** Toutes les voitures (super manager) */
    public function adminIndex(): void
    {
        $this->requireRole('super_manager');

        $cars = $this->carModel->allWithAgency();
        $this->render('admin/cars/index', [
            'cars'  => $cars,
            'flash' => $this->getFlash(),
        ]);
    }

    // ── Création ──────────────────────────────────────────

    public function create(): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $agencies = $this->authRole() === 'super_manager'
            ? $this->agencyModel->allActive()
            : [$this->agencyModel->findByManager($this->authId())];

        $this->render('manager/cars/create', ['agencies' => $agencies]);
    }

    public function store(): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);

        $data   = $this->postData();
        $errors = $this->validateCarData($data);

        // Vérifier l'immatriculation unique
        if ($this->carModel->exists('immatriculation', $data['immatriculation'])) {
            $errors['immatriculation'][] = 'Cette immatriculation est déjà enregistrée.';
        }

        // Un manager ne peut créer que pour son agence
        if ($this->authRole() === 'agency_manager') {
            $myAgency = $this->agencyModel->findByManager($this->authId());
            $data['agency_id'] = $myAgency['id'] ?? 0;
        }

        if (!empty($errors)) {
            $agencies = $this->authRole() === 'super_manager'
                ? $this->agencyModel->allActive()
                : [$this->agencyModel->findByManager($this->authId())];

            $this->render('manager/cars/create', [
                'errors'   => $errors,
                'old'      => $data,
                'agencies' => $agencies,
            ]);
            return;
        }

        // Upload photo
        $data['photo'] = $this->handlePhotoUpload();

        $this->carModel->create($data);

        $redirect = $this->authRole() === 'super_manager'
            ? '/admin/cars'
            : '/manager/cars';

        $this->redirectWithSuccess($redirect, 'Voiture ajoutée avec succès.');
    }

    // ── Modification ──────────────────────────────────────

    public function edit(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);
        $car = $this->carOrFail($id);

        $agencies = $this->authRole() === 'super_manager'
            ? $this->agencyModel->allActive()
            : [$this->agencyModel->find($car['agency_id'])];

        $this->render('manager/cars/edit', [
            'car'      => $car,
            'agencies' => $agencies,
        ]);
    }

    public function update(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);
        $car    = $this->carOrFail($id);
        $data   = $this->postData();
        $errors = $this->validateCarData($data, isUpdate: true);

         if (empty($data['agency_id'])) {
            $data['agency_id'] = $car['agency_id'];
        }

        if ($this->carModel->exists('immatriculation', $data['immatriculation'], $id)) {
            $errors['immatriculation'][] = 'Cette immatriculation est déjà utilisée.';
        }

        if (!empty($errors)) {
            $this->render('manager/cars/edit', [
                'errors' => $errors,
                'car'    => array_merge($car, $data),
            ]);
            return;
        }

        // Nouvelle photo si fournie
        $newPhoto = $this->handlePhotoUpload();
        if ($newPhoto) {
            $data['photo'] = $newPhoto;
        }

        $this->carModel->update($id, $data);

        $redirect = $this->authRole() === 'super_manager'
            ? '/admin/cars'
            : '/manager/cars';

        $this->redirectWithSuccess($redirect, 'Voiture mise à jour.');
    }

    // ── Changer le statut ─────────────────────────────────

    public function updateStatut(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);
        $this->carOrFail($id);

        $statut = $this->post('statut', '');
        $allowed = ['disponible', 'maintenance', 'inactive'];

        if (!in_array($statut, $allowed, true)) {
            $this->redirectWithError('/manager/cars', 'Statut invalide.');
            return;
        }

        $this->carModel->updateStatut($id, $statut);
        $this->redirectWithSuccess('/manager/cars', 'Statut de la voiture mis à jour.');
    }

    // ── Suppression ───────────────────────────────────────

    public function destroy(int $id): void
    {
        $this->requireRole(['agency_manager', 'super_manager']);
        $car = $this->carOrFail($id);

        // Vérifier qu'aucune réservation active n'existe
        $resaActives = $this->carModel->query(
            "SELECT COUNT(*) AS total FROM reservations
             WHERE car_id = ? AND statut IN ('en_attente','confirmee')",
            [$id]
        );

        if (($resaActives[0]['total'] ?? 0) > 0) {
            $this->redirectWithError('/manager/cars', 'Impossible de supprimer : cette voiture a des réservations actives.');
            return;
        }

        // Supprimer la photo
        if (!empty($car['photo'])) {
            $photoPath = BASE_PATH . '/public/assets/uploads/cars/' . $car['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $this->carModel->delete($id);

        $redirect = $this->authRole() === 'super_manager'
            ? '/admin/cars'
            : '/manager/cars';

        $this->redirectWithSuccess($redirect, 'Voiture supprimée.');
    }

    // ── Helpers ───────────────────────────────────────────

    private function carOrFail(int $id): array
    {
        $car = $this->carModel->findOrFail($id);

        if ($this->authRole() === 'agency_manager') {
            $myAgency = $this->agencyModel->findByManager($this->authId());
            if (!$myAgency || $car['agency_id'] !== $myAgency['id']) {
                http_response_code(403);
                $this->render('errors/403');
                exit;
            }
        }

        return $car;
    }

    private function resolveAgencyId(): int
    {
        if ($this->authRole() === 'super_manager') {
            return (int) $this->get('agency_id', 0);
        }
        $agency = $this->agencyModel->findByManager($this->authId());
        return $agency ? $agency['id'] : 0;
    }

    private function postData(): array
    {
        return [
            'agency_id'       => (int) $this->post('agency_id', 0),
            'marque'          => $this->post('marque', ''),
            'modele'          => $this->post('modele', ''),
            'immatriculation' => strtoupper(trim($this->post('immatriculation', ''))),
            'annee'           => (int) $this->post('annee', date('Y')),
            'couleur'         => $this->post('couleur', ''),
            'nb_places'       => (int) $this->post('nb_places', 5),
            'carburant'       => $this->post('carburant', 'diesel'),
            'transmission'    => $this->post('transmission', 'manuelle'),
            'climatisation'   => (int) $this->post('climatisation', 1),
            'prix_jour'       => (float) $this->post('prix_jour', 0),
            'caution'         => (float) $this->post('caution', 0),
            'statut'          => $this->post('statut', 'disponible'),
            'description'     => $this->post('description', ''),
        ];
    }

    private function validateCarData(array $data, bool $isUpdate = false): array
    {
        $rules = [
            'marque'          => 'required|max:100',
            'modele'          => 'required|max:100',
            'immatriculation' => 'required|max:20',
            'annee'           => 'required|numeric',
            'prix_jour'       => 'required|numeric',
            'carburant'       => 'required|in:essence,diesel,hybride,electrique',
            'transmission'    => 'required|in:manuelle,automatique',
            'statut'          => 'required|in:disponible,louee,maintenance,inactive',
        ];

        if (!$isUpdate) {
            $rules['agency_id'] = 'required|numeric';
        }

        return $this->validate($data, $rules);
    }

    private function handlePhotoUpload(): ?string
    {
        if (empty($_FILES['photo']['name'])) {
            return null;
        }

        $file    = $_FILES['photo'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 3 * 1024 * 1024; // 3 Mo

        if (!in_array($file['type'], $allowed, true)) return null;
        if ($file['size'] > $maxSize)                  return null;
        if ($file['error'] !== UPLOAD_ERR_OK)          return null;

        // Dossier de stockage : public/assets/uploads/cars/
        $uploadDir = BASE_PATH . '/public/assets/uploads/cars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'car_' . uniqid('', true) . '.' . $ext;
        $dest     = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return $filename;
        }
        return null;
    }
}
