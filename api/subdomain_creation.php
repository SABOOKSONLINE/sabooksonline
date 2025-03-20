<?php

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Subdomain details
$subdomainName = 'papi'; // Replace with the desired subdomain name
$domainName = 'sabooksonline.africa'; // Replace with the main domain where the subdomain will be created

// Plesk API endpoint
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// SOAP request XML
$xml = <<<EOT
<packet>
    <subdomain>
        <add>
            <parent>$domainName</parent>
            <name>$subdomainName</name>
        </add>
    </subdomain>
</packet>
EOT;

// Set the SOAP request headers
$headers = [
    'Content-Type: text/xml',
    'HTTP_PRETTY_PRINT: TRUE',
    'HTTP_AUTH_LOGIN: ' . $login,
    'HTTP_AUTH_PASSWD: ' . $password,
    'HTTP_X-API-Key: ' . $apiKey,
];

// Perform the SOAP request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$response = curl_exec($ch);
curl_close($ch);

// Process the API response
$xmlResponse = simplexml_load_string($response);
$result = $xmlResponse->subdomain->add->result;

if ($result->status == 'ok') {
    echo "Subdomain created successfully!";
} else {
    echo "An error occurred: " . $result->errtext;
}
