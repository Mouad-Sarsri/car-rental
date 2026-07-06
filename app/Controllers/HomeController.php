<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class HomeController extends Controller
{
    private Car $carModel;
    private Agency $agencyModel;
    private User $userModel;

    public function __construct()
    {
        $this->carModel    = new Car();
        $this->agencyModel = new Agency();
        $this->userModel   = new User();
    }
    public function index(): void
    {
        // Rediriger les utilisateurs connectés vers leur dashboard
        if ($this->isAuthenticated()) {
            $this->redirect(match($this->authRole()) {
                'super_manager'  => '/admin/dashboard',
                'agency_manager' => '/manager/dashboard',
                default          => '/dashboard',
            });
        }

        // Voitures vedettes : 6 disponibles aléatoires
        $featured_cars = $this->carModel->query(
            "SELECT c.*, a.nom AS agency_nom, a.ville AS agency_ville
             FROM cars c
             JOIN agencies a ON a.id = c.agency_id
             WHERE c.statut = 'disponible' AND a.actif = 1
             ORDER BY RAND()
             LIMIT 6"
        );

        $agencies = $this->agencyModel->allActive();
        $villes   = $this->agencyModel->villes();

        // Stats rapides pour le hero
        $stats = [
            'total_cars'     => $this->carModel->count('statut', 'disponible'),
            'total_agencies' => $this->agencyModel->count('actif', 1),
            'total_clients'  => $this->userModel->count('role', 'client'),
        ];

        $this->render('home/index', [
            'featured_cars' => $featured_cars,
            'agencies'      => $agencies,
            'villes'        => $villes,
            'stats'         => $stats,
            'authUser'      => $this->authUser(),
        ]);
    }

    public function agences(): void
    {
        $agencyModel = new Agency();
        $carModel    = new Car();

        $agencies = $agencyModel->allActive();

        // Charger les voitures disponibles pour chaque agence
        foreach ($agencies as &$agency) {
            $agency['cars'] = $carModel->query(
                "SELECT * FROM cars WHERE agency_id = ? AND statut = 'disponible'
                 ORDER BY prix_jour ASC",
                [$agency['id']]
            );
        }

        $this->render('home/agences', [
            'agencies' => $agencies,
            'pageTitle'=> 'Nos agences',
        ]);
    }
}
