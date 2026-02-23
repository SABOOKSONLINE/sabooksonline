<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../load_env.php';

// Read DB configuration from environment. In production we require these to be set;
// for local/dev we fall back to safe defaults.
$serverName = getenv('DB_HOST');
$username   = getenv('DB_USERNAME');
$password   = getenv('DB_PASSWORD');
$primaryDb  = getenv('DB_NAME');

$appEnv = getenv('APP_ENV') ?: 'development';
if ($appEnv === 'production') {
    if (!$serverName || !$username || !$primaryDb) {
        error_log('Missing DB env vars (DB_HOST/DB_USERNAME/DB_NAME) in production');
        die('Server configuration error: database not configured.');
    }
} else {
    // Safe development defaults (non-sensitive)
    $serverName = $serverName ?: '127.0.0.1';
    $username   = $username   ?: 'root';
    $password   = $password   ?: '';
    $primaryDb  = $primaryDb  ?: 'sabooksonline_local';
}

// Enable exceptions for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // create connection to primaryDb
    $conn = new mysqli($serverName, $username, $password, $primaryDb);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Centralized error logging
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database Connection Error: " . $e->getMessage());
}
