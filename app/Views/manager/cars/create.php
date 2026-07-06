<?php $pageTitle = 'Ajouter une voiture'; ?>
<div class="row justify-content-center"><div class="col-lg-8">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/manager/cars">Mes voitures</a></li>
        <li class="breadcrumb-item active">Ajouter</li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header fw-bold"><i class="bi bi-car-front me-2"></i>Nouvelle voiture</div>
    <div class="card-body p-4">

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $msgs): foreach((array)$msgs as $m): ?>
                    <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
                <?php endforeach; endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/manager/cars" enctype="multipart/form-data">

            <?php if (count($agencies) > 1): ?>
            <div class="mb-3">
                <label class="form-label fw-bold">Agence <span class="text-danger">*</span></label>
                <select name="agency_id" class="form-select" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach ($agencies as $ag): ?>
                        <option value="<?= $ag['id'] ?>" <?= ($old['agency_id']??0)==$ag['id']?'selected':'' ?>>
                            <?= htmlspecialchars($ag['nom'].' — '.$ag['ville']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php elseif (!empty($agencies[0])): ?>
                <input type="hidden" name="agency_id" value="<?= $agencies[0]['id'] ?>">
                <p class="text-muted small mb-3"><i class="bi bi-building me-1"></i><?= htmlspecialchars($agencies[0]['nom'].' — '.$agencies[0]['ville']) ?></p>
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Marque <span class="text-danger">*</span></label>
                    <input type="text" name="marque" class="form-control" value="<?= htmlspecialchars($old['marque'] ?? '') ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Modèle <span class="text-danger">*</span></label>
                    <input type="text" name="modele" class="form-control" value="<?= htmlspecialchars($old['modele'] ?? '') ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Immatriculation <span class="text-danger">*</span></label>
                    <input type="text" name="immatriculation" class="form-control text-uppercase"
                           value="<?= htmlspecialchars($old['immatriculation'] ?? '') ?>"
                           placeholder="A-12345-XX" required>
                </div>
                <div class="col-3">
                    <label class="form-label fw-bold">Année <span class="text-danger">*</span></label>
                    <input type="number" name="annee" class="form-control"
                           value="<?= $old['annee'] ?? date('Y') ?>"
                           min="2000" max="<?= date('Y')+1 ?>" required>
                </div>
                <div class="col-3">
                    <label class="form-label fw-bold">Couleur</label>
                    <input type="text" name="couleur" class="form-control" value="<?= htmlspecialchars($old['couleur'] ?? '') ?>">
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Carburant</label>
                    <select name="carburant" class="form-select">
                        <?php foreach (['essence','diesel','hybride','electrique'] as $c): ?>
                            <option value="<?= $c ?>" <?= ($old['carburant']??'diesel')===$c?'selected':'' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Transmission</label>
                    <select name="transmission" class="form-select">
                        <option value="manuelle"   <?= ($old['transmission']??'manuelle')==='manuelle'?'selected':'' ?>>Manuelle</option>
                        <option value="automatique" <?= ($old['transmission']??'')==='automatique'?'selected':'' ?>>Automatique</option>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Places</label>
                    <input type="number" name="nb_places" class="form-control" value="<?= $old['nb_places'] ?? 5 ?>" min="2" max="9">
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Prix/jour (MAD) <span class="text-danger">*</span></label>
                    <input type="number" name="prix_jour" class="form-control" value="<?= $old['prix_jour'] ?? '' ?>" min="0" step="10" required>
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Caution (MAD)</label>
                    <input type="number" name="caution" class="form-control" value="<?= $old['caution'] ?? 0 ?>" min="0" step="100">
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Climatisation</label>
                    <select name="climatisation" class="form-select">
                        <option value="1" <?= ($old['climatisation']??1)?'selected':'' ?>>Oui</option>
                        <option value="0" <?= isset($old['climatisation']) && !$old['climatisation']?'selected':'' ?>>Non</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="disponible" selected>Disponible</option>
                        <option value="maintenance">En maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Photo du véhicule</label>
                    <input type="file" name="photo" id="photoInput" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">JPEG, PNG ou WebP — max 3 Mo.</div>
                    <div class="mt-2" id="photoPreviewBox" style="display:none;">
                        <img id="photoPreview" src="" alt="Aperçu" class="img-fluid rounded-3">
                    </div>
                    <div class="form-text">JPG, PNG ou WebP — max 2 Mo</div>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <a href="/manager/cars" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning fw-bold flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Ajouter la voiture
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div></div>

<script>
document.getElementById('photoInput')?.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPreview').src = e.target.result;
        document.getElementById('photoPreviewBox').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
