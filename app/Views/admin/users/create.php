<?php $pageTitle = 'Nouvel utilisateur'; ?>

<div class="row justify-content-center">
<div class="col-lg-6">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/users">Utilisateurs</a></li>
        <li class="breadcrumb-item active">Nouveau</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header fw-bold"><i class="bi bi-person-plus me-2"></i>Créer un utilisateur</div>
    <div class="card-body p-4">

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $msgs): foreach((array)$msgs as $m): ?>
                    <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
                <?php endforeach; endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/admin/users">
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Téléphone</label>
                    <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>" placeholder="+212 6xx xxx xxx">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Rôle <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="client"          <?= ($old['role']??'client')==='client'?'selected':'' ?>>Client</option>
                        <option value="agency_manager"  <?= ($old['role']??'')==='agency_manager'?'selected':'' ?>>Manager agence</option>
                        <option value="super_manager"   <?= ($old['role']??'')==='super_manager'?'selected':'' ?>>Super manager</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" minlength="8" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="actif" class="form-select">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <a href="/admin/users" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning fw-bold flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Créer l'utilisateur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
