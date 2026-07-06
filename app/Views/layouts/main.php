<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'CarRental' ?> — CarRental Maroc</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<?php require __DIR__ . '/navbar.php'; ?>

<main class="flex-grow-1">
    <?php
    // Affichage des messages flash
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    // Vues sans container propre (homepage, agences, contact)
    $fullWidthViews = ['home/index'];
    $isFullWidth = isset($currentView) && in_array($currentView, $fullWidthViews);
    if (!empty($flash)): ?>
        <div class="container pt-3">
            <?php foreach ($flash as $type => $message): ?>
                <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?> alert-dismissible fade show">
                    <i class="bi bi-<?= $type === 'error' ? 'exclamation-triangle' : 'check-circle' ?> me-2"></i>
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($isFullWidth): ?>
        <?= $content ?>
    <?php else: ?>
        <div class="container py-4">
            <?= $content ?>
        </div>
    <?php endif; ?>
</main>

<?php require __DIR__ . '/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
