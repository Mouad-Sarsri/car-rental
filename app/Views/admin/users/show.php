<?php
$pageTitle  = htmlspecialchars($user['prenom'].' '.$user['nom']);
$roleColors = ['client'=>'info','agency_manager'=>'warning','super_manager'=>'danger'];
$roleLabels = ['client'=>'Client','agency_manager'=>'Manager agence','super_manager'=>'Super manager'];
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/users">Utilisateurs</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center">
            <div class="card-body py-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-secondary text-white rounded-circle fw-bold"
                     style="width:72px;height:72px;font-size:24px;">
                    <?= strtoupper(substr($user['prenom'],0,1).substr($user['nom'],0,1)) ?>
                </div>
                <h5 class="fw-bold mb-1"><?= $pageTitle ?></h5>
                <p class="text-muted small mb-2"><?= htmlspecialchars($user['email']) ?></p>
                <span class="badge bg-<?= $roleColors[$user['role']] ?? 'secondary' ?> mb-2">
                    <?= $roleLabels[$user['role']] ?? $user['role'] ?>
                </span><br>
                <span class="badge bg-<?= $user['actif'] ? 'success' : 'secondary' ?>">
                    <?= $user['actif'] ? 'Actif' : 'Inactif' ?>
                </span>
                <hr>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="/admin/users/<?= $user['id'] ?>/edit" class="btn btn-warning btn-sm fw-bold">
                        <i class="bi bi-pencil me-1"></i>Modifier
                    </a>
                    <form method="POST" action="/admin/users/<?= $user['id'] ?>/toggle">
                        <button class="btn btn-outline-secondary btn-sm">
                            <?= $user['actif'] ? 'Désactiver' : 'Activer' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold">Informations</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Téléphone</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($user['telephone'] ?? '—') ?></dd>
                    <dt class="col-sm-4 text-muted">Compte créé</dt>
                    <dd class="col-sm-8"><?= date('d/m/Y à H:i', strtotime($user['created_at'])) ?></dd>
                    <dt class="col-sm-4 text-muted">Dernière màj</dt>
                    <dd class="col-sm-8"><?= date('d/m/Y à H:i', strtotime($user['updated_at'])) ?></dd>
                </dl>
            </div>
        </div>

        <?php if ($agency): ?>
        <div class="card shadow-sm border-warning">
            <div class="card-header fw-bold"><i class="bi bi-building me-2 text-warning"></i>Agence gérée</div>
            <div class="card-body">
                <h6 class="fw-bold"><?= htmlspecialchars($agency['nom']) ?></h6>
                <p class="text-muted mb-1"><?= htmlspecialchars($agency['adresse']) ?></p>
                <p class="text-muted mb-0"><?= htmlspecialchars($agency['ville']) ?></p>
                <a href="/admin/agencies/<?= $agency['id'] ?>" class="btn btn-outline-warning btn-sm mt-2">
                    Voir l'agence
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
