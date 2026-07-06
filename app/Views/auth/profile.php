<?php $pageTitle = 'Mon profil'; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm text-center">
            <div class="card-body py-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-warning text-dark rounded-circle fw-bold"
                     style="width:80px;height:80px;font-size:28px;">
                    <?= strtoupper(substr($user['prenom'],0,1).substr($user['nom'],0,1)) ?>
                </div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['prenom'].' '.$user['nom']) ?></h5>
                <p class="text-muted small"><?= htmlspecialchars($user['email']) ?></p>
                <?php
                $roleLabels = ['client'=>'Client','agency_manager'=>'Manager agence','super_manager'=>'Super manager'];
                $roleColors = ['client'=>'info','agency_manager'=>'warning','super_manager'=>'danger'];
                ?>
                <span class="badge bg-<?= $roleColors[$user['role']] ?? 'secondary' ?>">
                    <?= $roleLabels[$user['role']] ?? $user['role'] ?>
                </span>
                <div class="text-muted small mt-3">Membre depuis <?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Infos principales -->
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-bold"><i class="bi bi-person me-2"></i>Mes informations</div>
            <div class="card-body p-4">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $msgs): foreach((array)$msgs as $m): ?>
                            <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($m) ?></div>
                        <?php endforeach; endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/profile">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning fw-bold">
                                <i class="bi bi-check-circle me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Changer mot de passe -->
        <div class="card shadow-sm">
            <div class="card-header fw-bold"><i class="bi bi-key me-2"></i>Changer le mot de passe</div>
            <div class="card-body p-4">
                <form method="POST" action="/profile/password">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Mot de passe actuel</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Confirmer</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-warning fw-bold">
                                <i class="bi bi-shield-check me-2"></i>Changer le mot de passe
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
