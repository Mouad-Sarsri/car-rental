<?php $pageTitle = 'Modifier — '.htmlspecialchars($agency['nom']); ?>

<div class="row justify-content-center"><div class="col-lg-7">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/agencies">Agences</a></li>
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

<div class="card shadow-sm">
    <div class="card-header fw-bold"><i class="bi bi-pencil me-2"></i>Modifier l'agence</div>
    <div class="card-body p-4">
        <form method="POST" action="/admin/agencies/<?= $agency['id'] ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($agency['nom']) ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($agency['adresse']) ?>">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Ville</label>
                    <input type="text" name="ville" class="form-control" value="<?= htmlspecialchars($agency['ville']) ?>">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Code postal</label>
                    <input type="text" name="code_postal" class="form-control" value="<?= htmlspecialchars($agency['code_postal'] ?? '') ?>">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Téléphone</label>
                    <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($agency['telephone'] ?? '') ?>">
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($agency['email'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Manager</label>
                    <select name="manager_id" class="form-select">
                        <option value="">— Aucun —</option>
                        <?php foreach ($managers as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $agency['manager_id'] == $m['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['prenom'].' '.$m['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($agency['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <a href="/admin/agencies" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning fw-bold flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div></div>
