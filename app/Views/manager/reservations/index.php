<?php
$pageTitle = 'Réservations de l\'agence';
$statutColors = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'secondary','terminee'=>'info','refusee'=>'danger'];
$statutLabels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée','refusee'=>'Refusée'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-calendar-check text-warning me-2"></i>Réservations</h2>
</div>

<!-- Filtres statut -->
<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="/manager/reservations" class="btn btn-sm <?= !$statut ? 'btn-dark' : 'btn-outline-secondary' ?>">
        Toutes <span class="badge bg-secondary ms-1"><?= $stats['total'] ?? 0 ?></span>
    </a>
    <?php foreach ($statutLabels as $s => $label): ?>
        <a href="/manager/reservations?statut=<?= $s ?>"
           class="btn btn-sm <?= $statut === $s ? 'btn-'.$statutColors[$s] : 'btn-outline-secondary' ?>">
            <?= $label ?>
            <?php if ($s === 'en_attente' && ($stats['en_attente'] ?? 0) > 0): ?>
                <span class="badge bg-dark ms-1"><?= $stats['en_attente'] ?></span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($reservations)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
        <p>Aucune réservation.</p>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Véhicule</th>
                        <th>Période</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td class="text-muted small"><?= $r['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($r['client_prenom'] . ' ' . $r['client_nom']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($r['client_telephone'] ?? '') ?></small>
                            </td>
                            <td>
                                <?= htmlspecialchars($r['car_marque'] . ' ' . $r['car_modele']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($r['immatriculation']) ?></small>
                            </td>
                            <td class="small">
                                <?= date('d/m/Y', strtotime($r['date_debut'])) ?><br>
                                → <?= date('d/m/Y', strtotime($r['date_fin'])) ?>
                                <br><span class="text-muted"><?= $r['nb_jours'] ?>j</span>
                            </td>
                            <td class="fw-bold"><?= number_format($r['prix_total'], 0) ?> MAD</td>
                            <td>
                                <span class="badge bg-<?= $statutColors[$r['statut']] ?? 'secondary' ?>">
                                    <?= $statutLabels[$r['statut']] ?? $r['statut'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="/reservations/<?= $r['id'] ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($r['statut'] === 'en_attente'): ?>
                                        <form method="POST" action="/manager/reservations/<?= $r['id'] ?>/confirm" class="d-inline">
                                            <button class="btn btn-success btn-sm" title="Confirmer">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="/manager/reservations/<?= $r['id'] ?>/refuse" class="d-inline"
                                              onsubmit="return confirm('Refuser cette réservation ?')">
                                            <input type="hidden" name="motif" value="Refusée par l'agence.">
                                            <button class="btn btn-danger btn-sm" title="Refuser">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    <?php elseif ($r['statut'] === 'confirmee'): ?>
                                        <form method="POST" action="/manager/reservations/<?= $r['id'] ?>/terminate" class="d-inline"
                                              onsubmit="return confirm('Clôturer cette réservation ?')">
                                            <button class="btn btn-info btn-sm text-white" title="Clôturer">
                                                <i class="bi bi-flag-fill"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
