<?php


// Plesk API endpoint for creating a database
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// XML-RPC request to create a database
$requestXml = '<?xml version="1.0" encoding="UTF-8"?>
<packet>
    <database>
        <add-db>
            <webspace-id>' . $subscriptionID . '</webspace-id>
            <name>' . $databaseName . '</name>
            <type>' . $dbType . '</type>
        </add-db>
    </database>
</packet>';

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
$result = $xmlResponse->database->{'add-db'}->result;

if ($result->status == 'ok') {
    $database_success = true;

    $databaseID = (int)$result->id;

   // echo 'All works!';

} else {
    $database_success = false;
   echo "An error occurred: " . $result->errtext;

}

?>
