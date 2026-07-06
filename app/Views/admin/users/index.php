<?php
$pageTitle   = 'Gestion des utilisateurs';
$roleColors  = ['client'=>'info','agency_manager'=>'warning','super_manager'=>'danger'];
$roleLabels  = ['client'=>'Client','agency_manager'=>'Manager agence','super_manager'=>'Super manager'];
$countByRole = array_column($counts, 'total', 'role');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-people text-warning me-2"></i>Utilisateurs</h2>
    <a href="/admin/users/create" class="btn btn-warning fw-bold">
        <i class="bi bi-person-plus me-1"></i>Ajouter
    </a>
</div>

<!-- Filtres par rôle -->
<div class="d-flex gap-2 flex-wrap mb-3">
    <a href="/admin/users" class="btn btn-sm <?= !$role ? 'btn-dark' : 'btn-outline-secondary' ?>">
        Tous <span class="badge bg-secondary ms-1"><?= array_sum(array_column($counts, 'total')) ?></span>
    </a>
    <?php foreach ($roleLabels as $r => $label): ?>
        <a href="/admin/users?role=<?= $r ?>"
           class="btn btn-sm <?= $role === $r ? 'btn-'.$roleColors[$r] : 'btn-outline-secondary' ?>">
            <?= $label ?>
            <span class="badge bg-dark ms-1"><?= $countByRole[$r] ?? 0 ?></span>
        </a>
    <?php endforeach; ?>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th><th>Nom</th><th>Email</th><th>Téléphone</th>
                    <th>Rôle</th><th>Statut</th><th>Créé le</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-muted">Aucun utilisateur trouvé.</td></tr>
                <?php endif; ?>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td class="text-muted small"><?= $u['id'] ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm"><?= strtoupper(substr($u['prenom'],0,1).substr($u['nom'],0,1)) ?></div>
                            <strong><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></strong>
                        </div>
                    </td>
                    <td class="small"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="small"><?= htmlspecialchars($u['telephone'] ?? '—') ?></td>
                    <td><span class="badge bg-<?= $roleColors[$u['role']] ?? 'secondary' ?>"><?= $roleLabels[$u['role']] ?? $u['role'] ?></span></td>
                    <td><span class="badge bg-<?= $u['actif'] ? 'success' : 'secondary' ?>"><?= $u['actif'] ? 'Actif' : 'Inactif' ?></span></td>
                    <td class="small text-muted"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="/admin/users/<?= $u['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a>
                            <a href="/admin/users/<?= $u['id'] ?>/edit" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="/admin/users/<?= $u['id'] ?>/toggle" class="d-inline">
                                <button class="btn btn-outline-<?= $u['actif'] ? 'secondary' : 'success' ?> btn-sm">
                                    <i class="bi bi-<?= $u['actif'] ? 'pause' : 'play' ?>-fill"></i>
                                </button>
                            </form>
                            <form method="POST" action="/admin/users/<?= $u['id'] ?>/delete" class="d-inline"
                                  onsubmit="return confirm('Supprimer cet utilisateur ?')">
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
