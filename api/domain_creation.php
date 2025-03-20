<?php

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Subscription details
$subscriptionName = 'papidemo.co.zam';
$planName = 'test_plan'; // Replace with the desired plan name
$ipAddress = '41.76.111.78'; // Replace with the IP address you want to assign to the subscription
$subscriptionPassword = 'your_subscription_password'; // Replace with the desired password


// Plesk API endpoint
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// SOAP request XML
$xml = <<<EOT
<packet>
    <webspace>
        <add>
            <gen_setup>
                <name>$subscriptionName</name>
                <ip_address>$ipAddress</ip_address>
                <htype>vrt_hst</htype>
                <password>$subscriptionPassword</password>
            </gen_setup>
            <hosting>
                <vrt_hst>
                    <property>
                        <name>ftp_login</name>
                        <value>ftpuser</value>
                    </property>
                    <property>
                        <name>ftp_password</name>
                        <value>ftppassword</value>
                    </property>
                    <property>
                        <name>php</name>
                        <value>true</value>
                    </property>
                </vrt_hst>
            </hosting>
            <plan-name>$planName</plan-name>
        </add>
    </webspace>
</packet>
EOT;

// Debugging: Output the SOAP request XML
echo "SOAP Request XML:<br>";
echo htmlentities($xml);
echo "<br><br>";

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
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Debugging: Output the API response
echo "API Response:<br>";
echo htmlentities($response);
echo "<br><br>";

// Process the API response
$xmlResponse = simplexml_load_string($response);
$result = $xmlResponse->webspace->add->result;

if ($httpCode === 201 && $result->status == 'ok') {
    echo "Subscription created successfully!";
} else {
    echo "An error occurred: " . $result->errtext;
}

?>
