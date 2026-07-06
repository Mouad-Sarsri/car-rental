<?php $pageTitle = 'Nos agences'; ?>

<div class="text-center mb-5 pt-2">
    <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2">Réseau CarRental</span>
    <h2 class="fw-bold display-6">Nos <span class="text-warning">agences</span> au Maroc</h2>
    <p class="text-muted">Retrouvez-nous dans les principales villes du royaume</p>
</div>

<?php foreach ($agencies as $a): ?>
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden" id="agence-<?= $a['id'] ?>">
        <div class="row g-0">

            <!-- Infos agence -->
            <div class="col-md-4 p-4 d-flex flex-column justify-content-between" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                <div>
                    <div class="mb-3" style="font-size:3rem;"><i class="bi bi-building text-warning"></i></div>
                    <h4 class="fw-bold text-white mb-1"><?= htmlspecialchars($a['nom']) ?></h4>
                    <p class="text-warning mb-3">
                        <i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($a['ville']) ?>
                    </p>
                    <div class="text-white-50 small">
                        <p class="mb-2">
                            <i class="bi bi-map me-2 text-warning"></i><?= htmlspecialchars($a['adresse']) ?>
                            <?php if ($a['code_postal']): ?>, <?= $a['code_postal'] ?><?php endif; ?>
                        </p>
                        <?php if ($a['telephone']): ?>
                            <p class="mb-2">
                                <i class="bi bi-telephone me-2 text-warning"></i>
                                <a href="tel:<?= htmlspecialchars($a['telephone']) ?>" class="text-white-50 text-decoration-none">
                                    <?= htmlspecialchars($a['telephone']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ($a['email']): ?>
                            <p class="mb-2">
                                <i class="bi bi-envelope me-2 text-warning"></i>
                                <a href="mailto:<?= htmlspecialchars($a['email']) ?>" class="text-white-50 text-decoration-none">
                                    <?= htmlspecialchars($a['email']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php if ($a['description']): ?>
                        <p class="text-white-50 small mt-3 mb-0"><?= htmlspecialchars($a['description']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="mt-4">
                    <a href="/cars?ville=<?= urlencode($a['ville']) ?>" class="btn btn-warning fw-bold w-100">
                        <i class="bi bi-car-front me-2"></i>Voir les voitures
                    </a>
                </div>
            </div>

            <!-- Voitures de l'agence -->
            <div class="col-md-8 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-muted text-uppercase small tracking-wide">
                        <i class="bi bi-car-front text-warning me-2"></i>
                        Véhicules disponibles (<?= count($a['cars']) ?>)
                    </h6>
                </div>

                <?php if (empty($a['cars'])): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-car-front d-block mb-2" style="font-size:2rem;"></i>
                        <small>Aucun véhicule disponible actuellement.</small>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach (array_slice($a['cars'], 0, 4) as $car): ?>
                            <div class="col-sm-6">
                                <a href="/cars/<?= $car['id'] ?>" class="card border rounded-3 text-decoration-none text-dark h-100 hover-lift">
                                    <div class="row g-0 align-items-center p-2">
                                        <div class="col-4">
                                            <?php if (!empty($car['photo'])): ?>
                                                <img src="/assets/uploads/cars/<?= htmlspecialchars($car['photo']) ?>"
                                                     class="img-fluid rounded-2"
                                                     style="height:55px;width:100%;object-fit:cover;"
                                                     alt="<?= htmlspecialchars($car['marque']) ?>">
                                            <?php else: ?>
                                                <div class="bg-light rounded-2 d-flex align-items-center justify-content-center" style="height:55px;">
                                                    <i class="bi bi-car-front text-warning"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-8 ps-2">
                                            <div class="fw-bold small"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></div>
                                            <div class="text-muted" style="font-size:.7rem;"><?= $car['annee'] ?> · <?= ucfirst($car['carburant']) ?></div>
                                            <div class="text-warning fw-bold small"><?= number_format($car['prix_jour'],0) ?> MAD/j</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($a['cars']) > 4): ?>
                        <div class="text-center mt-3">
                            <a href="/cars?ville=<?= urlencode($a['ville']) ?>" class="btn btn-outline-warning btn-sm fw-bold">
                                Voir les <?= count($a['cars']) - 4 ?> autres véhicules
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
