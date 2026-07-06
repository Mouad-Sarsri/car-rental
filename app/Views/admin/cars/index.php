<?php
$pageTitle    = 'Toutes les voitures';
$statutColors = ['disponible'=>'success','louee'=>'warning','maintenance'=>'danger','inactive'=>'secondary'];
$statutLabels = ['disponible'=>'Disponible','louee'=>'Louée','maintenance'=>'Maintenance','inactive'=>'Inactive'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-car-front text-warning me-2"></i>Toutes les voitures</h2>
    <span class="badge bg-secondary fs-6"><?= count($cars) ?> voiture(s)</span>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr><th>Voiture</th><th>Immat.</th><th>Agence</th><th>Carburant</th><th>Prix/jour</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($cars)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-muted">Aucune voiture.</td></tr>
                <?php endif; ?>
                <?php foreach ($cars as $c): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($c['marque'].' '.$c['modele']) ?></strong>
                        <br><small class="text-muted"><?= $c['annee'] ?></small>
                    </td>
                    <td><code><?= htmlspecialchars($c['immatriculation']) ?></code></td>
                    <td>
                        <?= htmlspecialchars($c['agency_nom']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($c['agency_ville']) ?></small>
                    </td>
                    <td><?= ucfirst($c['carburant']) ?></td>
                    <td class="fw-bold"><?= number_format($c['prix_jour'], 0) ?> MAD</td>
                    <td><span class="badge bg-<?= $statutColors[$c['statut']] ?? 'secondary' ?>"><?= $statutLabels[$c['statut']] ?? $c['statut'] ?></span></td>
                    <td>
                        <a href="/cars/<?= $c['id'] ?>" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-eye"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
