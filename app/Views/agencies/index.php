<?php $pageTitle = 'Gestion des agences'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-building text-warning me-2"></i>Agences</h2>
    <a href="/admin/agencies/create" class="btn btn-warning fw-bold">
        <i class="bi bi-plus me-1"></i>Nouvelle agence
    </a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Agence</th>
                    <th>Ville</th>
                    <th>Manager</th>
                    <th>Voitures</th>
                    <th>Contact</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agencies as $a): ?>
                    <tr class="<?= !$a['actif'] ? 'table-secondary text-muted' : '' ?>">
                        <td>
                            <strong><?= htmlspecialchars($a['nom']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($a['adresse']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($a['ville']) ?></td>
                        <td>
                            <?php if ($a['manager_nom']): ?>
                                <?= htmlspecialchars($a['manager_prenom'].' '.$a['manager_nom']) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic small">Non assigné</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-success me-1"><?= $a['cars_disponibles'] ?> dispo</span>
                            <?php if ($a['cars_louees'] > 0): ?>
                                <span class="badge bg-warning text-dark"><?= $a['cars_louees'] ?> louées</span>
                            <?php endif; ?>
                            <?php if ($a['cars_maintenance'] > 0): ?>
                                <span class="badge bg-secondary"><?= $a['cars_maintenance'] ?> maint.</span>
                            <?php endif; ?>
                            <div class="small text-muted"><?= $a['total_cars'] ?> total</div>
                        </td>
                        <td class="small text-muted">
                            <?php if ($a['telephone']): ?>
                                <div><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($a['telephone']) ?></div>
                            <?php endif; ?>
                            <?php if ($a['email']): ?>
                                <div><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($a['email']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $a['actif'] ? 'success':'secondary' ?>">
                                <?= $a['actif'] ? 'Active':'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="/admin/agencies/<?= $a['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="/admin/agencies/<?= $a['id'] ?>/edit" class="btn btn-outline-secondary btn-sm" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="/admin/agencies/<?= $a['id'] ?>/toggle" class="d-inline">
                                    <button class="btn btn-sm btn-outline-<?= $a['actif'] ? 'warning':'success' ?>" title="<?= $a['actif'] ? 'Désactiver':'Activer' ?>">
                                        <i class="bi bi-<?= $a['actif'] ? 'pause-circle':'play-circle' ?>"></i>
                                    </button>
                                </form>
                                <form method="POST" action="/admin/agencies/<?= $a['id'] ?>/delete" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette agence et toutes ses voitures ?')">
                                    <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
