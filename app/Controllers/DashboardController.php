<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Agency;
use App\Models\Car;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function client(): void
    {
        $this->requireRole('client');

        $resaModel    = new Reservation();
        $reservations = $resaModel->byClient($this->authId());
        $stats        = $resaModel->stats();

        // Séparer les actives
        $actives = array_filter($reservations, fn($r) => in_array($r['statut'], ['en_attente','confirmee']));

        $this->render('dashboard/client', [
            'reservations' => $reservations,
            'actives'      => array_values($actives),
            'flash'        => $this->getFlash(),
        ]);
    }
}
