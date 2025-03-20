<?php


// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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


$amount = $_GET['amount'];
$token = $_GET['token'];
// PayFast merchant credentials
$merchantId = '16107155'; // Replace with your actual merchant ID
$merchantKey = '4aguztdacw570'; // Replace with your actual merchant key
$passPhrase = 'OnerHosting1632'; // Replace with your passphrase (if set)
$subscriptionId = $token; // Replace with actual subscription ID         

// PayFast API URL for the ad-hoc subscription payment
$payfastUrl = 'https://api.payfast.co.za/subscriptions/' . $subscriptionId . '/adhoc';

// Generate timestamp
$timestamp = date('Y-m-d\TH:i:s'); // Example: 2024-09-05T12:00:01

// Payment amount
$amount = $amount * 100;      
                 
$item = "Token Capture v2";

// Prepare the data that will be used in the signature generation
$signatureData = array(
    'merchant-id' => $merchantId,
    'version' => 'v1',
    'timestamp' => $timestamp,
    'amount' => $amount,
    'item_name' => $item
);

// Generate signature using improved method
function generateApiSignature($pfData, $passPhrase = null) {
    if ($passPhrase !== null) {
        $pfData['passphrase'] = $passPhrase;
    }

    // Sort the array by key, alphabetically
    ksort($pfData);

    // Create parameter string
    $pfParamString = http_build_query($pfData);
    return md5($pfParamString);
}

// Generate the signature
$signature = generateApiSignature($signatureData, $passPhrase);

// Set up headers
$headers = array(
    'merchant-id: ' . $merchantId,
    'version: v1',
    'timestamp: ' . $timestamp,
    'signature: ' . $signature,
    'Content-Type: application/x-www-form-urlencoded'
);      

// cURL setup
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $payfastUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('amount' => $amount, 'item_name' => $item))); // Add the amount and item_name parameter
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute cURL request
$response = curl_exec($ch);
       

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Decode JSON response
    $responseData = json_decode($response, true);

    if (isset($responseData['code'])) {
        if ($responseData['code'] == 200 && $responseData['status'] == 'success') {
            // Handle successful response
  

             // Receive and process payment notification from PayFast
                // Parse and validate PayFast notification data
                // Verify the payment details (e.g., signature verification)
                // Update the payment status in the payments table
      
                $userkey = $_GET['userid'];
                $orderamount = $_GET['amount'];
                $item_name = $_GET['item'];
                $token = $_GET['token'];
                $cycle = '';
                $subscription = $_GET['item'];       
                $payment_id = $_GET['id'];       
                $status = 'Paid';                

                // Example code to update payment status in the payments table:
                $invoice_id = $userkey;
                $payment_date = date("Y-m-d");
                

                $sql = "UPDATE payments SET payment_date = ?, amount_paid = ?, token = ?, status = ? WHERE payment_id = ?";    

                $stmt = $conn->prepare($sql);        
                $stmt->bind_param("sssss", $payment_date, $orderamount, $token, $status, $payment_id);
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
                            header("Location: page-dashboard-services-plan?result=Success");
                        } else {
                            header("Location: page-dashboard-services-plan?result=Failed");
                        }
                    } else {
                        echo "User not found in the database";
                    } 

                } else {
                    echo "Error preparing the SQL statement: " . $mysqli->error;
                }


        } elseif ($responseData['code'] == 400 && $responseData['status'] == 'failed') {
            // Handle unsuccessful response
            echo "Transaction failed! \n";
            echo "Error Message: " . $responseData['data']['message'] . " (Response Code: " . $responseData['data']['response'] . ")\n";
        } else {
            // Handle unexpected status
            echo "Unexpected response: " . $response;
        }
    } else {
        echo "Unexpected response format: " . $response;
    }
}

// Close cURL session
curl_close($ch);
?>
   