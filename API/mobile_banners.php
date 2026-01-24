<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 

// Get screen parameter
$screen = $_GET['screen'] ?? 'home';

// Database connection (using same structure as existing API)
$serverName = "sql58.jnb1.host-h.net";
$username = "sabooksonline";
$password = "slTFvaj07dNY6Ke";
$primaryDb = "sabookso_db";

// Enable mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connect to database
    $mysqli = new mysqli($serverName, $username, $password, $primaryDb);
    $mysqli->set_charset("utf8mb4");

    // Safe query using prepared statement
    $stmt = $mysqli->prepare("SELECT id, title, description, image as image_url, action_url, screen, priority, start_date, end_date, created_at 
                              FROM Mobile_banners 
                              WHERE screen = ? 
                              AND (start_date <= NOW()) 
                              AND (end_date IS NULL OR end_date >= NOW())
                              ORDER BY priority DESC, created_at DESC");
    $stmt->bind_param("s", $screen);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and return results
    $banners = [];
    while ($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }

    $stmt->close();
    $mysqli->close();

    // Return response in expected format
    echo json_encode([
        'success' => true,
        'screen' => $screen,
        'banners' => $banners
    ]);
} catch (mysqli_sql_exception $e) {
    error_log("Mobile Banners API Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'screen' => $screen,
        'banners' => [],
        'error' => 'Database connection or query failed'
    ]);
}
?>