<?php $pageTitle = htmlspecialchars($agency['nom']); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/agencies">Agences</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <div class="display-6 mb-2"><i class="bi bi-building text-warning"></i></div>
                <h5 class="fw-bold"><?= $pageTitle ?></h5>
                <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($agency['adresse'].', '.$agency['ville']) ?></p>
                <?php if ($agency['telephone']): ?><p class="text-muted small mb-1"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($agency['telephone']) ?></p><?php endif; ?>
                <?php if ($agency['email']): ?><p class="text-muted small mb-2"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($agency['email']) ?></p><?php endif; ?>
                <span class="badge bg-<?= $agency['actif'] ? 'success' : 'secondary' ?>"><?= $agency['actif'] ? 'Active' : 'Inactive' ?></span>
                <hr>
                <a href="/admin/agencies/<?= $agency['id'] ?>/edit" class="btn btn-warning btn-sm fw-bold">
                    <i class="bi bi-pencil me-1"></i>Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Stats -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center"><div class="card-body">
                    <div class="fs-3 fw-bold text-info"><?= $stats['total_voitures'] ?? 0 ?></div>
                    <div class="small text-muted">Voitures</div>
                </div></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center"><div class="card-body">
                    <div class="fs-3 fw-bold text-success"><?= $stats['total_reservations'] ?? 0 ?></div>
                    <div class="small text-muted">Réservations</div>
                </div></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center"><div class="card-body">
                    <div class="fs-3 fw-bold text-warning"><?= $stats['resa_en_attente'] ?? 0 ?></div>
                    <div class="small text-muted">En attente</div>
                </div></div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center"><div class="card-body">
                    <div class="fs-3 fw-bold text-warning"><?= number_format($stats['chiffre_affaires'] ?? 0, 0) ?></div>
                    <div class="small text-muted">MAD CA</div>
                </div></div>
            </div>
        </div>

        <!-- Manager -->
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Manager</div>
            <div class="card-body">
                <?php if (!empty($agency['manager_nom'])): ?>
                    <strong><?= htmlspecialchars($agency['manager_prenom'].' '.$agency['manager_nom']) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars($agency['manager_email']) ?></small>
                <?php else: ?>
                    <span class="text-muted fst-italic">Aucun manager assigné.</span>
                    <a href="/admin/agencies/<?= $agency['id'] ?>/edit" class="btn btn-outline-warning btn-sm ms-2">Assigner</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
