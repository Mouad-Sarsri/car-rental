<?php $pageTitle = 'Mon agence — '.htmlspecialchars($agency['nom']); ?>
<h2 class="fw-bold mb-4"><i class="bi bi-building text-warning me-2"></i>Mon agence</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $msgs): foreach((array)$msgs as $m): ?>
            <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
        <?php endforeach; endforeach; ?>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header fw-bold"><i class="bi bi-pencil me-2"></i>Modifier les informations</div>
            <div class="card-body p-4">
                <form method="POST" action="/manager/agency">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Nom de l'agence</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($agency['nom']) ?>" disabled>
                            <div class="form-text">Le nom est géré par l'administrateur.</div>
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
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($agency['description'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="bi bi-check-circle me-2"></i>Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center py-4">
                <i class="bi bi-building display-4 text-warning"></i>
                <h5 class="fw-bold mt-2"><?= htmlspecialchars($agency['nom']) ?></h5>
                <p class="text-muted small"><?= htmlspecialchars($agency['ville']) ?></p>
                <span class="badge bg-<?= $agency['actif'] ? 'success' : 'secondary' ?>">
                    <?= $agency['actif'] ? 'Active' : 'Inactive' ?>
                </span>
                <hr>
                <a href="/manager/dashboard" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-arrow-left me-1"></i>Tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>
