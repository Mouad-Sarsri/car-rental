<?php $pageTitle = 'Mes réservations';
$statutColors = [
    'en_attente' => 'warning',
    'confirmee'  => 'success',
    'annulee'    => 'secondary',
    'terminee'   => 'info',
    'refusee'    => 'danger',
];
$statutLabels = [
    'en_attente' => 'En attente',
    'confirmee'  => 'Confirmée',
    'annulee'    => 'Annulée',
    'terminee'   => 'Terminée',
    'refusee'    => 'Refusée',
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-calendar-check text-warning me-2"></i>Mes réservations</h2>
    <a href="/cars" class="btn btn-warning btn-sm fw-bold">
        <i class="bi bi-plus me-1"></i>Nouvelle réservation
    </a>
</div>

<?php if (empty($reservations)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
        <p>Vous n'avez aucune réservation pour le moment.</p>
        <a href="/cars" class="btn btn-warning fw-bold">Parcourir les véhicules</a>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Véhicule</th>
                        <th>Agence</th>
                        <th>Période</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td class="text-muted small"><?= $r['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($r['car_marque'] . ' ' . $r['car_modele']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($r['immatriculation']) ?></small>
                            </td>
                            <td class="small"><?= htmlspecialchars($r['agency_nom']) ?><br>
                                <span class="text-muted"><?= htmlspecialchars($r['agency_ville']) ?></span>
                            </td>
                            <td class="small">
                                <?= date('d/m/Y', strtotime($r['date_debut'])) ?><br>
                                → <?= date('d/m/Y', strtotime($r['date_fin'])) ?><br>
                                <span class="text-muted"><?= $r['nb_jours'] ?> jour(s)</span>
                            </td>
                            <td class="fw-bold text-warning"><?= number_format($r['prix_total'], 0) ?> MAD</td>
                            <td>
                                <span class="badge bg-<?= $statutColors[$r['statut']] ?? 'secondary' ?>">
                                    <?= $statutLabels[$r['statut']] ?? $r['statut'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="/reservations/<?= $r['id'] ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (in_array($r['statut'], ['en_attente', 'confirmee'])): ?>
                                    <form method="POST" action="/reservations/<?= $r['id'] ?>/cancel"
                                          class="d-inline" onsubmit="return confirm('Annuler cette réservation ?')">
                                        <input type="hidden" name="motif" value="Annulée par le client.">
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
