<?php $pageTitle = 'Tableau de bord — ' . htmlspecialchars($agency['nom']); ?>

<h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 text-warning me-2"></i><?= htmlspecialchars($agency['nom']) ?></h2>
<p class="text-muted mb-4"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($agency['adresse'] . ', ' . $agency['ville']) ?></p>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning"><?= number_format($stats['chiffre_affaires'] ?? 0, 0) ?></div>
                <div class="small text-muted">MAD de CA</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning"><?= $stats['resa_en_attente'] ?? 0 ?></div>
                <div class="small text-muted">En attente</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="display-6 fw-bold text-success"><?= $stats['resa_confirmees'] ?? 0 ?></div>
                <div class="small text-muted">Confirmées</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="display-6 fw-bold text-info"><?= $stats['total_voitures'] ?? 0 ?></div>
                <div class="small text-muted">Voitures</div>
            </div>
        </div>
    </div>
</div>

<!-- Raccourcis -->
<div class="row g-3">
    <div class="col-md-4">
        <a href="/manager/reservations?statut=en_attente" class="card border-warning shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-hourglass-split display-5 text-warning"></i>
                <p class="fw-bold mt-2 mb-0">Réservations en attente</p>
                <span class="badge bg-warning text-dark"><?= $stats['resa_en_attente'] ?? 0 ?></span>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/manager/cars" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-car-front display-5 text-success"></i>
                <p class="fw-bold mt-2 mb-0">Gérer mes voitures</p>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/manager/reservations" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check display-5 text-info"></i>
                <p class="fw-bold mt-2 mb-0">Toutes les réservations</p>
            </div>
        </a>
    </div>
</div>
