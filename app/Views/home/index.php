<?php $pageTitle = 'Location de voitures au Maroc'; ?>

<!-- ── Hero ── -->
<div class="hero-section position-relative overflow-hidden" style="margin-top:-1.5rem;margin-left:-12px;margin-right:-12px;background-image:url('/assets/img/index.png');background-size:cover;background-position:center;">    
    <div class="hero-overlay position-absolute w-100 h-100" style="background:linear-gradient(to right,rgba(0,0,0,.88) 0%,rgba(0,0,0,.70) 60%,rgba(0,0,0,.30) 100%);top:0;left:0;z-index:1;"></div>
    <div class="hero-bg-shapes position-absolute w-100 h-100" style="top:0;left:0;z-index:0;overflow:hidden;">
        <div style="position:absolute;right:-5%;top:-10%;width:600px;height:600px;border-radius:50%;background:radial-gradient(circle,rgba(255,193,7,.08) 0%,transparent 70%);"></div>
        <div style="position:absolute;left:-5%;bottom:-10%;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(255,193,7,.05) 0%,transparent 70%);"></div>
    </div>
    <div class="container position-relative py-5" style="z-index:2;">
        <div class="row align-items-center py-4">
            <div class="text-white py-4">
                <span class="badge fw-bold mb-4 px-3 py-2" style="background:rgba(255,193,7,.2);color:#FFC107;border:1px solid rgba(255,193,7,.3);">
                    <i class="bi bi-star-fill me-1"></i>N°1 de la location au Maroc
                </span>
                <h1 class="display-3 fw-bold lh-sm mb-4 text-center">
                    Trouvez la voiture<br>
                    <span class="text-warning">parfaite</span> <br>
                    pour votre voyage
                </h1>
                <p class="lead mb-4 text-center" style="color:rgba(255,255,255,.75);">
                    Plus de <strong class="text-warning"><?= $stats['total_cars'] ?? 0 ?></strong> véhicules disponibles
                    dans <strong class="text-warning"><?= $stats['total_agencies'] ?? 0 ?></strong> agences
                    à travers tout le Maroc.
                </p>
                <div class="d-flex gap-3 flex-wrap justify-content-center">
                    <a href="/cars" class="btn btn-warning btn-lg fw-bold px-5 shadow">
                        <i class="bi bi-search me-2"></i>Voir les voitures
                    </a>
                    <a href="/agences" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-building me-2"></i>Nos agences
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Barre de recherche rapide ── -->
<div class="container" style="margin-top:-2.5rem;position:relative;z-index:10;">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <form method="GET" action="/cars" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase tracking-wide">
                        <i class="bi bi-geo-alt-fill text-warning me-1"></i>Ville
                    </label>
                    <select name="ville" class="form-select form-select-lg bg-light border-0">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= htmlspecialchars($v['ville']) ?>">
                                <?= htmlspecialchars($v['ville']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">
                        <i class="bi bi-calendar-event text-warning me-1"></i>Date de début
                    </label>
                    <input type="date" name="date_debut" id="heroDebut"
                           class="form-control form-control-lg bg-light border-0"
                           min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted text-uppercase">
                        <i class="bi bi-calendar-check text-warning me-1"></i>Date de fin
                    </label>
                    <input type="date" name="date_fin" id="heroFin"
                           class="form-control form-control-lg bg-light border-0"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm">
                        <i class="bi bi-search me-2"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Stats ── -->
<div class="bg-dark py-5 mt-5">
    <div class="container">
        <div class="row g-4 text-center">
            <?php
            $kpis = [
                ['val' => ($stats['total_cars'] ?? 0).'+',     'label' => 'Véhicules',          'icon' => 'car-front-fill'],
                ['val' => $stats['total_agencies'] ?? 0,       'label' => 'Agences',             'icon' => 'building'],
                ['val' => ($stats['total_clients'] ?? 0).'+',  'label' => 'Clients',             'icon' => 'people-fill'],
                ['val' => '4',                                  'label' => 'Villes couvertes',    'icon' => 'geo-alt-fill'],
            ];
            foreach ($kpis as $k): ?>
                <div class="col-6 col-md-3">
                    <i class="bi bi-<?= $k['icon'] ?> text-warning mb-2 d-block" style="font-size:2rem;"></i>
                    <div class="display-6 fw-bold text-white"><?= $k['val'] ?></div>
                    <div class="text-white-50 small"><?= $k['label'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ── Voitures vedettes ── -->
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2">Sélection</span>
        <h2 class="fw-bold display-6">Nos véhicules <span class="text-warning">populaires</span></h2>
        <p class="text-muted">Découvrez nos voitures les plus demandées</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($featured_cars as $car): ?>
            <div class="col">
                <div class="card h-100 shadow-sm car-card border-0 rounded-4 overflow-hidden">
                    <div class="position-relative car-img-wrapper">
                        <?php if (!empty($car['photo'])): ?>
                            <img src="/assets/uploads/cars/<?= htmlspecialchars($car['photo']) ?>"
                                 class="car-img" alt="<?= htmlspecialchars($car['marque'].' '.$car['modele']) ?>">
                        <?php else: ?>
                            <div class="car-img-placeholder">
                                <i class="bi bi-car-front-fill text-warning" style="font-size:5rem;opacity:.3;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-dark bg-opacity-75"><?= htmlspecialchars($car['agency_ville']) ?></span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning text-dark fw-bold"><?= $car['annee'] ?></span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($car['marque']) ?></h5>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($car['modele']) ?></p>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <span class="badge bg-light text-dark border"><i class="bi bi-fuel-pump me-1 text-warning"></i><?= ucfirst($car['carburant']) ?></span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-gear me-1 text-warning"></i><?= ucfirst($car['transmission']) ?></span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-people me-1 text-warning"></i><?= $car['nb_places'] ?> places</span>
                            <?php if ($car['climatisation']): ?>
                                <span class="badge bg-light text-dark border"><i class="bi bi-snow me-1 text-info"></i>Clim</span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <div>
                                <span class="fs-3 fw-bold text-warning"><?= number_format($car['prix_jour'],0) ?></span>
                                <span class="text-muted small"> MAD/jour</span>
                            </div>
                            <a href="/cars/<?= $car['id'] ?>" class="btn btn-warning fw-bold px-4">
                                Réserver <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
        <a href="/cars" class="btn btn-outline-dark btn-lg fw-bold px-5">
            Voir tous les véhicules <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</div>

<!-- ── Agences ── -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2">Réseau</span>
            <h2 class="fw-bold display-6">Nos <span class="text-warning">agences</span></h2>
            <p class="text-muted">Présents dans les principales villes du Maroc</p>
        </div>
        <div class="row g-4">
            <?php foreach ($agencies as $a): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4 text-center p-4">
                        <div class="mb-3" style="font-size:2.5rem;"><i class="bi bi-building text-warning"></i></div>
                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($a['nom']) ?></h6>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-geo-alt me-1 text-warning"></i><?= htmlspecialchars($a['ville']) ?>
                        </p>
                        <p class="text-muted small mb-3"><?= htmlspecialchars($a['adresse']) ?></p>
                        <?php if (!empty($a['telephone'])): ?>
                            <p class="small mb-3">
                                <i class="bi bi-telephone text-warning me-1"></i><?= htmlspecialchars($a['telephone']) ?>
                            </p>
                        <?php endif; ?>
                        <a href="/cars?ville=<?= urlencode($a['ville']) ?>" class="btn btn-outline-warning btn-sm mt-auto fw-bold">
                            Voitures disponibles
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ── Avantages ── -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">Pourquoi choisir <span class="text-warning">CarRental</span> ?</h2>
    </div>
    <div class="row g-4 text-center">
        <?php
        $avantages = [
            ['icon'=>'shield-fill-check','color'=>'success','titre'=>'Véhicules assurés',    'desc'=>'Chaque voiture est contrôlée, entretenue et assurée tous risques.'],
            ['icon'=>'lightning-charge-fill','color'=>'warning','titre'=>'Réservation rapide','desc'=>'En 2 minutes, votre voiture est réservée. Confirmation instantanée.'],
            ['icon'=>'headset',           'color'=>'info',   'titre'=>'Support 7j/7',          'desc'=>'Une équipe disponible à toute heure pour vous accompagner.'],
            ['icon'=>'cash-coin',         'color'=>'warning','titre'=>'Prix transparents',     'desc'=>'Aucun frais caché. Prix fixes, caution entièrement remboursable.'],
        ];
        foreach ($avantages as $av): ?>
            <div class="col-md-6 col-lg-3">
                <div class="p-4 h-100 rounded-4 border border-2 hover-lift">
                    <div class="mb-3 text-<?= $av['color'] ?>" style="font-size:3rem;">
                        <i class="bi bi-<?= $av['icon'] ?>"></i>
                    </div>
                    <h5 class="fw-bold mb-2"><?= $av['titre'] ?></h5>
                    <p class="text-muted small mb-0"><?= $av['desc'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ── CTA ── -->
<div class="py-5" style="background:linear-gradient(135deg,#FFC107 0%,#FF8C00 100%);">
    <div class="container text-center">
        <h2 class="fw-bold display-6 text-dark mb-3">Prêt à prendre la route ?</h2>
        <p class="text-dark mb-4" style="opacity:.8;">Créez votre compte gratuitement et réservez votre voiture en 2 minutes.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/register" class="btn btn-dark btn-lg fw-bold px-5 shadow">
                    <i class="bi bi-person-plus me-2"></i>Créer un compte
                </a>
                <a href="/cars" class="btn btn-outline-dark btn-lg px-5">
                    <i class="bi bi-car-front me-2"></i>Parcourir les voitures
                </a>
            <?php else: ?>
                <a href="/cars" class="btn btn-dark btn-lg fw-bold px-5 shadow">
                    <i class="bi bi-search me-2"></i>Trouver ma voiture
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('heroDebut')?.addEventListener('change', function() {
    const fin = document.getElementById('heroFin');
    const next = new Date(this.value);
    next.setDate(next.getDate() + 1);
    fin.min = next.toISOString().split('T')[0];
    if (fin.value && fin.value <= this.value) fin.value = '';
});
</script>
