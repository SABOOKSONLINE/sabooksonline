<?php
// Tell Payfast that this page is reachable by triggering a header 200
header('HTTP/1.0 200 OK');
flush();

define('SANDBOX_MODE', true);
$pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

// Posted variables from ITN
$pfData = $_POST;

// Strip any slashes in data
foreach ($pfData as $key => $val) {
    $pfData[$key] = stripslashes($val);
}

// Convert posted variables to a string
$pfParamString = '';
foreach ($pfData as $key => $val) {
    if ($key !== 'signature') {
        $pfParamString .= $key . '=' . urlencode($val) . '&';
    } else {
        break;
    }
}
$pfParamString = substr($pfParamString, 0, -1);

//VERIFY THE SIGNATURE
function pfValidSignature($pfData, $pfParamString, $pfPassphrase = 'SABooksOnline2021') {
    // Calculate security signature
    if ($pfPassphrase === null) {
        $tempParamString = $pfParamString;
    } else {
        $tempParamString = $pfParamString . '&passphrase=' . urlencode($pfPassphrase);
    }

    $signature = md5($tempParamString);
    return ($pfData['signature'] === $signature);
}

//Check that the notification has come from a valid Payfast domain
function pfValidIP() {
    // Variable initialization
    $validHosts = array(
        'www.payfast.co.za',
        'sandbox.payfast.co.za',
        'w1w.payfast.co.za',
        'w2w.payfast.co.za',
    );

    $validIps = [];

    foreach ($validHosts as $pfHostname) {
        $ips = gethostbynamel($pfHostname);

        if ($ips !== false)
            $validIps = array_merge($validIps, $ips);
    }

    // Remove duplicates
    $validIps = array_unique($validIps);
    $referrerIp = gethostbyname(parse_url($_SERVER['HTTP_REFERER'])['host']);
    if (in_array($referrerIp, $validIps, true)) {
        return true;
    }
    return false;
}

//Compare payment data
function pfValidPaymentData($cartTotal, $pfData) {
    return abs((float)$cartTotal - (float)$pfData['amount_gross']) < 0.01;
}

//Perform a server request to confirm the details
function pfValidServerConfirmation($pfParamString, $pfHost = 'sandbox.payfast.co.za', $pfProxy = null) {
    // Use cURL (if available)
    if (in_array('curl', get_loaded_extensions(), true)) {
        // Variable initialization
        $url = 'https://' . $pfHost . '/eng/query/validate';

        // Create default cURL object
        $ch = curl_init();

        // Set cURL options - Use curl_setopt for greater PHP compatibility
        // Base settings
        curl_setopt($ch, CURLOPT_USERAGENT, NULL);  // Set user agent
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);      // Return output as a string rather than outputting it
        curl_setopt($ch, CURLOPT_HEADER, false);             // Don't include header in output
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Standard settings
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $pfParamString);
        if (!empty($pfProxy))
            curl_setopt($ch, CURLOPT_PROXY, $pfProxy);

        // Execute cURL
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response === 'VALID') {
            return true;
        }
    }
    return false;
}

// Debugging output (for development purposes)
error_log("Calculated Signature: " . $signature);
error_log("PayFast Signature: " . $pfData['signature']);

// Create a log file for debugging
$logFile = 'notify_log.txt';
$logData = date('Y-m-d H:i:s') . " - Notification received\n";

$orderamount = $pfData['amount_gross'];

$check1 = pfValidSignature($pfData, $pfParamString);
$check2 = pfValidIP();
$check3 = pfValidPaymentData($orderamount, $pfData);
$check4 = pfValidServerConfirmation($pfParamString, $pfHost);

$myFile = fopen('notify.html', 'wb') or die();


$check1 = pfValidSignature($pfData, $pfParamString);
$check1 ? fwrite($myFile , " Signature Valid "): fwrite($myFile , " Signature Failed ");

$check2 = pfValidIP();
$check2 ? fwrite($myFile , " IP Valid "): fwrite($myFile , " IP Not Valid ");

$check3 = pfValidPaymentData($pfData['amount_gross'], $pfData);
$check3 ? fwrite($myFile , "Payment Valid"): fwrite($myFile , " Payment Total Invalid ");

$check4 = pfValidServerConfirmation($pfParamString, $pfHost);
$check4 ? fwrite($myFile , " Server Valid "): fwrite($myFile , " Server Not Confirmed");


