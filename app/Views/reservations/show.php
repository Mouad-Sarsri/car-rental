<?php
$pageTitle    = 'Réservation #'.$resa['id'];
$statutColors = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'secondary','terminee'=>'info','refusee'=>'danger'];
$statutLabels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée','refusee'=>'Refusée'];
$role         = $_SESSION['user_role'] ?? '';
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <?php if ($role === 'client'): ?>
            <li class="breadcrumb-item"><a href="/reservations">Mes réservations</a></li>
        <?php elseif ($role === 'agency_manager'): ?>
            <li class="breadcrumb-item"><a href="/manager/reservations">Réservations</a></li>
        <?php else: ?>
            <li class="breadcrumb-item"><a href="/admin/reservations">Réservations</a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active">#<?= $resa['id'] ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Détail -->
    <div class="col-lg-7">
        <!-- Voiture -->
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="bi bi-car-front me-2"></i>Véhicule</div>
            <div class="card-body d-flex gap-3 align-items-center">
                <?php if (!empty($resa['car_photo'])): ?>
                    <img src="/assets/uploads/cars/<?= htmlspecialchars($resa['car_photo']) ?>"
                         style="width:100px;height:70px;object-fit:cover;border-radius:8px;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="width:100px;height:70px;border-radius:8px;">
                        <i class="bi bi-car-front fs-2 text-secondary"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <h5 class="mb-0 fw-bold"><?= htmlspecialchars($resa['car_marque'].' '.$resa['car_modele']) ?></h5>
                    <p class="text-muted mb-0 small"><?= htmlspecialchars($resa['immatriculation']) ?></p>
                    <p class="text-muted mb-0 small"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($resa['agency_nom'].' — '.$resa['agency_ville']) ?></p>
                </div>
            </div>
        </div>

        <!-- Période -->
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="bi bi-calendar me-2"></i>Période</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 text-center">
                        <div class="text-muted small">Début</div>
                        <div class="fw-bold fs-5"><?= date('d/m/Y', strtotime($resa['date_debut'])) ?></div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="text-muted small">Fin</div>
                        <div class="fw-bold fs-5"><?= date('d/m/Y', strtotime($resa['date_fin'])) ?></div>
                    </div>
                    <div class="col-12 text-center">
                        <span class="badge bg-secondary fs-6"><?= $resa['nb_jours'] ?> jour(s)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <?php if (!empty($resa['notes_client'])): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="bi bi-chat-text me-2"></i>Notes client</div>
            <div class="card-body"><p class="mb-0"><?= nl2br(htmlspecialchars($resa['notes_client'])) ?></p></div>
        </div>
        <?php endif; ?>

        <?php if (!empty($resa['motif_annulation'])): ?>
        <div class="card shadow-sm border-danger mb-3">
            <div class="card-header fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Motif</div>
            <div class="card-body"><p class="mb-0"><?= nl2br(htmlspecialchars($resa['motif_annulation'])) ?></p></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar résumé + actions -->
    <div class="col-lg-5">
        <!-- Statut + prix -->
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center py-4">
                <div class="display-6 fw-bold text-warning mb-1"><?= number_format($resa['prix_total'], 0) ?> <small class="fs-6 fw-normal text-muted">MAD</small></div>
                <div class="text-muted small mb-3"><?= number_format($resa['prix_jour_snap'], 0) ?> MAD/jour × <?= $resa['nb_jours'] ?> jour(s)</div>
                <?php if ($resa['caution_snap'] > 0): ?>
                    <div class="text-muted small mb-2">Caution : <?= number_format($resa['caution_snap'], 0) ?> MAD</div>
                <?php endif; ?>
                <span class="badge bg-<?= $statutColors[$resa['statut']] ?? 'secondary' ?> fs-6">
                    <?= $statutLabels[$resa['statut']] ?? $resa['statut'] ?>
                </span>
                <div class="text-muted small mt-2">Créée le <?= date('d/m/Y à H:i', strtotime($resa['created_at'])) ?></div>
            </div>
        </div>

        <!-- Client (visible manager/admin) -->
        <?php if (in_array($role, ['agency_manager','super_manager'])): ?>
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="bi bi-person me-2"></i>Client</div>
            <div class="card-body">
                <strong><?= htmlspecialchars($resa['client_prenom'].' '.$resa['client_nom']) ?></strong><br>
                <small class="text-muted"><?= htmlspecialchars($resa['client_email']) ?></small><br>
                <?php if (!empty($resa['client_telephone'])): ?>
                    <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($resa['client_telephone']) ?></small>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="card shadow-sm">
            <div class="card-body d-grid gap-2">
                <?php if ($role === 'client' && in_array($resa['statut'], ['en_attente','confirmee'])): ?>
                    <form method="POST" action="/reservations/<?= $resa['id'] ?>/cancel"
                          onsubmit="return confirm('Annuler cette réservation ?')">
                        <input type="hidden" name="motif" value="Annulée par le client.">
                        <button class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-2"></i>Annuler la réservation
                        </button>
                    </form>
                <?php endif; ?>

                <?php if (in_array($role, ['agency_manager','super_manager'])): ?>
                    <?php if ($resa['statut'] === 'en_attente'): ?>
                        <form method="POST" action="/manager/reservations/<?= $resa['id'] ?>/confirm">
                            <button class="btn btn-success w-100 fw-bold">
                                <i class="bi bi-check-circle me-2"></i>Confirmer
                            </button>
                        </form>
                        <form method="POST" action="/manager/reservations/<?= $resa['id'] ?>/refuse"
                              onsubmit="return confirm('Refuser ?')">
                            <input type="hidden" name="motif" value="Refusée par l'agence.">
                            <button class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-2"></i>Refuser
                            </button>
                        </form>
                    <?php elseif ($resa['statut'] === 'confirmee'): ?>
                        <form method="POST" action="/manager/reservations/<?= $resa['id'] ?>/terminate"
                              onsubmit="return confirm('Clôturer ?')">
                            <button class="btn btn-info text-white w-100 fw-bold">
                                <i class="bi bi-flag-fill me-2"></i>Clôturer
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="javascript:history.back()" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>
</div>
