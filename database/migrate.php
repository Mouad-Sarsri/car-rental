<?php

/**
 * Migration & Seeder Runner
 * Usage :
 *   php migrate.php                  → exécute les migrations en attente
 *   php migrate.php --seed           → migrations + seeders
 *   php migrate.php --fresh          → DROP tout + migrations + seeders
 *   php migrate.php --fresh --seed   → idem avec seeders
 *   php migrate.php --seed-only      → seeders uniquement (tables vides)
 */

declare(strict_types=1);

// ── Configuration ─────────────────────────────────────────────────────────────
$config = require __DIR__ . '/../config/database.php';

$dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
    $config['host'],
    $config['port'] ?? 3306,
    $config['database']
);

// ── Helpers ───────────────────────────────────────────────────────────────────
function color(string $text, string $type = 'info'): string
{
    $codes = [
        'info'    => "\033[0;36m",  // cyan
        'success' => "\033[0;32m",  // green
        'warning' => "\033[0;33m",  // yellow
        'error'   => "\033[0;31m",  // red
        'bold'    => "\033[1m",
    ];
    $reset = "\033[0m";
    return ($codes[$type] ?? '') . $text . $reset;
}

function line(string $msg, string $type = 'info'): void
{
    echo color($msg, $type) . PHP_EOL;
}

function separator(): void
{
    echo str_repeat('─', 55) . PHP_EOL;
}

function runSqlFile(PDO $pdo, string $filepath): void
{
    $filename = basename($filepath);
    $sql = file_get_contents($filepath);

    // Supprimer les commentaires et les lignes vides
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        fn($s) => !empty($s) && !str_starts_with(ltrim($s), '--')
    );

    foreach ($statements as $statement) {
        if (trim($statement) === '') continue;
        try {
            $pdo->exec($statement);
        } catch (PDOException $e) {
            line("  ✗ Erreur dans {$filename}: " . $e->getMessage(), 'error');
            exit(1);
        }
    }
}

// ── Parse des arguments CLI ───────────────────────────────────────────────────
$args      = array_slice($argv, 1);
$isFresh   = in_array('--fresh', $args);
$withSeed  = in_array('--seed', $args) || in_array('--seed-only', $args);
$seedOnly  = in_array('--seed-only', $args);

// ── Connexion PDO ─────────────────────────────────────────────────────────────
try {
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    line('Connexion impossible : ' . $e->getMessage(), 'error');
    exit(1);
}

$migrationsDir = __DIR__ . '/migrations';
$seedersDir    = __DIR__ . '/seeders';

separator();
line('CarRental — Database Runner', 'bold');
separator();

// ── --fresh : tout dropper ─────────────────────────────────────────────────────
if ($isFresh) {
    line('Mode --fresh : suppression de toutes les tables...', 'warning');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
        line(" DROP TABLE {$table}", 'success');
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    line('');
}

// ── Migrations ────────────────────────────────────────────────────────────────
if (!$seedOnly) {
    line('Migrations', 'bold');

    // S'assurer que la table migrations existe
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
            filename    VARCHAR(255) NOT NULL,
            executed_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uq_migration_file (filename)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Migrations déjà exécutées
    $done = $pdo->query("SELECT filename FROM migrations ORDER BY filename")
                ->fetchAll(PDO::FETCH_COLUMN);

    // Fichiers à exécuter
    $files = glob($migrationsDir . '/*.sql');
    sort($files);

    $count = 0;
    foreach ($files as $file) {
        $filename = basename($file);
        if (in_array($filename, $done)) {
            line("  · {$filename} (déjà exécutée)");
            continue;
        }

        runSqlFile($pdo, $file);

        $stmt = $pdo->prepare("INSERT INTO migrations (filename) VALUES (?)");
        $stmt->execute([$filename]);

        line("{$filename}", 'success');
        $count++;
    }

    if ($count === 0) {
        line('  Aucune migration en attente.', 'warning');
    } else {
        line(" {$count} migration(s) exécutée(s).", 'success');
    }
    line('');
}

// ── Seeders ───────────────────────────────────────────────────────────────────
if ($withSeed) {
    line('Seeders', 'bold');

    $files = glob($seedersDir . '/*.sql');
    sort($files);

    if (empty($files)) {
        line('  Aucun seeder trouvé.', 'warning');
    }

    // Désactiver les FK pour insérer dans l'ordre
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

    foreach ($files as $file) {
        $filename = basename($file);
        runSqlFile($pdo, $file);
        line("  {$filename}", 'success');
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    line('  Seeders terminés.', 'success');
    line('');
}

separator();
line('Opération terminée avec succès.', 'success');
separator();
