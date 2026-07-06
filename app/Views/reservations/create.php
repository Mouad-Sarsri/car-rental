<?php $pageTitle = 'Nouvelle réservation'; ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <h2 class="fw-bold mb-4"><i class="bi bi-calendar-plus text-warning me-2"></i>Réserver un véhicule</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $msgs): ?>
                    <?php foreach ((array)$msgs as $msg): ?>
                        <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($car): ?>
            <!-- Récap voiture -->
            <div class="card mb-4 shadow-sm border-warning">
                <div class="card-body d-flex gap-3 align-items-center">
                    <?php if (!empty($car['photo'])): ?>
                        <img src="/assets/uploads/cars/<?= htmlspecialchars($car['photo']) ?>"
                             style="width:100px;height:70px;object-fit:cover;border-radius:8px;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="width:100px;height:70px;border-radius:8px;">
                            <i class="bi bi-car-front fs-1 text-secondary"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h5 class="mb-0 fw-bold"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                        <p class="mb-0 text-muted small">
                            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($car['agency_nom']) ?> — <?= htmlspecialchars($car['agency_ville']) ?>
                        </p>
                        <p class="mb-0 fw-bold text-warning"><?= number_format($car['prix_jour'], 0) ?> MAD/jour</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="/reservations" id="resaForm">
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Date de début <span class="text-danger">*</span></label>
                                <input type="date" name="date_debut" id="dateDebut"
                                       class="form-control"
                                       value="<?= htmlspecialchars($date_debut ?? '') ?>"
                                       min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Date de fin <span class="text-danger">*</span></label>
                                <input type="date" name="date_fin" id="dateFin"
                                       class="form-control"
                                       value="<?= htmlspecialchars($date_fin ?? '') ?>"
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                            </div>

                            <!-- Calcul prix dynamique -->
                            <div class="col-12" id="prixCalcule" style="display:none;">
                                <div class="bg-warning bg-opacity-10 rounded p-3">
                                    <div class="row">
                                        <div class="col-6"><span class="text-muted small">Durée</span><br><strong id="nbJours">—</strong> jour(s)</div>
                                        <div class="col-6 text-end"><span class="text-muted small">Total estimé</span><br><strong id="prixTotal" class="text-warning fs-5">—</strong> MAD</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Notes (optionnel)</label>
                                <textarea name="notes_client" class="form-control" rows="3"
                                          placeholder="Demandes spéciales, heure d'arrivée…"></textarea>
                            </div>

                            <div class="col-12 d-flex gap-2">
                                <a href="/cars/<?= $car['id'] ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Retour
                                </a>
                                <button type="submit" class="btn btn-warning fw-bold flex-grow-1">
                                    <i class="bi bi-check-circle me-2"></i>Confirmer la réservation
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function() {
    const prixJour = <?= (float)($car['prix_jour'] ?? 0) ?>;
    const debut    = document.getElementById('dateDebut');
    const fin      = document.getElementById('dateFin');
    const bloc     = document.getElementById('prixCalcule');
    const nbEl     = document.getElementById('nbJours');
    const totalEl  = document.getElementById('prixTotal');

    function calc() {
        if (!debut.value || !fin.value) { bloc.style.display='none'; return; }
        const d = new Date(debut.value), f = new Date(fin.value);
        const jours = Math.round((f - d) / 86400000);
        if (jours <= 0) { bloc.style.display='none'; return; }
        nbEl.textContent    = jours;
        totalEl.textContent = (jours * prixJour).toLocaleString('fr-MA');
        bloc.style.display  = 'block';
        fin.min = new Date(d.getTime() + 86400000).toISOString().split('T')[0];
    }

    debut.addEventListener('change', calc);
    fin.addEventListener('change', calc);
    calc();
})();
</script>
