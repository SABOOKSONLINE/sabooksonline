<?php

session_start();

include '../../../database_connections/sabooks_plesk.php';

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

// XML request to delete a customer
$requestXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.9.1"> <!-- Use the appropriate protocol version -->
    <customer>
        <del>
            <filter>
                <id>$customerIdToDelete</id>
            </filter>
        </del>
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
$result = $xmlResponse->customer->del->result;

if ($result->status == 'ok') {
    // Successful customer deletion from Plesk

    // Database operations
    $stmt = $mysqli->prepare("DELETE FROM plesk_accounts WHERE USERKEY = ?");
    if ($stmt) {
        $stmt->bind_param("s", $userkey);
        if ($stmt->execute()) {
            $stmt->close();

            $stmt = $mysqli->prepare("DELETE FROM plesk_emails WHERE EMAIL_USERID = ?");
            if ($stmt) {
                $stmt->bind_param("s", $userkey);
                if ($stmt->execute()) {
                    $stmt->close();

                    echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your data has been deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-websites');},3000);</script>";
                } else {
                    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your emails could not be deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-websites');},3000);</script>";
                }
            } else {
                echo "Error preparing statement for emails deletion: " . $mysqli->error;
            }
        } else {
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your website with domain could not be deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-websites');},3000);</script>";
        }
    } else {
        echo "Error preparing statement for customer deletion: " . $mysqli->error;
    }
} else {
    // Error in customer deletion from Plesk
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your website with domain could not be deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-websites');},3000);</script>";
}

?>
