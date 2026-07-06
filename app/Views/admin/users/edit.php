<?php
$pageTitle  = 'Modifier — '.htmlspecialchars($user['prenom'].' '.$user['nom']);
$roleLabels = ['client'=>'Client','agency_manager'=>'Manager agence','super_manager'=>'Super manager'];
?>

<div class="row justify-content-center">
<div class="col-lg-6">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/users">Utilisateurs</a></li>
        <li class="breadcrumb-item active">Modifier</li>
    </ol>
</nav>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $msgs): foreach((array)$msgs as $m): ?>
            <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
        <?php endforeach; endforeach; ?>
    </div>
<?php endif; ?>

<!-- Infos principales -->
<div class="card shadow-sm mb-3">
    <div class="card-header fw-bold"><i class="bi bi-pencil me-2"></i>Informations</div>
    <div class="card-body p-4">
        <form method="POST" action="/admin/users/<?= $user['id'] ?>">
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Téléphone</label>
                    <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Rôle</label>
                    <select name="role" class="form-select">
                        <?php foreach ($roleLabels as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $user['role']===$val?'selected':'' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="actif" class="form-select">
                        <option value="1" <?= $user['actif']?'selected':'' ?>>Actif</option>
                        <option value="0" <?= !$user['actif']?'selected':'' ?>>Inactif</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <a href="/admin/users" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning fw-bold flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reset mot de passe -->
<div class="card shadow-sm">
    <div class="card-header fw-bold text-danger"><i class="bi bi-key me-2"></i>Réinitialiser le mot de passe</div>
    <div class="card-body p-4">
        <form method="POST" action="/admin/users/<?= $user['id'] ?>/password"
              onsubmit="return confirm('Réinitialiser le mot de passe ?')">
            <div class="input-group">
                <input type="password" name="new_password" class="form-control"
                       placeholder="Nouveau mot de passe (min. 8 caractères)" minlength="8" required>
                <button type="submit" class="btn btn-danger fw-bold">Réinitialiser</button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
