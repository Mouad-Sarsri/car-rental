<?php $pageTitle = htmlspecialchars($agency['nom']); ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/admin/agencies" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h2 class="fw-bold mb-0"><i class="bi bi-building text-warning me-2"></i><?= $pageTitle ?></h2>
    <a href="/admin/agencies/<?= $agency['id'] ?>/edit" class="btn btn-outline-warning btn-sm ms-auto">
        <i class="bi bi-pencil me-1"></i>Modifier
    </a>
</div>

<div class="row g-4">
    <!-- Infos agence -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Informations</h6>
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted">Adresse</dt>
                    <dd class="col-7"><?= htmlspecialchars($agency['adresse']) ?></dd>
                    <dt class="col-5 text-muted">Ville</dt>
                    <dd class="col-7"><?= htmlspecialchars($agency['ville']) ?></dd>
                    <dt class="col-5 text-muted">Code postal</dt>
                    <dd class="col-7"><?= htmlspecialchars($agency['code_postal'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted">Téléphone</dt>
                    <dd class="col-7"><?= htmlspecialchars($agency['telephone'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted">Email</dt>
                    <dd class="col-7"><?= htmlspecialchars($agency['email'] ?? '—') ?></dd>
                    <dt class="col-5 text-muted">Statut</dt>
                    <dd class="col-7">
                        <span class="badge bg-<?= $agency['actif'] ? 'success':'secondary' ?>">
                            <?= $agency['actif'] ? 'Active':'Inactive' ?>
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Manager -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-person-badge me-2"></i>Manager</h6>
                <?php if ($agency['manager_nom']): ?>
                    <p class="mb-0 fw-bold"><?= htmlspecialchars($agency['manager_prenom'].' '.$agency['manager_nom']) ?></p>
                    <p class="mb-0 text-muted small"><?= htmlspecialchars($agency['manager_email']) ?></p>
                <?php else: ?>
                    <p class="text-muted fst-italic small mb-0">Aucun manager assigné.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="col-md-8">
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-warning"><?= number_format($stats['chiffre_affaires'] ?? 0, 0) ?></div>
                        <div class="small text-muted">MAD de CA</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-info"><?= $stats['total_reservations'] ?? 0 ?></div>
                        <div class="small text-muted">Réservations</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-warning"><?= $stats['resa_en_attente'] ?? 0 ?></div>
                        <div class="small text-muted">En attente</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-success"><?= $stats['total_voitures'] ?? 0 ?></div>
                        <div class="small text-muted">Voitures</div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($agency['description'])): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Description</h6>
                    <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($agency['description'])) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="d-flex gap-2 mt-3">
            <a href="/admin/cars?agency_id=<?= $agency['id'] ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-car-front me-1"></i>Voir les voitures
            </a>
            <a href="/admin/reservations?agency_id=<?= $agency['id'] ?>" class="btn btn-outline-info btn-sm">
                <i class="bi bi-calendar-check me-1"></i>Voir les réservations
            </a>
            <form method="POST" action="/admin/agencies/<?= $agency['id'] ?>/toggle" class="d-inline ms-auto">
                <button class="btn btn-sm btn-outline-<?= $agency['actif'] ? 'warning':'success' ?>">
                    <?= $agency['actif'] ? 'Désactiver':'Activer' ?> l'agence
                </button>
            </form>
        </div>
    </div>
</div>
