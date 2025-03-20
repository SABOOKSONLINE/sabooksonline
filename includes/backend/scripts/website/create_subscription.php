<?php
// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';

// Customer details
$customerName = 'John Doe'; // Replace with the customer's name
$customer_Name = 'John_Doe'; // Replace with the customer's name
$customerEmail = 'john@example.com'; // Replace with the customer's email
$customerPassword = '!Emmanuel@1632'; // Replace with the customer's email

// Plesk XML API endpoint for creating a customer
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// XML request to create a customer
$requestXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.0">
    <customer>
        <add>
            <gen_info>
                <cname>$customerName</cname>
                <pname>$customerName</pname>
                <login>$customer_Name</login>
                <passwd>$customerPassword</passwd>
                <status>0</status>
                <email>$customerEmail</email>
            </gen_info>
        </add>
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
$result = $xmlResponse->customer->add->result;

if ($result->status == 'ok') {
    echo "Customer created successfully!";
} else {
    echo "An error occurred: " . $result->errtext;
}
?>
