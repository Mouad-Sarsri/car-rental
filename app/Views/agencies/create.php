<?php $pageTitle = 'Nouvelle agence'; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/admin/agencies" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
    <h2 class="fw-bold mb-0"><i class="bi bi-building-add text-warning me-2"></i>Nouvelle agence</h2>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $msgs): foreach ((array)$msgs as $m): ?>
                            <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
                        <?php endforeach; endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/admin/agencies">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Nom de l'agence <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control"
                                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Adresse <span class="text-danger">*</span></label>
                            <input type="text" name="adresse" class="form-control"
                                   value="<?= htmlspecialchars($old['adresse'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ville <span class="text-danger">*</span></label>
                            <input type="text" name="ville" class="form-control"
                                   value="<?= htmlspecialchars($old['ville'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Code postal</label>
                            <input type="text" name="code_postal" class="form-control"
                                   value="<?= htmlspecialchars($old['code_postal'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Pays</label>
                            <input type="text" name="pays" class="form-control"
                                   value="<?= htmlspecialchars($old['pays'] ?? 'Maroc') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control"
                                   value="<?= htmlspecialchars($old['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Manager</label>
                            <select name="manager_id" class="form-select">
                                <option value="">— Aucun manager —</option>
                                <?php foreach ($managers as $m): ?>
                                    <option value="<?= $m['id'] ?>"
                                        <?= ($old['manager_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($m['prenom'].' '.$m['nom']) ?>
                                        (<?= htmlspecialchars($m['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">Seuls les managers sans agence sont affichés.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12 d-flex gap-2 justify-content-end">
                            <a href="/admin/agencies" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="bi bi-check me-1"></i>Créer l'agence
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
