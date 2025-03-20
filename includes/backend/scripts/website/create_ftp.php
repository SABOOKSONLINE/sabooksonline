<?php



// FTP account details
$ftpUsername = $customerUsername; // Replace with desired FTP username
$ftpPassword = $customerPassword; // Replace with desired FTP password

// Prepare the XML request
$requestXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.9.1">
    <ftp-user>
        <add>
            <name>$customerUsername</name>
            <password>$customerPassword</password>
            <home/>
            <quota>0</quota>
            <permissions>
                <write>true</write>
            </permissions>
            <webspace-id>$subscriptionID</webspace-id>
        </add>
    </ftp-user>
</packet>
EOT;

// Set cURL options
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$host:8443/enterprise/control/agent.php");
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
$result = $xmlResponse->ftp_user->add->result;

?>
