<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ── Inscription ───────────────────────────────────────

    public function showRegister(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect($this->dashboardUrl());
        }
        $this->render('auth/register');
    }

    public function register(): void
    {
        $data = [
            'nom'       => $this->post('nom', ''),
            'prenom'    => $this->post('prenom', ''),
            'email'     => $this->post('email', ''),
            'password'  => $this->post('password', ''),
            'password_confirm' => $this->post('password_confirm', ''),
            'telephone' => $this->post('telephone', ''),
        ];

        $errors = $this->validate($data, [
            'nom'      => 'required|max:100',
            'prenom'   => 'required|max:100',
            'email'    => 'required|email|max:191',
            'password' => 'required|min:8',
        ]);

        if ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'][] = 'Les mots de passe ne correspondent pas.';
        }

        if ($this->userModel->exists('email', $data['email'])) {
            $errors['email'][] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            $this->render('auth/register', ['errors' => $errors, 'old' => $data]);
            return;
        }

        $id = $this->userModel->register([
            'nom'       => $data['nom'],
            'prenom'    => $data['prenom'],
            'email'     => $data['email'],
            'password'  => $data['password'],
            'telephone' => $data['telephone'],
            'role'      => 'client',
        ]);

        $user = $this->userModel->find($id);
        $this->loginUser($user);

        $this->redirectWithSuccess('/dashboard', 'Bienvenue ' . $user['prenom'] . ' !');
    }

    // ── Connexion ─────────────────────────────────────────

    public function showLogin(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect($this->dashboardUrl());
        }
        $this->render('auth/login');
    }

    public function login(): void
    {
        $email    = $this->post('email', '');
        $password = $this->post('password', '');

        $errors = $this->validate(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email', 'password' => 'required']
        );

        if (!empty($errors)) {
            $this->render('auth/login', ['errors' => $errors, 'old' => ['email' => $email]]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $this->render('auth/login', [
                'errors' => ['email' => ['Email ou mot de passe incorrect.']],
                'old'    => ['email' => $email],
            ]);
            return;
        }

        if (!$user['actif']) {
            $this->render('auth/login', [
                'errors' => ['email' => ['Votre compte a été désactivé. Contactez l\'administrateur.']],
            ]);
            return;
        }

        $this->loginUser($user);
        $this->redirect($this->dashboardUrl());
    }

    // ── Déconnexion ───────────────────────────────────────

    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }

    // ── Profil ────────────────────────────────────────────

    public function showProfile(): void
    {
        $this->requireAuth();
        $user = $this->userModel->findOrFail($this->authId());
        $this->render('auth/profile', ['user' => $this->userModel->sanitize($user)]);
    }

    public function updateProfile(): void
    {
        $this->requireAuth();
        $id = $this->authId();

        $data = [
            'nom'       => $this->post('nom', ''),
            'prenom'    => $this->post('prenom', ''),
            'email'     => $this->post('email', ''),
            'telephone' => $this->post('telephone', ''),
        ];

        $errors = $this->validate($data, [
            'nom'    => 'required|max:100',
            'prenom' => 'required|max:100',
            'email'  => 'required|email|max:191',
        ]);

        if ($this->userModel->exists('email', $data['email'], $id)) {
            $errors['email'][] = 'Cette adresse email est déjà utilisée.';
        }

        if (!empty($errors)) {
            $user = $this->userModel->find($id);
            $this->render('auth/profile', ['errors' => $errors, 'user' => $user]);
            return;
        }

        $this->userModel->update($id, $data);

        // Mettre à jour la session
        $user = $this->userModel->find($id);
        $_SESSION['user']      = $this->userModel->sanitize($user);
        $_SESSION['user_role'] = $user['role'];

        $this->redirectWithSuccess('/profile', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(): void
    {
        $this->requireAuth();
        $id = $this->authId();

        $current    = $this->post('current_password', '');
        $new        = $this->post('new_password', '');
        $confirm    = $this->post('confirm_password', '');

        $user = $this->userModel->findOrFail($id);

        if (!$this->userModel->verifyPassword($current, $user['password'])) {
            $this->redirectWithError('/profile', 'Mot de passe actuel incorrect.');
            return;
        }

        if (strlen($new) < 8) {
            $this->redirectWithError('/profile', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
            return;
        }

        if ($new !== $confirm) {
            $this->redirectWithError('/profile', 'Les nouveaux mots de passe ne correspondent pas.');
            return;
        }

        $this->userModel->updatePassword($id, $new);
        $this->redirectWithSuccess('/profile', 'Mot de passe modifié avec succès.');
    }

    // ── Helpers privés ────────────────────────────────────

    private function loginUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user']      = $this->userModel->sanitize($user);
    }

    private function dashboardUrl(): string
    {
        return match ($this->authRole()) {
            'super_manager'   => '/admin/dashboard',
            'agency_manager'  => '/manager/dashboard',
            default           => '/dashboard',
        };
    }
}
