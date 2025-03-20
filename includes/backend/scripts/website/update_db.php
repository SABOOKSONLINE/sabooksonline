<?php
$apiToken = 'afd79b2d-a246-9008-7646-e4c285e82c1b';
$serverBaseUrl = 'https://jabu.onerserv.co.za:8443';

// Define the path to the file you want to modify
$filePath = '/var/www/vhosts/newspay.co.za/httpdocs/includes/db.php';

// Define the new content
$newContent = '<?php
$servername = "localhost";
$username = "text";
$password = "text";
$dbh = "text";

// Create connection
$con = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>';

// API endpoint
$apiEndpoint = "/api/v2/files/{$filePath}";

// Full API URL
$apiUrl = $serverBaseUrl . $apiEndpoint;

// cURL options
$curlOptions = [
    CURLOPT_URL => $apiUrl,
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer {$apiToken}",
        "Content-Type: text/plain",
    ],
    CURLOPT_POSTFIELDS => $newContent,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => true, // Enable SSL verification (recommended in production)
];

// Initialize cURL session
$curl = curl_init();
curl_setopt_array($curl, $curlOptions);

// Execute cURL session
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    echo 'Error: ' . curl_error($curl);
} else {
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    echo "HTTP Response Code: {$httpCode}\n";
    echo "Response Content: {$response}\n";
}

// Close cURL session
curl_close($curl);
?>
