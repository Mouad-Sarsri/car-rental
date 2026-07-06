<?php
$pageTitle    = 'Toutes les réservations';
$statutColors = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'secondary','terminee'=>'info','refusee'=>'danger'];
$statutLabels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée','refusee'=>'Refusée'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-calendar-check text-warning me-2"></i>Toutes les réservations</h2>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <?php
    $kpis = [
        ['label'=>'Total','val'=>$stats['total']??0,'color'=>'secondary'],
        ['label'=>'En attente','val'=>$stats['en_attente']??0,'color'=>'warning'],
        ['label'=>'Confirmées','val'=>$stats['confirmees']??0,'color'=>'success'],
        ['label'=>'CA total','val'=>number_format($stats['chiffre_affaires']??0,0).' MAD','color'=>'warning'],
    ];
    foreach ($kpis as $k): ?>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="fs-3 fw-bold text-<?= $k['color'] ?>"><?= $k['val'] ?></div>
                <div class="small text-muted"><?= $k['label'] ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr><th>#</th><th>Client</th><th>Véhicule</th><th>Agence</th><th>Période</th><th>Total</th><th>Statut</th><th></th></tr>
            </thead>
            <tbody>
                <?php if (empty($reservations)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">Aucune réservation.</td></tr>
                <?php endif; ?>
                <?php foreach ($reservations as $r): ?>
                <tr>
                    <td class="text-muted small"><?= $r['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['client_prenom'].' '.$r['client_nom']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($r['client_email']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($r['car_marque'].' '.$r['car_modele']) ?></td>
                    <td><?= htmlspecialchars($r['agency_nom']) ?></td>
                    <td class="small">
                        <?= date('d/m/Y', strtotime($r['date_debut'])) ?><br>
                        → <?= date('d/m/Y', strtotime($r['date_fin'])) ?>
                    </td>
                    <td class="fw-bold"><?= number_format($r['prix_total'], 0) ?> MAD</td>
                    <td><span class="badge bg-<?= $statutColors[$r['statut']] ?? 'secondary' ?>"><?= $statutLabels[$r['statut']] ?? $r['statut'] ?></span></td>
                    <td>
                        <a href="/admin/reservations/<?= $r['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
