<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

// Autoloader PSR-4 (composer ou fallback maison)
require_once BASE_PATH . '/vendor/autoload.php';

// Config erreurs
$config = require BASE_PATH . '/config/app.php';

if ($config['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
    set_exception_handler(function (\Throwable $e) {
        error_log($e->getMessage());
        http_response_code(500);
        require BASE_PATH . '/app/Views/errors/500.php';
        exit;
    });
}

(new \App\Core\App())->run();
