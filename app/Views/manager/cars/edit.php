<?php
$pageTitle    = 'Modifier — '.htmlspecialchars($car['marque'].' '.$car['modele']);
$statutLabels = ['disponible'=>'Disponible','louee'=>'Louée','maintenance'=>'Maintenance','inactive'=>'Inactive'];
?>
<div class="row justify-content-center"><div class="col-lg-8">

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/manager/cars">Mes voitures</a></li>
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
    <div class="card-header fw-bold"><i class="bi bi-pencil me-2"></i><?= $pageTitle ?></div>
    <div class="card-body p-4">
        <form method="POST" action="/manager/cars/<?= $car['id'] ?>" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Marque</label>
                    <input type="text" name="marque" class="form-control" value="<?= htmlspecialchars($car['marque']) ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Modèle</label>
                    <input type="text" name="modele" class="form-control" value="<?= htmlspecialchars($car['modele']) ?>" required>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Immatriculation</label>
                    <input type="text" name="immatriculation" class="form-control text-uppercase" value="<?= htmlspecialchars($car['immatriculation']) ?>" required>
                </div>
                <div class="col-3">
                    <label class="form-label fw-bold">Année</label>
                    <input type="number" name="annee" class="form-control" value="<?= $car['annee'] ?>" min="2000" max="<?= date('Y')+1 ?>">
                </div>
                <div class="col-3">
                    <label class="form-label fw-bold">Couleur</label>
                    <input type="text" name="couleur" class="form-control" value="<?= htmlspecialchars($car['couleur'] ?? '') ?>">
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Carburant</label>
                    <select name="carburant" class="form-select">
                        <?php foreach (['essence','diesel','hybride','electrique'] as $c): ?>
                            <option value="<?= $c ?>" <?= $car['carburant']===$c?'selected':'' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Transmission</label>
                    <select name="transmission" class="form-select">
                        <option value="manuelle"    <?= $car['transmission']==='manuelle'?'selected':'' ?>>Manuelle</option>
                        <option value="automatique" <?= $car['transmission']==='automatique'?'selected':'' ?>>Automatique</option>
                    </select>
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Places</label>
                    <input type="number" name="nb_places" class="form-control" value="<?= $car['nb_places'] ?>" min="2" max="9">
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Prix/jour (MAD)</label>
                    <input type="number" name="prix_jour" class="form-control" value="<?= $car['prix_jour'] ?>" min="0" step="10" required>
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Caution (MAD)</label>
                    <input type="number" name="caution" class="form-control" value="<?= $car['caution'] ?>" min="0" step="100">
                </div>
                <div class="col-4">
                    <label class="form-label fw-bold">Climatisation</label>
                    <select name="climatisation" class="form-select">
                        <option value="1" <?= $car['climatisation']?'selected':'' ?>>Oui</option>
                        <option value="0" <?= !$car['climatisation']?'selected':'' ?>>Non</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="statut" class="form-select">
                        <?php foreach ($statutLabels as $s => $l): ?>
                            <option value="<?= $s ?>" <?= $car['statut']===$s?'selected':'' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Photo du véhicule</label>
                    <?php if (!empty($car['photo'])): ?>
                        <div class="mb-2">
                            <img src="/assets/uploads/cars/<?= htmlspecialchars($car['photo']) ?>"
                                 class="img-fluid rounded-3" style="max-height:140px;object-fit:cover;"
                                 id="photoPreview" alt="Photo actuelle">
                        </div>
                    <?php else: ?>
                        <div class="mb-2" id="photoPreviewBox" style="display:none;">
                            <img id="photoPreview" src="" alt="Aperçu" class="img-fluid rounded-3" style="max-height:140px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" id="photoInput" class="form-control" accept="image/jpeg,image/png,image/webp">
                    <div class="form-text">Laissez vide pour conserver la photo actuelle. Max 3 Mo.</div>
                    
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($car['description'] ?? '') ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <a href="/manager/cars" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning fw-bold flex-grow-1">
                        <i class="bi bi-check-circle me-2"></i>Enregistrer
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
        const img = document.getElementById('photoPreview');
        const box = document.getElementById('photoPreviewBox');
        img.src = e.target.result;
        if (box) box.style.display = 'block';
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
