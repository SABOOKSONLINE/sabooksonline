<?php

// Plesk API URL
$pleskUrl = 'https://jabu.onerserv.co.za:8443/enterprise/control/agent.php';

// Construct XML request to set the password
$xmlRequestPassword = <<<XML
<?xml version="1.0"?>
<packet>
    <mail>
        <create>
            <filter>
                <site-id>$domain_id</site-id>
                <mailname>
                    <name>$emailName</name>
                    <mailbox>
                        <enabled>true</enabled>
                        <quota>10485760</quota> <!-- 10 MB in bytes -->
                    </mailbox>
                    <password>
                      <value>$newPassword</value>
                    </password>
                </mailname>
            </filter>
        </create>
    </mail>
</packet>
XML;

// Initialize cURL session for setting the password
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pleskUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: text/xml',
    'HTTP_AUTH_LOGIN: ' . $username_plesk,
    'HTTP_AUTH_PASSWD: ' . $password_plesk,
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequestPassword);
$response = curl_exec($ch);
curl_close($ch);

// Process API response for setting the password
$xmlResponsePassword = simplexml_load_string($response);

if ($xmlResponsePassword->mail->create->result->status == 'ok') {

    $client_success = true;

   
} else {
    //echo 'Error setting password: ' . $xmlResponsePassword->mail->create->result->errtext;

    $client_success = false;

    //echo $response;
    //echo $domain_id;
}
?>
