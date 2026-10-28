<?php
/**
 * Nilayam Heights HMS & Yadav PG - Database Connection & System Configuration
 * 
 * Supports environment variables via .env.local (developer secrets) and .env (deployments).
 * Zero Composer dependencies required - powered by lightweight native env_loader.php.
 * 
 * Team reminder: Always place custom credentials in .env.local (git-ignored),
 * never hardcode database passwords in tracked repository files!
 */
require_once __DIR__ . '/env_loader.php';

// Database configuration with environment variable support
define('DB_SERVER', hms_env('DB_HOST', 'localhost'));
define('DB_USERNAME', hms_env('DB_USER', 'root'));
define('DB_PASSWORD', hms_env('DB_PASS', ''));
define('DB_NAME', hms_env('DB_NAME', 'hms'));
define('DB_PORT', (int)hms_env('DB_PORT', 3306));

/* Attempt to connect to MySQL database */
try {
    $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($con === false) {
        throw new Exception(mysqli_connect_error());
    }
} catch (Throwable $e) {
    $errorMsg = $e->getMessage();
    die("ERROR: Could not connect to MySQL database.\n"
      . "Host: " . DB_SERVER . ":" . DB_PORT . " | User: " . DB_USERNAME . " | Database: " . DB_NAME . "\n"
      . "Tip for Developers: Copy .env.example to .env.local and configure your local MySQL credentials.\n"
      . "Detail: " . $errorMsg);
}
?>