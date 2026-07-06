<?php
$pageTitle    = 'Mes voitures';
$statutColors = ['disponible'=>'success','louee'=>'warning','maintenance'=>'danger','inactive'=>'secondary'];
$statutLabels = ['disponible'=>'Disponible','louee'=>'Louée','maintenance'=>'Maintenance','inactive'=>'Inactive'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-car-front text-warning me-2"></i>Mes voitures</h2>
    <a href="/manager/cars/create" class="btn btn-warning fw-bold">
        <i class="bi bi-plus me-1"></i>Ajouter
    </a>
</div>

<?php if (empty($cars)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-car-front display-4 d-block mb-3"></i>
        <p>Aucune voiture dans votre agence.</p>
        <a href="/manager/cars/create" class="btn btn-warning fw-bold">Ajouter la première</a>
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr><th>Voiture</th><th>Immat.</th><th>Carburant</th><th>Places</th><th>Prix/jour</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $c): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($c['marque'].' '.$c['modele']) ?></strong>
                            <br><small class="text-muted"><?= $c['annee'] ?> — <?= htmlspecialchars($c['couleur'] ?? '') ?></small>
                        </td>
                        <td><code><?= htmlspecialchars($c['immatriculation']) ?></code></td>
                        <td><?= ucfirst($c['carburant']) ?></td>
                        <td><?= $c['nb_places'] ?></td>
                        <td class="fw-bold text-warning"><?= number_format($c['prix_jour'], 0) ?> MAD</td>
                        <td>
                            <span class="badge bg-<?= $statutColors[$c['statut']] ?? 'secondary' ?>">
                                <?= $statutLabels[$c['statut']] ?? $c['statut'] ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="/cars/<?= $c['id'] ?>" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="bi bi-eye"></i></a>
                                <a href="/manager/cars/<?= $c['id'] ?>/edit" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>

                                <!-- Changer statut -->
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class="bi bi-sliders"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($statutLabels as $s => $l): if ($s === $c['statut']) continue; ?>
                                        <li>
                                            <form method="POST" action="/manager/cars/<?= $c['id'] ?>/statut">
                                                <input type="hidden" name="statut" value="<?= $s ?>">
                                                <button class="dropdown-item"><?= $l ?></button>
                                            </form>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <form method="POST" action="/manager/cars/<?= $c['id'] ?>/delete" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette voiture ?')">
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
