<?php
// Replace with your Plesk server URL, username, and password
$pleskServer = 'https://jabu.onerserv.co.za:8443';
$username = 'admin';
$password = '!#Mtimande@1632';

// API endpoints for creating a database and a user
$createDatabaseEndpoint = $pleskServer . '/api/v2/databases';
$createUserEndpoint = $pleskServer . '/api/v2/db-users';

// Database and user details
$databaseName = 'new_databases';
$username = 'new_user';
$passwordForUser = 'user_password';

// Create the database
$createDatabasePayload = [
    'type' => 'mysql', // Replace with the appropriate database type
    'name' => $databaseName,
    'subscriptionId' => 32, // Replace with the subscription ID
];

$databaseResponse = makeApiRequest($createDatabaseEndpoint, $createDatabasePayload, $username, $password, 'POST');

// Create the user with all privileges
$createUserPayload = [
    'dbServerId' => 1, // Replace with the appropriate DB server ID
    'name' => $username,
    'password' => $passwordForUser,
    'databaseId' => $databaseResponse['id'], // Use the database ID from the response
    'permissions' => 'ALL PRIVILEGES',
];

$userResponse = makeApiRequest($createUserEndpoint, $createUserPayload, $username, $password, 'POST');

// Output the results
if ($databaseResponse['status'] === 'success' && $userResponse['status'] === 'success') {
    echo "Database and user with all privileges created successfully!";
} else {
    echo "An error occurred.";
}

// Helper function to make API requests
function makeApiRequest($url, $data, $username, $password, $method) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
