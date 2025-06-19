<?php
header('Content-Type: application/json');

// Array of authorized API keys
$authorizedKeys = [
    'nola1234',
    'bongo1234',
    // Add more authorized keys here
];

// Get the provided API key from the request
$providedApiKey = $_GET['api_key'];
$userkey = $_GET['userkey'];

// Check if the provided API key is authorized
if (!in_array($providedApiKey, $authorizedKeys)) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Remote database connection parameters
$remoteHost = 'localhost';
$remoteUser = 'sabooks_library';
$remotePass = '1m0g7mR3$';
$remoteDb = 'Sibusisomanqa_website_plesk';


// Create connection
$remoteConn = new mysqli($remoteHost, $remoteUser, $remotePass, $remoteDb);

// Check connection
if ($remoteConn->connect_error) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Remote database connection failed']);
    exit;
}

// Query to fetch data from remote table
$query = "SELECT * FROM plesk_accounts WHERE USERKEY = '$userkey'";
$result = $remoteConn->query($query);

if (!$result) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error fetching data from remote table']);
    exit;
}

// Fetch data as an associative array
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close remote connection
$remoteConn->close();

// Return fetched data as JSON response
echo json_encode($data);
?>
