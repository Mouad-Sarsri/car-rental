<?php $pageTitle = 'Inscription'; ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold mb-4 text-center">
                    <i class="bi bi-person-plus text-warning me-2"></i>Créer un compte
                </h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $field => $msgs): ?>
                            <?php foreach ($msgs as $msg): ?>
                                <div><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($msg) ?></div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control"
                                   value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control"
                                   value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control"
                                   value="<?= htmlspecialchars($old['telephone'] ?? '') ?>"
                                   placeholder="+212 6xx xxx xxx">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Confirmer</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-warning fw-bold w-100">
                                Créer mon compte
                            </button>
                        </div>
                    </div>
                </form>

                <hr>
                <p class="text-center mb-0 small">
                    Déjà inscrit ? <a href="/login" class="text-warning fw-bold">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
