<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class AgencyController extends Controller
{
    private Agency $agencyModel;
    private User   $userModel;

    public function __construct()
    {
        $this->agencyModel = new Agency();
        $this->userModel   = new User();
    }

    // ── Super Manager : liste complète ────────────────────

    public function index(): void
    {
        $this->requireRole('super_manager');

        $agencies = $this->agencyModel->withCarCount();
        $this->render('agencies/index', [
            'agencies' => $agencies,
            'flash'    => $this->getFlash(),
        ]);
    }

    // ── Détail d'une agence ───────────────────────────────

    public function show(int $id): void
    {
        $this->requireAuth();

        $agency = $this->agencyModel->findWithManager($id);
        if (!$agency) {
            $this->render('errors/404'); return;
        }

        // Un manager ne peut voir que son agence
        if ($this->authRole() === 'agency_manager') {
            $myAgency = $this->agencyModel->findByManager($this->authId());
            if (!$myAgency || $myAgency['id'] !== $id) {
                $this->render('errors/403'); return;
            }
        }

        $stats = $this->agencyModel->stats($id);

        $this->render('agencies/show', [
            'agency' => $agency,
            'stats'  => $stats,
            'flash'  => $this->getFlash(),
        ]);
    }

    // ── Création ──────────────────────────────────────────

    public function create(): void
    {
        $this->requireRole('super_manager');

        $managers = $this->userModel->availableManagers();
        $this->render('agencies/create', ['managers' => $managers]);
    }

    public function store(): void
    {
        $this->requireRole('super_manager');

        $data = $this->postData();

        $errors = $this->validate($data, [
            'nom'     => 'required|max:150',
            'adresse' => 'required|max:255',
            'ville'   => 'required|max:100',
        ]);

        if (!empty($data['email']) && $this->agencyModel->exists('email', $data['email'])) {
            $errors['email'][] = 'Cette adresse email est déjà utilisée par une autre agence.';
        }

        if (!empty($errors)) {
            $managers = $this->userModel->availableManagers();
            $this->render('agencies/create', [
                'errors'   => $errors,
                'old'      => $data,
                'managers' => $managers,
            ]);
            return;
        }

        $id = $this->agencyModel->create($data);
        $this->redirectWithSuccess('/admin/agencies', "Agence créée avec succès.");
    }

    // ── Modification ──────────────────────────────────────

    public function edit(int $id): void
    {
        $this->requireAuth();
        $agency = $this->agencyOrFail($id);

        if ($this->authRole() === 'super_manager') {
            $agency   = $this->agencyModel->findWithManager($id);
            $managers = $this->userModel->allManagers();
            $this->render('admin/agencies/edit', [
                'agency'   => $agency,
                'managers' => $managers,
            ]);
        } else {
            $this->render('agencies/edit', [
                'agency'   => $agency,
                'managers' => [],
            ]);
        }
    }
    public function update(int $id): void
    {
        $this->requireAuth();
        $agency = $this->agencyOrFail($id);

        $data = $this->postData();

        // Un manager ne peut modifier que certains champs
        if ($this->authRole() === 'agency_manager') {
            $data = array_intersect_key($data, array_flip([
                'adresse', 'ville', 'code_postal',
                'telephone', 'email', 'description',
            ]));
        }

        $errors = $this->validate($data, [
            'nom'     => 'max:150',
            'adresse' => 'max:255',
            'ville'   => 'max:100',
        ]);

        if (!empty($data['email']) && $this->agencyModel->exists('email', $data['email'], $id)) {
            $errors['email'][] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            $managers = $this->userModel->allManagers();
            $this->render('agencies/edit', [
                'errors'   => $errors,
                'agency'   => array_merge($agency, $data),
                'managers' => $managers,
            ]);
            return;
        }

        $this->agencyModel->update($id, $data);


        
        $redirect = $this->authRole() === 'super_manager'
            ? '/admin/agencies'
            : '/manager/agency';

        $this->redirectWithSuccess($redirect, 'Agence mise à jour.');
    }

    // ── Activation / désactivation ────────────────────────

    public function toggle(int $id): void
    {
        $this->requireRole('super_manager');
        $this->agencyModel->findOrFail($id);
        $this->agencyModel->toggleActif($id);
        $this->redirectWithSuccess('/admin/agencies', 'Statut de l\'agence modifié.');
    }

    // ── Suppression ───────────────────────────────────────

    public function destroy(int $id): void
    {
        $this->requireRole('super_manager');
        $this->agencyModel->findOrFail($id);
        $this->agencyModel->delete($id);
        $this->redirectWithSuccess('/admin/agencies', 'Agence supprimée.');
    }

    // ── Dashboard manager : son agence ───────────────────

    public function managerDashboard(): void
    {
        $this->requireRole('agency_manager');

        $agency = $this->agencyModel->findByManager($this->authId());
        if (!$agency) {
            $this->render('manager/no_agency');
            return;
        }

        $stats = $this->agencyModel->stats($agency['id']);
        $this->render('manager/dashboard', [
            'agency' => $agency,
            'stats'  => $stats,
            'flash'  => $this->getFlash(),
        ]);
    }

    // ── Helpers ───────────────────────────────────────────

    private function agencyOrFail(int $id): array
    {
        $agency = $this->agencyModel->findOrFail($id);

        if ($this->authRole() === 'agency_manager') {
            $myAgency = $this->agencyModel->findByManager($this->authId());
            if (!$myAgency || $myAgency['id'] !== $id) {
                http_response_code(403);
                $this->render('errors/403');
                exit;
            }
        }

        return $agency;
    }

    private function postData(): array
    {
        return [
            'manager_id'  => $this->post('manager_id') ?: null,
            'nom'         => $this->post('nom', ''),
            'adresse'     => $this->post('adresse', ''),
            'ville'       => $this->post('ville', ''),
            'code_postal' => $this->post('code_postal', ''),
            'pays'        => $this->post('pays', 'Maroc'),
            'telephone'   => $this->post('telephone', ''),
            'email'       => $this->post('email', ''),
            'description' => $this->post('description', ''),
        ];
    }

    // ── Manager : modifier son agence (sans ID dans l'URL) ─

    public function managerEdit(): void
    {
        $this->requireRole('agency_manager');
        $agency = $this->agencyModel->findByManager($this->authId());
        if (!$agency) { $this->render('manager/no_agency'); return; }
        $this->render('agencies/edit', ['agency' => $agency, 'managers' => []]);
    }

    public function managerUpdate(): void
    {
        $this->requireRole('agency_manager');
        $agency = $this->agencyModel->findByManager($this->authId());
        if (!$agency) { $this->redirect('/manager/dashboard'); }
        $this->update((int)$agency['id']);
    }
}