<?php


error_reporting(0);
    ini_set('display_errors', 0);

//session_start();

//nclude '../../../database_connections/sabooks_plesk.php';

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Plesk XML API endpoint for deleting a customer
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// Customer ID you want to delete
$customerIdToDelete = $_SESSION['ADMIN_CUSTOMER_PLESK']; // Replace with the actual customer ID
$userkey = $_SESSION['ADMIN_USERKEY']; // Replace with the actual customer ID

$domainName = 'sabooksonline.co.za'; // The main domain where the subdomain exists

$request = <<<XML
<packet>
    <database>
        <del-db>
            <filter>
                <id>$database_id</id>
            </filter>
        </del-db>
    </database>
</packet>
XML;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://$host:8443/enterprise/control/agent.php");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    "Content-Type: text/xml",
    "HTTP_AUTH_LOGIN: $login",
    "HTTP_AUTH_PASSWD: $password"
]);

$response = curl_exec($curl);

if ($response === false) {
    echo "Curl error: " . curl_error($curl);
} else {
    $xml = simplexml_load_string($response);
    if ($xml === false) {
        echo "Failed to parse XML response";
    } else {
        if ($xml->database->del->result->status == 'ok') {
            
        } else {
           // echo "Subdomain removal failed. Error: {$xml->subdomain->remove->result->errtext}";
        }
    }
}

curl_close($curl);


?>
