<?php $pageTitle = 'Véhicules disponibles'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-car-front me-2 text-warning"></i>Nos véhicules</h2>
    <span class="badge bg-secondary fs-6"><?= count($cars) ?> véhicule(s)</span>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="/cars" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-bold">Ville</label>
                <select name="ville" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?= $v['ville'] ?>" <?= ($filters['ville'] ?? '') === $v['ville'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['ville']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Marque</label>
                <select name="marque" class="form-select form-select-sm">
                    <option value="">Toutes</option>
                    <?php foreach ($marques as $m): ?>
                        <option value="<?= $m['marque'] ?>" <?= ($filters['marque'] ?? '') === $m['marque'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m['marque']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Carburant</label>
                <select name="carburant" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <?php foreach (['essence','diesel','hybride','electrique'] as $c): ?>
                        <option value="<?= $c ?>" <?= ($filters['carburant'] ?? '') === $c ? 'selected' : '' ?>>
                            <?= ucfirst($c) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Date début</label>
                <input type="date" name="date_debut" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($filters['date_debut'] ?? '') ?>"
                       min="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Date fin</label>
                <input type="date" name="date_fin" class="form-control form-control-sm"
                       value="<?= htmlspecialchars($filters['date_fin'] ?? '') ?>"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-warning btn-sm fw-bold flex-grow-1">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                <a href="/cars" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Grille des voitures -->
<?php if (empty($cars)): ?>
    <div class="text-center py-5 text-muted">
        <i class="bi bi-car-front display-4 d-block mb-3"></i>
        <p>Aucun véhicule disponible pour ces critères.</p>
        <a href="/cars" class="btn btn-outline-warning">Réinitialiser les filtres</a>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($cars as $car): ?>
            <div class="col">
                <div class="card h-100 shadow-sm car-card">
                    <div class="car-img-wrapper bg-light">
                        <?php if (!empty($car['photo'])): ?>
                            <img src="/assets/uploads/cars/<?= htmlspecialchars($car['photo']) ?>"
                                 class="car-img" alt="<?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?>">
                        <?php else: ?>
                            <div class="car-img-placeholder">
                                <i class="bi bi-car-front display-4 text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h5 class="card-title mb-0 fw-bold">
                                <?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?>
                            </h5>
                            <span class="badge bg-warning text-dark"><?= $car['annee'] ?></span>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt me-1"></i>
                            <?= htmlspecialchars($car['agency_nom']) ?> — <?= htmlspecialchars($car['agency_ville']) ?>
                        </p>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-fuel-pump me-1"></i><?= ucfirst($car['carburant']) ?>
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-gear me-1"></i><?= ucfirst($car['transmission']) ?>
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-people me-1"></i><?= $car['nb_places'] ?> places
                            </span>
                            <?php if ($car['climatisation']): ?>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-snow me-1"></i>Clim
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4 fw-bold text-warning"><?= number_format($car['prix_jour'], 0) ?></span>
                                <span class="text-muted small"> MAD/jour</span>
                            </div>
                            <a href="/cars/<?= $car['id'] ?>" class="btn btn-outline-warning btn-sm fw-bold">
                                Voir <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
