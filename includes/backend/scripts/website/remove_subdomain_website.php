<?php

error_reporting(0);
    ini_set('display_errors', 0);


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

$domainName = $_POST['domain']; // The main domain where the subdomain exists

include '../select/select_website_data.php';

$request = <<<XML
<packet>
    <subdomain>
        <del>
            <filter>
                <name>$domainName</name>
            </filter>
        </del>
    </subdomain>
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
        if ($xml->subdomain->del->result->status == 'ok') {
             // Database operations
                $stmt = $mysqli->prepare("DELETE FROM plesk_accounts WHERE USERKEY = ?");
                if ($stmt) {
                    $stmt->bind_param("s", $userkey);
                    if ($stmt->execute()) {
                        $stmt->close();

                        include_once 'remove_database.php';

                        echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your data has been deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";

                        $stmt = $mysqli->prepare("DELETE FROM plesk_emails WHERE EMAIL_USERID = ?");
                        if ($stmt) {
                            $stmt->bind_param("s", $userkey);
                            if ($stmt->execute()) {
                                $stmt->close();


                            } else {

                                echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Your website could not be deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";

                            }
                        } else {
                            echo "Error preparing statement for emails deletion: " . $mysqli->error;
                        }
                    } else {
                        echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Your website with domain could not be deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";
                    }
                } else {
                    echo "Error preparing statement for customer deletion: " . $mysqli->error;
                }
        } else {
            //echo "Subdomain removal failed. Error: {$xml->subdomain->remove->result->errtext}";

            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your website'.$domainName.' with domain could not be deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";
        }
    }
}

curl_close($curl);


?>