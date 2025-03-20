<?php



// Plesk XML API endpoint for adding a database user
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// XML request to add a database user with all privileges (db_owner role)
$requestXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.9.1">
    <database>
        <add-db-user>
            <db-id>$databaseId</db-id>
            <login>$dbUsername</login>
            <password>$dbUserPassword</password>
            <role>readWrite</role> <!-- Use 'db_owner' role for all privileges -->
        </add-db-user>
    </database>
</packet>
EOT;

// Set cURL options
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: text/xml',
    'HTTP_AUTH_LOGIN: ' . $login,
    'HTTP_AUTH_PASSWD: ' . $password,
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXml);

// Perform the cURL request
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Process the API response
$xmlResponse = simplexml_load_string($response);
$result = $xmlResponse->database->{'add-db-user'}->result;

if ($result->status == 'ok') {
    $user_success = true;
} else {
    $user_success = false;

    //echo "An error occurred: " . $result->errtext;
}
?>
