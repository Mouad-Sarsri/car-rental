<?php $pageTitle = 'Connexion'; ?>
<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold mb-4 text-center">
                    <i class="bi bi-lock text-warning me-2"></i>Connexion
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

                <form method="POST" action="/login">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                               required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning fw-bold w-100">
                        Se connecter
                    </button>
                </form>

                <hr>
                <p class="text-center mb-0 small">
                    Pas encore de compte ?
                    <a href="/register" class="text-warning fw-bold">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</div>
