<?php
$role     = $_SESSION['user_role'] ?? null;
$authUser = $_SESSION['user']      ?? null;
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
function navActive(string $path, bool $exact = false): string {
    global $uri;
    return $exact ? ($uri === $path ? 'active' : '') : (str_starts_with($uri, $path) ? 'active' : '');
}
?>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);">
    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
            <span class="brand-icon"><i class="bi bi-car-front-fill text-warning"></i></span>
            <span>Car<span class="text-warning">Rental</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">

            <!-- ── Liens publics ── -->
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill <?= navActive('/', true) ?>" href="/">
                        <i class="bi bi-house me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill <?= navActive('/cars') ?>" href="/cars">
                        <i class="bi bi-car-front me-1"></i>Voitures
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill <?= navActive('/agences') ?>" href="/agences">
                        <i class="bi bi-building me-1"></i>Agences
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill <?= navActive('/contact') ?>" href="/contact">
                        <i class="bi bi-envelope me-1"></i>Contact
                    </a>
                </li>

                <!-- ── Manager ── -->
                <?php if ($role === 'agency_manager'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded-pill <?= navActive('/manager/') ?>"
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-speedometer2 me-1"></i>Mon espace
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark border-0 shadow">
                            <li><a class="dropdown-item" href="/manager/dashboard"><i class="bi bi-grid me-2"></i>Tableau de bord</a></li>
                            <li><hr class="dropdown-divider opacity-25"></li>
                            <li><a class="dropdown-item" href="/manager/cars"><i class="bi bi-car-front me-2"></i>Mes voitures</a></li>
                            <li><a class="dropdown-item" href="/manager/reservations"><i class="bi bi-calendar-check me-2"></i>Réservations</a></li>
                            <li><a class="dropdown-item" href="/manager/agency"><i class="bi bi-building me-2"></i>Mon agence</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- ── Super Admin ── -->
                <?php if ($role === 'super_manager'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded-pill <?= navActive('/admin/') ?>"
                           href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-shield-check me-1"></i>Administration
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark border-0 shadow">
                            <li><a class="dropdown-item" href="/admin/dashboard"><i class="bi bi-grid me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider opacity-25"></li>
                            <li><a class="dropdown-item" href="/admin/users"><i class="bi bi-people me-2"></i>Utilisateurs</a></li>
                            <li><a class="dropdown-item" href="/admin/agencies"><i class="bi bi-building me-2"></i>Agences</a></li>
                            <li><a class="dropdown-item" href="/admin/cars"><i class="bi bi-car-front me-2"></i>Voitures</a></li>
                            <li><a class="dropdown-item" href="/admin/reservations"><i class="bi bi-calendar-check me-2"></i>Réservations</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- ── Client ── -->
                <?php if ($role === 'client'): ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill <?= navActive('/reservations') ?>" href="/reservations">
                            <i class="bi bi-calendar-check me-1"></i>Mes réservations
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- ── Auth ── -->
            <ul class="navbar-nav gap-2 align-items-lg-center">
                <?php if ($authUser): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-white bg-opacity-10"
                           href="#" data-bs-toggle="dropdown">
                            <span class="avatar-sm">
                                <?= strtoupper(substr($authUser['prenom'],0,1).substr($authUser['nom'],0,1)) ?>
                            </span>
                            <span class="d-none d-lg-inline"><?= htmlspecialchars($authUser['prenom']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-0 shadow">
                            <li class="px-3 py-2">
                                <div class="fw-bold text-warning"><?= htmlspecialchars($authUser['prenom'].' '.$authUser['nom']) ?></div>
                                <div class="small text-muted"><?= htmlspecialchars($authUser['email']) ?></div>
                            </li>
                            <li><hr class="dropdown-divider opacity-25"></li>
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                            <li><hr class="dropdown-divider opacity-25"></li>
                            <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link px-3 <?= $uri === '/login' ? 'text-warning fw-bold' : '' ?>" href="/login">
                            <i class="bi bi-lock me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning fw-bold px-4" href="/register">
                            <i class="bi bi-person-plus me-1"></i>S'inscrire
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>
