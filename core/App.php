<?php

declare(strict_types=1);

namespace App\Core;

/**
 * App — Bootstrap du framework
 */
class App
{
    private Router $router;

    public function __construct()
    {
        $this->boot();
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function boot(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'secure'   => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    private function registerRoutes(): void
    {
        $r = $this->router;

        // Alias court pour lisibilité
        $ctrl = fn(string $c) => "App\\Controllers\\{$c}";

        // ── Auth ──────────────────────────────────────────
        $r->get ('/register',         $ctrl('AuthController'), 'showRegister');
        $r->post('/register',         $ctrl('AuthController'), 'register');
        $r->get ('/login',            $ctrl('AuthController'), 'showLogin');
        $r->post('/login',            $ctrl('AuthController'), 'login');
        $r->get ('/logout',           $ctrl('AuthController'), 'logout');
        $r->get ('/profile',          $ctrl('AuthController'), 'showProfile');
        $r->post('/profile',          $ctrl('AuthController'), 'updateProfile');
        $r->post('/profile/password', $ctrl('AuthController'), 'updatePassword');

        // ── Catalogue public ──────────────────────────────
        $r->get('/cars',      $ctrl('CarController'), 'index');
        $r->get('/cars/{id}', $ctrl('CarController'), 'show');

        // ── Réservations client ───────────────────────────
        $r->get ('/dashboard',               $ctrl('DashboardController'),   'client');
        $r->get ('/reservations',            $ctrl('ReservationController'), 'myReservations');
        $r->get ('/reservations/new',        $ctrl('ReservationController'), 'showCreate');
        $r->post('/reservations',            $ctrl('ReservationController'), 'store');
        $r->get ('/reservations/{id}',       $ctrl('ReservationController'), 'show');
        $r->post('/reservations/{id}/cancel',$ctrl('ReservationController'), 'cancel');

        // ── Manager : dashboard ───────────────────────────
        $r->get('/manager/dashboard', $ctrl('AgencyController'), 'managerDashboard');

        // ── Manager : voitures ────────────────────────────
        $r->get ('/manager/cars',            $ctrl('CarController'), 'managerIndex');
        $r->get ('/manager/cars/create',     $ctrl('CarController'), 'create');
        $r->post('/manager/cars',            $ctrl('CarController'), 'store');
        $r->get ('/manager/cars/{id}/edit',  $ctrl('CarController'), 'edit');
        $r->post('/manager/cars/{id}',       $ctrl('CarController'), 'update');
        $r->post('/manager/cars/{id}/statut',$ctrl('CarController'), 'updateStatut');
        $r->post('/manager/cars/{id}/delete',$ctrl('CarController'), 'destroy');

        // ── Manager : réservations ────────────────────────
        $r->get ('/manager/reservations',                  $ctrl('ReservationController'), 'agencyIndex');
        $r->get ('/manager/reservations/{id}',             $ctrl('ReservationController'), 'show');
        $r->post('/manager/reservations/{id}/confirm',     $ctrl('ReservationController'), 'confirm');
        $r->post('/manager/reservations/{id}/refuse',      $ctrl('ReservationController'), 'refuse');
        $r->post('/manager/reservations/{id}/terminate',   $ctrl('ReservationController'), 'terminate');

        // ── Manager : agence ──────────────────────────────
        $r->get ('/manager/agency', $ctrl('AgencyController'), 'managerEdit');
        $r->post('/manager/agency', $ctrl('AgencyController'), 'managerUpdate');

        // ── Admin : dashboard ─────────────────────────────
        $r->get('/admin/dashboard', $ctrl('UserController'), 'adminDashboard');

        // ── Admin : utilisateurs ──────────────────────────
        $r->get ('/admin/users',               $ctrl('UserController'), 'index');
        $r->get ('/admin/users/create',        $ctrl('UserController'), 'create');
        $r->post('/admin/users',               $ctrl('UserController'), 'store');
        $r->get ('/admin/users/{id}',          $ctrl('UserController'), 'show');
        $r->get ('/admin/users/{id}/edit',     $ctrl('UserController'), 'edit');
        $r->post('/admin/users/{id}',          $ctrl('UserController'), 'update');
        $r->post('/admin/users/{id}/toggle',   $ctrl('UserController'), 'toggle');
        $r->post('/admin/users/{id}/password', $ctrl('UserController'), 'resetPassword');
        $r->post('/admin/users/{id}/delete',   $ctrl('UserController'), 'destroy');

        // ── Admin : agences ───────────────────────────────
        $r->get ('/admin/agencies',              $ctrl('AgencyController'), 'index');
        $r->get ('/admin/agencies/create',       $ctrl('AgencyController'), 'create');
        $r->post('/admin/agencies',              $ctrl('AgencyController'), 'store');
        $r->get ('/admin/agencies/{id}',         $ctrl('AgencyController'), 'show');
        $r->get ('/admin/agencies/{id}/edit',    $ctrl('AgencyController'), 'edit');
        $r->post('/admin/agencies/{id}',         $ctrl('AgencyController'), 'update');
        $r->post('/admin/agencies/{id}/toggle',  $ctrl('AgencyController'), 'toggle');
        $r->post('/admin/agencies/{id}/delete',  $ctrl('AgencyController'), 'destroy');

        // ── Admin : voitures & réservations ──────────────
        $r->get('/admin/cars',               $ctrl('CarController'),         'adminIndex');
        $r->get('/admin/reservations',       $ctrl('ReservationController'), 'adminIndex');
        $r->get('/admin/reservations/{id}',  $ctrl('ReservationController'), 'show');

        // ── Pages publiques ───────────────────────────────
        $r->get ('/',        $ctrl('HomeController'),    'index');
        $r->get ('/agences', $ctrl('HomeController'),    'agences');
        $r->get ('/contact', $ctrl('ContactController'), 'index');
        $r->post('/contact', $ctrl('ContactController'), 'send');
    }

    public function run(): void
    {
        $this->router->dispatch(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI']
        );
    }
}
