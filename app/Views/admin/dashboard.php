<?php $pageTitle = 'Tableau de bord — Administration';
$countsByRole = array_column($user_counts, 'total', 'role');
?>

<h2 class="fw-bold mb-4"><i class="bi bi-speedometer2 text-warning me-2"></i>Tableau de bord</h2>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning"><?= number_format($global_stats['chiffre_affaires'] ?? 0, 0) ?></div>
                <div class="small text-muted">MAD de CA total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-success"><?= $global_stats['total'] ?? 0 ?></div>
                <div class="small text-muted">Réservations totales</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-info"><?= $countsByRole['client'] ?? 0 ?></div>
                <div class="small text-muted">Clients inscrits</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm text-center h-100">
            <div class="card-body">
                <div class="display-6 fw-bold text-warning"><?= $global_stats['en_attente'] ?? 0 ?></div>
                <div class="small text-muted">Réservations en attente</div>
            </div>
        </div>
    </div>
</div>

<!-- Agences -->
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="bi bi-building me-2"></i>Agences</h6>
        <a href="/admin/agencies/create" class="btn btn-warning btn-sm">
            <i class="bi bi-plus me-1"></i>Nouvelle agence
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Agence</th>
                    <th>Ville</th>
                    <th>Voitures</th>
                    <th>Disponibles</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agency_stats as $a): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($a['nom']) ?></td>
                        <td><?= htmlspecialchars($a['ville']) ?></td>
                        <td><?= $a['total_cars'] ?></td>
                        <td>
                            <span class="badge bg-success"><?= $a['cars_disponibles'] ?></span>
                            <?php if ($a['cars_louees'] > 0): ?>
                                <span class="badge bg-warning text-dark"><?= $a['cars_louees'] ?> louées</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $a['actif'] ? 'success' : 'secondary' ?>">
                                <?= $a['actif'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <a href="/admin/agencies/<?= $a['id'] ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Raccourcis -->
<div class="row g-3">
    <div class="col-md-3">
        <a href="/admin/users" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-people display-6 text-primary"></i>
                <p class="mb-0 mt-2 fw-bold">Utilisateurs</p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/agencies" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-building display-6 text-warning"></i>
                <p class="mb-0 mt-2 fw-bold">Agences</p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/cars" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-car-front display-6 text-success"></i>
                <p class="mb-0 mt-2 fw-bold">Voitures</p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/reservations" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check display-6 text-info"></i>
                <p class="mb-0 mt-2 fw-bold">Réservations</p>
            </div>
        </a>
    </div>
</div>