if ($check2 && $check3) {
    // All checks have passed, the payment is successful

    $servername = "localhost";
    $username = "sabooks_library";
    $password = "1m0g7mR3$";
    $dbh = "Sibusisomanqa_update_3";
    
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbh);
    
    // Check the database connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    // Receive and process payment notification from PayFast
   // Receive and process payment notification from PayFast
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Parse and validate PayFast notification data
        // Verify the payment details (e.g., signature verification)
        // Update the payment status in the payments table

        $orderNumber = $pfData['item_name'];
        $userkey = $pfData['m_payment_id'];
        $orderamount = $pfData['amount_gross'];
        $item_name = $pfData['item_name'];
        $cycle = $pfData['name_last'];
        $token = $pfData['token'];
        $frequency = '';

        // Example code to update payment status in the payments table:
        $invoice_id = $userkey;
        $payment_date = date("Y-m-d");
        

        $sql = "INSERT INTO payments (invoice_id, payment_date, amount_paid,token) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $invoice_id, $payment_date, $orderamount, $token);
        $stmt->execute();

       
        $start_date = date("Y-m-d");
        //$next_invoice_date = date("Y-m-d", strtotime($cycle));
        $status = 'active';

        // Example code to update or insert subscription based on payment data
        $sqlCheckSubscription = "SELECT * FROM subscriptions_p WHERE user_id = ? AND status = 'active'";
        $stmtCheckSubscription = $conn->prepare($sqlCheckSubscription);
        $stmtCheckSubscription->bind_param("s", $userkey);
        $stmtCheckSubscription->execute();
        $resultCheckSubscription = $stmtCheckSubscription->get_result();

        if ($resultCheckSubscription->num_rows > 0) {
            // Subscription already exists, update the details
            $rowSubscription = $resultCheckSubscription->fetch_assoc();

            $next_invoice_date = $cycle; // Update with the new next invoice date
            $amount = $orderamount; // Update with the new amount
            $plan = $item_name; // Update with the new plan

            $sqlUpdateSubscription = "UPDATE subscriptions_p SET next_invoice_date = ?, amount = ?, plan = ? WHERE user_id = ?";
            $stmtUpdateSubscription = $conn->prepare($sqlUpdateSubscription);
            $stmtUpdateSubscription->bind_param("ssss", $next_invoice_date, $amount, $plan, $userkey);
            $stmtUpdateSubscription->execute();
        } else {
            // Subscription does not exist, insert a new subscription
            $start_date = date("Y-m-d");
            $status = 'active';

            $sqlInsertSubscription = "INSERT INTO subscriptions_p (user_id, start_date, next_invoice_date, status, frequency, amount, plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtInsertSubscription = $conn->prepare($sqlInsertSubscription);
            $stmtInsertSubscription->bind_param("sssssss", $userkey, $start_date, $cycle, $status, $frequency, $orderamount, $item_name);
            $stmtInsertSubscription->execute();  
        }


        /*$sql = "INSERT INTO subscriptions_p (user_id, start_date, next_invoice_date, status, frequency, amount, plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $userkey, $start_date, $cycle, $status, $frequency, $orderamount, $item_name);
        $stmt->execute();*/

        // Prepare the SQL statement
        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the user key to the placeholder
            $stmt->bind_param("s", $userkey);

            // Execute the query
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Check if there is a row returned
            if ($result->num_rows > 0) {
                // Fetch user data
                $user = $result->fetch_assoc();


                // Get user's email or other relevant data
                $userEmail = $user['ADMIN_EMAIL'];
                $userName = $user['ADMIN_NAME'];

                // Construct the email message and send it to the user
                $to = $userEmail;
                $reg_name = $userName;
                $subject = "Payment Confirmation - SA Books Online";
                $headers = "From: noreply@sabooksonline.co.co.za"; // Change this to your email address

                $message = "Your subscription payment has been successfully received. Here are your subscription details:\n\n";
                $message .= "<br><br><b>Subscription Plan:</b> ".$item_name;
                $message .= "<br><br><b>Subscription Amount:</b> ".$orderamount;
                $message .= "<br><br><b>Email Address:</b> ".$userEmail;
                $message .= "<br><br><b>Payment Date:</b> ".$payment_date;
                $message .= "<br><br><b>Next Payment Date:</b> ".$cycle;
  
                $button_link = "https://sabooksonline.co.za/login";
                $link_text = "Go To Dashboard";
                
                $sub_type = $subscription;
                
                include '../templates/email.php';

                // Send the email
                if (mail($to, $subject, $message2, $headers)) {
                    echo "Email sent to $userEmail: Payment confirmation\n";
                } else {
                    echo "Email sending failed to $userEmail\n";
                }
            } else {
                echo "User not found in the database";
            } 

        } else {
            echo "Error preparing the SQL statement: " . $mysqli->error;
        }

    }

    // Close the database connection
    $conn->close();

    // Log successful payment
    $logData .= "Payment Successful\n";
} else {
    // Some checks have failed, check payment manually and log for investigation
    // Log failed payment
    $logData .= "Payment Unsuccessful\n";
}

// Append log data to the log file
file_put_contents($logFile, $logData, FILE_APPEND);

?>
