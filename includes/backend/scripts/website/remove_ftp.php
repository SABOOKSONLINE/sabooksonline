<?php

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Plesk XML API endpoint for deleting a customer
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// Customer ID you want to delete
$userkey = $_SESSION['ADMIN_USERKEY']; // Replace with the actual customer ID
$userid = $_SESSION['ADMIN_ID']; // Replace with the actual customer ID

$customerUsername = strtolower(str_replace(' ','_',$_SESSION['ADMIN_NAME'])).'_'.$userid;

// FTP username to be deleted
$ftpUsername = $customerUsername;

// Create the XML request
$request = <<<XML
<packet>
    <ftp-user>
        <del>
            <filter>
                <name>$ftpUsername</name>
            </filter>
        </del>
    </ftp-user>
</packet>
XML;

// Create a cURL request to send the XML data to Plesk API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://$host:8443/enterprise/control/agent.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: text/xml",
    "HTTP_AUTH_LOGIN: $login",
    "HTTP_AUTH_PASSWD: $password",
));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

// Execute cURL request
$response = curl_exec($ch);

// Check for cURL errors and handle response
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);

    //echo '<script>alert("Worked!")</script>';
} else {
    // Process $response here
    //echo $response;
    //echo '<script>alert($response)</script>';
}

// Close cURL connection
curl_close($ch);

?>

