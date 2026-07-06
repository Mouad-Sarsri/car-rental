<?php $pageTitle = htmlspecialchars($car['marque'] . ' ' . $car['modele']); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/cars">Véhicules</a></li>
        <li class="breadcrumb-item active"><?= $pageTitle ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Photo + badges -->
    <div class="col-lg-7">
        <div class="card shadow-sm overflow-hidden">
            <?php if (!empty($car['photo'])): ?>
                <img src="/assets/uploads/cars/<?= htmlspecialchars($car['photo']) ?>"
                     class="img-fluid" alt="<?= $pageTitle ?>">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:320px;">
                    <i class="bi bi-car-front display-1 text-secondary"></i>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($car['description'])): ?>
            <div class="card mt-3 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Description</h6>
                    <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Infos + formulaire réservation -->
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="fw-bold"><?= $pageTitle ?></h2>
                <p class="text-muted mb-2">
                    <i class="bi bi-geo-alt me-1"></i>
                    <?= htmlspecialchars($car['agency_nom']) ?> — <?= htmlspecialchars($car['agency_ville']) ?>
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-secondary"><i class="bi bi-calendar me-1"></i><?= $car['annee'] ?></span>
                    <span class="badge bg-secondary"><i class="bi bi-fuel-pump me-1"></i><?= ucfirst($car['carburant']) ?></span>
                    <span class="badge bg-secondary"><i class="bi bi-gear me-1"></i><?= ucfirst($car['transmission']) ?></span>
                    <span class="badge bg-secondary"><i class="bi bi-people me-1"></i><?= $car['nb_places'] ?> places</span>
                    <?php if (!empty($car['couleur'])): ?>
                        <span class="badge bg-secondary"><i class="bi bi-circle-fill me-1"></i><?= htmlspecialchars($car['couleur']) ?></span>
                    <?php endif; ?>
                    <?php if ($car['climatisation']): ?>
                        <span class="badge bg-info"><i class="bi bi-snow me-1"></i>Climatisation</span>
                    <?php endif; ?>
                </div>

                <div class="bg-warning bg-opacity-10 rounded p-3 mb-4">
                    <div class="fs-3 fw-bold text-warning">
                        <?= number_format($car['prix_jour'], 0) ?> <small class="fs-6 fw-normal text-muted">MAD / jour</small>
                    </div>
                    <?php if ($car['caution'] > 0): ?>
                        <div class="small text-muted">Caution : <?= number_format($car['caution'], 0) ?> MAD</div>
                    <?php endif; ?>
                </div>

                <?php if ($car['statut'] === 'disponible'): ?>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'client'): ?>
                        <form method="GET" action="/reservations/new">
                            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Début</label>
                                    <input type="date" name="date_debut" class="form-control"
                                           min="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold">Fin</label>
                                    <input type="date" name="date_fin" class="form-control"
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning fw-bold w-100">
                                <i class="bi bi-calendar-check me-2"></i>Réserver ce véhicule
                            </button>
                        </form>
                    <?php elseif (!isset($_SESSION['user_id'])): ?>
                        <a href="/login" class="btn btn-outline-warning w-100 fw-bold">
                            <i class="bi bi-lock me-2"></i>Connectez-vous pour réserver
                        </a>
                    <?php else: ?>
                        <div class="alert alert-info small mb-0">
                            Seuls les clients peuvent effectuer des réservations.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Ce véhicule n'est pas disponible à la location actuellement.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contact agence -->
        <div class="card mt-3 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-2"><i class="bi bi-building me-2"></i>Agence</h6>
                <p class="mb-1"><?= htmlspecialchars($car['agency_nom']) ?></p>
                <?php if (!empty($car['agency_tel'])): ?>
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($car['agency_tel']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
