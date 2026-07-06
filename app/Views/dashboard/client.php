<?php $pageTitle = 'Mon espace';
$user = $_SESSION['user'];
$statutColors = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'secondary','terminee'=>'info','refusee'=>'danger'];
$statutLabels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée','refusee'=>'Refusée'];
?>

<h2 class="fw-bold mb-1">Bonjour, <?= htmlspecialchars($user['prenom']) ?> 👋</h2>
<p class="text-muted mb-4">Bienvenue dans votre espace client.</p>

<!-- Réservations actives -->
<?php if (!empty($actives)): ?>
    <div class="card border-warning shadow-sm mb-4">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="bi bi-clock me-2"></i>Réservations en cours (<?= count($actives) ?>)
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($actives as $r): ?>
                <a href="/reservations/<?= $r['id'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($r['car_marque'] . ' ' . $r['car_modele']) ?></strong>
                        <br><small class="text-muted">
                            <?= date('d/m/Y', strtotime($r['date_debut'])) ?> → <?= date('d/m/Y', strtotime($r['date_fin'])) ?>
                            — <?= htmlspecialchars($r['agency_nom']) ?>
                        </small>
                    </div>
                    <span class="badge bg-<?= $statutColors[$r['statut']] ?>">
                        <?= $statutLabels[$r['statut']] ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- CTA -->
<div class="row g-3">
    <div class="col-md-6">
        <a href="/cars" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center py-4">
                <i class="bi bi-car-front display-5 text-warning"></i>
                <p class="fw-bold mt-2 mb-0">Trouver une voiture</p>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="/reservations" class="card shadow-sm text-decoration-none text-dark h-100">
            <div class="card-body text-center py-4">
                <i class="bi bi-calendar-check display-5 text-info"></i>
                <p class="fw-bold mt-2 mb-0">Mes réservations (<?= count($reservations) ?>)</p>
            </div>
        </a>
    </div>
</div>
