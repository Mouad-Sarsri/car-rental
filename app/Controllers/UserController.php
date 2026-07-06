<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class UserController extends Controller
{
    private User   $userModel;
    private Agency $agencyModel;

    public function __construct()
    {
        $this->userModel   = new User();
        $this->agencyModel = new Agency();
    }

    // ── Liste ─────────────────────────────────────────────

    public function index(): void
    {
        $this->requireRole('super_manager');

        $role  = $this->get('role', '');
        $users = $role
            ? $this->userModel->where('role', $role, 'nom')
            : $this->userModel->all('nom');

        $counts = $this->userModel->countByRole();

        $this->render('admin/users/index', [
            'users'  => $users,
            'counts' => $counts,
            'role'   => $role,
            'flash'  => $this->getFlash(),
        ]);
    }

    // ── Détail ────────────────────────────────────────────

    public function show(int $id): void
    {
        $this->requireRole('super_manager');

        $user   = $this->userModel->findOrFail($id);
        $agency = null;

        if ($user['role'] === 'agency_manager') {
            $agency = $this->agencyModel->findByManager($id);
        }

        $this->render('admin/users/show', [
            'user'   => $this->userModel->sanitize($user),
            'agency' => $agency,
        ]);
    }

    // ── Création ──────────────────────────────────────────

    public function create(): void
    {
        $this->requireRole('super_manager');
        $this->render('admin/users/create');
    }

    public function store(): void
    {
        $this->requireRole('super_manager');

        $data = $this->postData();

        $errors = $this->validate($data, [
            'nom'      => 'required|max:100',
            'prenom'   => 'required|max:100',
            'email'    => 'required|email|max:191',
            'password' => 'required|min:8',
            'role'     => 'required|in:client,agency_manager,super_manager',
        ]);

        if ($this->userModel->exists('email', $data['email'])) {
            $errors['email'][] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            $this->render('admin/users/create', ['errors' => $errors, 'old' => $data]);
            return;
        }

        $this->userModel->register($data);
        $this->redirectWithSuccess('/admin/users', 'Utilisateur créé avec succès.');
    }

    // ── Modification ──────────────────────────────────────

    public function edit(int $id): void
    {
        $this->requireRole('super_manager');
        $user = $this->userModel->findOrFail($id);

        $this->render('admin/users/edit', [
            'user' => $this->userModel->sanitize($user),
        ]);
    }

    public function update(int $id): void
    {
        $this->requireRole('super_manager');
        $this->userModel->findOrFail($id);

        $data = $this->postData(withPassword: false);

        $errors = $this->validate($data, [
            'nom'    => 'required|max:100',
            'prenom' => 'required|max:100',
            'email'  => 'required|email|max:191',
            'role'   => 'required|in:client,agency_manager,super_manager',
        ]);

        if ($this->userModel->exists('email', $data['email'], $id)) {
            $errors['email'][] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            $this->render('admin/users/edit', [
                'errors' => $errors,
                'user'   => array_merge(['id' => $id], $data),
            ]);
            return;
        }

        $this->userModel->update($id, $data);

        // Si le rôle change, s'assurer que la session est cohérente
        if ($id === $this->authId()) {
            $_SESSION['user_role'] = $data['role'];
        }

        $this->redirectWithSuccess('/admin/users', 'Utilisateur mis à jour.');
    }

    // ── Reset mot de passe par admin ──────────────────────

    public function resetPassword(int $id): void
    {
        $this->requireRole('super_manager');
        $this->userModel->findOrFail($id);

        $newPassword = $this->post('new_password', '');

        if (strlen($newPassword) < 8) {
            $this->redirectWithError("/admin/users/{$id}", 'Le mot de passe doit contenir au moins 8 caractères.');
            return;
        }

        $this->userModel->updatePassword($id, $newPassword);
        $this->redirectWithSuccess('/admin/users', 'Mot de passe réinitialisé.');
    }

    // ── Activer / désactiver ──────────────────────────────

    public function toggle(int $id): void
    {
        $this->requireRole('super_manager');

        if ($id === $this->authId()) {
            $this->redirectWithError('/admin/users', 'Vous ne pouvez pas désactiver votre propre compte.');
            return;
        }

        $this->userModel->findOrFail($id);
        $this->userModel->toggleActif($id);
        $this->redirectWithSuccess('/admin/users', 'Statut du compte modifié.');
    }

    // ── Suppression ───────────────────────────────────────

    public function destroy(int $id): void
    {
        $this->requireRole('super_manager');

        if ($id === $this->authId()) {
            $this->redirectWithError('/admin/users', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }

        $user = $this->userModel->findOrFail($id);

        // Vérifier les réservations actives
        $activeResa = $this->userModel->query(
            "SELECT COUNT(*) AS total FROM reservations
             WHERE client_id = ? AND statut IN ('en_attente','confirmee')",
            [$id]
        );

        if (($activeResa[0]['total'] ?? 0) > 0) {
            $this->redirectWithError('/admin/users', 'Impossible de supprimer : cet utilisateur a des réservations actives.');
            return;
        }

        $this->userModel->delete($id);
        $this->redirectWithSuccess('/admin/users', "Utilisateur {$user['prenom']} {$user['nom']} supprimé.");
    }

    // ── Dashboard super manager ───────────────────────────

    public function adminDashboard(): void
    {
        $this->requireRole('super_manager');

        $userCounts   = $this->userModel->countByRole();
        $agencyStats  = $this->agencyModel->withCarCount();
        $resaModel    = new Reservation();
        $globalStats  = $resaModel->stats();
        $resaParJour  = $resaModel->parJour(null, 30);

        $this->render('admin/dashboard', [
            'user_counts'  => $userCounts,
            'agency_stats' => $agencyStats,
            'global_stats' => $globalStats,
            'resa_par_jour'=> $resaParJour,
            'flash'        => $this->getFlash(),
        ]);
    }

    // ── Helper ────────────────────────────────────────────

    private function postData(bool $withPassword = true): array
    {
        $data = [
            'nom'       => $this->post('nom', ''),
            'prenom'    => $this->post('prenom', ''),
            'email'     => $this->post('email', ''),
            'role'      => $this->post('role', 'client'),
            'telephone' => $this->post('telephone', ''),
            'actif'     => (int) $this->post('actif', 1),
        ];

        if ($withPassword) {
            $data['password'] = $this->post('password', '');
        }

        return $data;
    }
}
