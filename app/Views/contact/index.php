<?php $pageTitle = 'Contactez-nous'; ?>

<div class="text-center mb-5 pt-2">
    <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2">Support</span>
    <h2 class="fw-bold display-6">Contactez <span class="text-warning">CarRental</span></h2>
    <p class="text-muted">Notre équipe vous répond sous 24h — souvent bien avant !</p>
</div>

<div class="row g-5">

    <!-- ── Formulaire ── -->
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-4 p-2">
            <div class="card-body p-4">

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success rounded-3">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>Message envoyé !</strong> Nous vous répondrons dans les plus brefs délais.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger rounded-3">
                        <?php foreach ($errors as $msgs): foreach ((array)$msgs as $m): ?>
                            <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
                        <?php endforeach; endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/contact" id="contactForm">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" class="form-control bg-light border-0"
                                   value="<?= htmlspecialchars($old['prenom'] ?? ($authUser['prenom'] ?? '')) ?>"
                                   placeholder="Votre prénom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control bg-light border-0"
                                   value="<?= htmlspecialchars($old['nom'] ?? ($authUser['nom'] ?? '')) ?>"
                                   placeholder="Votre nom" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-light border-0"
                                   value="<?= htmlspecialchars($old['email'] ?? ($authUser['email'] ?? '')) ?>"
                                   placeholder="votre@email.ma" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control bg-light border-0"
                                   value="<?= htmlspecialchars($old['telephone'] ?? '') ?>"
                                   placeholder="+212 6xx xxx xxx">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Sujet <span class="text-danger">*</span></label>
                            <select name="sujet" class="form-select bg-light border-0" required>
                                <option value="">— Choisir un sujet —</option>
                                <?php
                                $sujets = [
                                    'reservation'  => 'Question sur une réservation',
                                    'disponibilite'=> 'Disponibilité d\'un véhicule',
                                    'tarifs'       => 'Informations tarifaires',
                                    'reclamation'  => 'Réclamation',
                                    'partenariat'  => 'Partenariat / Agence',
                                    'autre'        => 'Autre demande',
                                ];
                                foreach ($sujets as $val => $label): ?>
                                    <option value="<?= $val ?>" <?= ($old['sujet'] ?? '') === $val ? 'selected':'' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control bg-light border-0" rows="6"
                                      placeholder="Décrivez votre demande en détail…" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                            <div class="form-text text-end">
                                <span id="charCount">0</span> / 1000 caractères
                            </div>
                        </div>

                        <!-- Anti-spam simple -->
                        <input type="text" name="website" class="d-none" tabindex="-1" autocomplete="off">

                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rgpd" required>
                                <label class="form-check-label small" for="rgpd">
                                    J'accepte que mes données soient utilisées pour traiter ma demande.
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold w-100">
                                <i class="bi bi-send me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── Infos contact ── -->
    <div class="col-lg-5">

        <!-- Carte infos -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="p-4" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                <h5 class="text-white fw-bold mb-4">
                    <i class="bi bi-info-circle text-warning me-2"></i>Nos coordonnées
                </h5>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3 text-white-75">
                        <i class="bi bi-geo-alt-fill text-warning mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="fw-bold text-white small">Siège social</div>
                            <div class="text-white-50 small">25 Boulevard Mohammed V, Casablanca 20000</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <i class="bi bi-telephone-fill text-warning mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="fw-bold text-white small">Téléphone</div>
                            <a href="tel:+212522000000" class="text-white-50 text-decoration-none small">+212 5 22 00 00 00</a>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <i class="bi bi-envelope-fill text-warning mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="fw-bold text-white small">Email</div>
                            <a href="mailto:contact@carrental.ma" class="text-white-50 text-decoration-none small">contact@carrental.ma</a>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <i class="bi bi-clock-fill text-warning mt-1 flex-shrink-0"></i>
                        <div>
                            <div class="fw-bold text-white small">Horaires</div>
                            <div class="text-white-50 small">Lun – Sam : 8h00 – 19h00</div>
                            <div class="text-white-50 small">Dim : 9h00 – 13h00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agences -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-building text-warning me-2"></i>Nos agences</h6>
                <?php foreach ($agencies as $a): ?>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-bold small"><?= htmlspecialchars($a['ville']) ?></div>
                            <div class="text-muted" style="font-size:.75rem;"><?= htmlspecialchars($a['adresse']) ?></div>
                        </div>
                        <?php if ($a['telephone']): ?>
                            <a href="tel:<?= htmlspecialchars($a['telephone']) ?>" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-telephone"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- FAQ rapide -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-question-circle text-warning me-2"></i>Questions fréquentes</h6>
                <div class="accordion accordion-flush" id="faq">
                    <?php
                    $faqs = [
                        'Quel âge minimum pour louer ?' => 'Vous devez avoir au moins 21 ans et posséder un permis valide depuis 1 an.',
                        'Quels documents sont nécessaires ?' => 'CIN ou passeport, permis de conduire et une carte bancaire pour la caution.',
                        'La caution est-elle remboursée ?' => 'Oui, intégralement dans les 5 jours suivant le retour du véhicule en bon état.',
                        'Puis-je annuler ma réservation ?' => 'Oui, depuis votre espace client jusqu\'à 24h avant la date de prise en charge.',
                    ];
                    $i = 0;
                    foreach ($faqs as $q => $r): ?>
                        <div class="accordion-item border-0 border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed px-0 py-2 small fw-bold bg-transparent shadow-none"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faq<?= $i ?>">
                                    <?= htmlspecialchars($q) ?>
                                </button>
                            </h2>
                            <div id="faq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#faq">
                                <div class="accordion-body px-0 py-2 text-muted small"><?= htmlspecialchars($r) ?></div>
                            </div>
                        </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const msg = document.querySelector('textarea[name="message"]');
const cnt = document.getElementById('charCount');
if (msg && cnt) {
    msg.addEventListener('input', () => {
        cnt.textContent = msg.value.length;
        if (msg.value.length > 900) cnt.classList.add('text-danger');
        else cnt.classList.remove('text-danger');
        if (msg.value.length > 1000) msg.value = msg.value.slice(0, 1000);
    });
}
</script>
