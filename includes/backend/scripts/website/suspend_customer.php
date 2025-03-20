<?php

error_reporting(0);
ini_set('display_errors', 0);

 // Plesk API settings
 $host = 'jabu.onerserv.co.za';
 $login = 'sabookso';
 $password = 'lOpF5s0cB~28&Q';
 $apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Plesk XML API endpoint for suspending a customer
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// Customer ID you want to suspend
$customerIdToSuspend = $susbcriptionID; // Replace with the actual customer ID

// XML request to suspend a customer
$requestXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.0">
    <customer>
        <set>
            <filter>
                <id>$customerIdToSuspend</id>
            </filter>
            <values>
                <gen_info>
                    <status>16</status> <!-- Use 16 for suspension, 0 for active -->
                </gen_info>
            </values>
        </set>
    </customer>
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
$result = $xmlResponse->customer->set->result;

if ($result->status == 'ok') {
    $customer_suspended = true;
} else {
    $customer_suspended = false;
}

?>
