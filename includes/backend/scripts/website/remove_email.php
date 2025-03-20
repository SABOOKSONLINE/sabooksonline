<?php

session_start();

include '../../../database_connections/sabooks_plesk.php';

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$username = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

// Plesk XML API endpoint for deleting a customer
$apiUrl = "https://$host:8443/enterprise/control/agent.php";

// Plesk API URL
$pleskUrl = $apiUrl;

// Assuming you have defined the USERKEY value you want to use for the query
$userkey = $_SESSION['ADMIN_USERKEY'];

// Prepare the SELECT statement
$query = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";

// Prepare and bind the parameter
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $userkey);

// Execute the prepared statement
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Check if any rows are found
if ($result->num_rows > 0) {
    // Fetch and display the rows
    while ($row = $result->fetch_assoc()) {
        $domain_id = $row['DOMAIN_ID'];
    }

    $domain = true;

} else {
    $domain = false;
}

// Email account to delete
$emailName = $_POST['email_name']; // Replace with the email address you want to delete
$emailName_full = $_POST['email_name']; // Replace with the email address you want to delete

// Split the email address using "@" as the delimiter
$emailParts = explode('@', $emailName);

// Take the part before the "@" symbol
$emailName = $emailParts[0];


// Construct XML request to delete email
$xmlRequest = <<<XML
<packet>
    <mail>
        <remove>
            <filter>
                <site-id>$domain_id</site-id>
                <name>$emailName</name>
            </filter>
        </remove>
    </mail>
</packet>
XML;

// Set cURL options
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $pleskUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: text/xml',
    'HTTP_AUTH_LOGIN: ' . $username,
    'HTTP_AUTH_PASSWD: ' . $password,
]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);

// Execute cURL request
$response = curl_exec($ch);

// Close cURL session
curl_close($ch);

// Process API response
$xmlResponse = simplexml_load_string($response);

if ($xmlResponse->mail->remove->result->status == 'ok') {
    // Successful customer deletion from Plesk

    // Database operations
   
            $stmt = $mysqli->prepare("DELETE FROM plesk_emails WHERE EMAIL_ACCOUNT = ?");
            if ($stmt) {
                $stmt->bind_param("s", $emailName_full);
                if ($stmt->execute()) {
                    $stmt->close();

                    echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your email has been deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";
                } else {
                    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your email could not be deleted!".$emailName."',showConfirmButton: false,timer: 6000});</script>";
                }  
            } else {
                //echo "Error preparing statement for emails deletion: " . $mysqli->error;

                echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your email could not be deleted!".$emailName."',showConfirmButton: false,timer: 6000});</script>";
            }
        
   
} else {
    // Error in customer deletion from Plesk
    echo "<script>Swal.fire({position: 'top-end',icon: 'danger',title: 'Your email address could not be deleted!".$emailName."',showConfirmButton: false,timer: 6000});</script>";

}

?>
