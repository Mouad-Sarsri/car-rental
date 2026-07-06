<?php

/**
 * Configuration de la base de données
 * Copiez ce fichier en config/database.php et adaptez les valeurs.
 */

return [
    'host'     => $_ENV['DB_HOST']     ?? 'localhost',
    'port'     => $_ENV['DB_PORT']     ?? 3306,
    'database' => $_ENV['DB_DATABASE'] ?? 'car_rental',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];
