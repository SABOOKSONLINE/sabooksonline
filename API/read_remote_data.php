<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Authorized API keys
$authorizedKeys = [
    'nola1234',
    'bongo1234',
    // Add more authorized keys here
];

// Get values from the request
$providedApiKey = $_GET['api_key'] ?? null;
$userkey = $_GET['userkey'] ?? null;

// Validate API key
if (!in_array($providedApiKey, $authorizedKeys)) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Use the same variables from your first script
$serverName = "sql7.jnb3.host-h.net";
$username2nd = "sabookso_db";
$secondaryDb = "sabookso_plesk_acc";
$password = "slTFvaj07dNY6Ke";

// Enable mysqli exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connect to database
    $mysqli = new mysqli($serverName, $username2nd, $password, $secondaryDb);
    $mysqli->set_charset("utf8mb4");

    // Safe query using prepared statement
    $stmt = $mysqli->prepare("SELECT * FROM plesk_accounts WHERE USERKEY = ?");
    $stmt->bind_param("s", $userkey);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and return results
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode($data);
} catch (mysqli_sql_exception $e) {
    error_log("DB Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection or query failed']);
}
