<?php $pageTitle = 'Gestion des agences'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-building text-warning me-2"></i>Agences</h2>
    <a href="/admin/agencies/create" class="btn btn-warning fw-bold"><i class="bi bi-plus me-1"></i>Nouvelle agence</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr><th>#</th><th>Nom</th><th>Ville</th><th>Manager</th><th>Voitures</th><th>Disponibles</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($agencies)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">Aucune agence.</td></tr>
                <?php endif; ?>
                <?php foreach ($agencies as $a): ?>
                <tr>
                    <td class="text-muted small"><?= $a['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($a['nom']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($a['adresse']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($a['ville']) ?></td>
                    <td>
                        <?= !empty($a['manager_nom']) ? htmlspecialchars($a['manager_prenom'].' '.$a['manager_nom']) : '<span class="text-muted fst-italic small">Non assigné</span>' ?>
                    </td>
                    <td><span class="badge bg-secondary"><?= $a['total_cars'] ?></span></td>
                    <td>
                        <span class="badge bg-success"><?= $a['cars_disponibles'] ?></span>
                        <?php if ($a['cars_louees'] > 0): ?><span class="badge bg-warning text-dark"><?= $a['cars_louees'] ?>L</span><?php endif; ?>
                        <?php if ($a['cars_maintenance'] > 0): ?><span class="badge bg-danger"><?= $a['cars_maintenance'] ?>M</span><?php endif; ?>
                    </td>
                    <td><span class="badge bg-<?= $a['actif'] ? 'success' : 'secondary' ?>"><?= $a['actif'] ? 'Active' : 'Inactive' ?></span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="/admin/agencies/<?= $a['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a>
                            <a href="/admin/agencies/<?= $a['id'] ?>/edit" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/agencies/<?= $a['id'] ?>/toggle" class="d-inline">
                                <button class="btn btn-sm btn-outline-<?= $a['actif'] ? 'secondary' : 'success' ?>"><i class="bi bi-<?= $a['actif'] ? 'pause' : 'play' ?>-fill"></i></button>
                            </form>
                            <form method="POST" action="/admin/agencies/<?= $a['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Supprimer ?')">
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
