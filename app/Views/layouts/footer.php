<footer class="text-white pt-5 pb-3 mt-auto" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);">
    <div class="container">
        <div class="row g-4 mb-4">

            <!-- Brand -->
            <div class="col-lg-4">
                <a href="/" class="text-decoration-none">
                    <h5 class="fw-bold text-white mb-2">
                        <i class="bi bi-car-front-fill text-warning me-2"></i>Car<span class="text-warning">Rental</span>
                    </h5>
                </a>
                <p class="text-white-50 small">
                    Leader de la location de voitures multi-agences au Maroc.
                    Des véhicules de qualité, des prix transparents, un service irréprochable.
                </p>
            </div>

            <!-- Navigation -->
            <div class="col-6 col-lg-2">
                <h6 class="text-warning fw-bold mb-3 text-uppercase small">Navigation</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="/"        class="text-white-50 text-decoration-none footer-link">Accueil</a></li>
                    <li class="mb-2"><a href="/cars"     class="text-white-50 text-decoration-none footer-link">Voitures</a></li>
                    <li class="mb-2"><a href="/agences"  class="text-white-50 text-decoration-none footer-link">Agences</a></li>
                    <li class="mb-2"><a href="/contact"  class="text-white-50 text-decoration-none footer-link">Contact</a></li>
                </ul>
            </div>

            <!-- Compte -->
            <div class="col-6 col-lg-2">
                <h6 class="text-warning fw-bold mb-3 text-uppercase small">Compte</h6>
                <ul class="list-unstyled small">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="mb-2"><a href="/login"    class="text-white-50 text-decoration-none footer-link">Connexion</a></li>
                        <li class="mb-2"><a href="/register" class="text-white-50 text-decoration-none footer-link">S'inscrire</a></li>
                    <?php else: ?>
                        <li class="mb-2"><a href="/profile"      class="text-white-50 text-decoration-none footer-link">Mon profil</a></li>
                        <li class="mb-2"><a href="/reservations" class="text-white-50 text-decoration-none footer-link">Mes réservations</a></li>
                        <li class="mb-2"><a href="/logout"       class="text-white-50 text-decoration-none footer-link">Déconnexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-4">
                <h6 class="text-warning fw-bold mb-3 text-uppercase small">Nous contacter</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><i class="bi bi-geo-alt text-warning me-2"></i>25 Bd Mohammed V, Casablanca</li>
                    <li class="mb-2">
                        <i class="bi bi-telephone text-warning me-2"></i>
                        <a href="tel:+212522000000" class="text-white-50 text-decoration-none">+212 5 22 00 00 00</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope text-warning me-2"></i>
                        <a href="mailto:contact@carrental.ma" class="text-white-50 text-decoration-none">contact@carrental.ma</a>
                    </li>
                    <li><i class="bi bi-clock text-warning me-2"></i>Lun–Sam 8h–19h &nbsp;|&nbsp; Dim 9h–13h</li>
                </ul>
            </div>
        </div>

        <hr class="border-white border-opacity-10">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="text-white-50 small mb-0">
                &copy; <?= date('Y') ?> CarRental Maroc — Tous droits réservés
            </p>
            <p class="text-white-50 small mb-0">
                Fait avec <i class="bi bi-heart-fill text-warning"></i> au Maroc
            </p>
        </div>
    </div>
</footer>

<style>
.footer-link:hover { color: #FFC107 !important; }
</style>
