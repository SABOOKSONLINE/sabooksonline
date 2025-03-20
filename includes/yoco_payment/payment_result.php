<?php
    // Tell Payfast that this page is reachable by triggering a header 200
    header('HTTP/1.0 200 OK');
    flush();

    session_start();

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

        $orderNumber = '999';
        $userkey = $_SESSION['ADMIN_USERKEY'];
        $orderamount = '999';
        $item_name = '999';
        $cycle = '999';
        $token = '999';
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

        $sql = "INSERT INTO subscriptions_p (user_id, start_date, next_invoice_date, status, frequency, amount, plan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $userkey, $start_date, $cycle, $status, $frequency, $orderamount, $item_name);
        $stmt->execute();

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
  
                $button_link = "https://my.sabooksonline.co.za/login";
                $link_text = "Go To Dashboard";
                
                $sub_type = $subscription;
                
                include '../templates/email.php';

                // Send the email
                if (mail($to, $subject, $message2, $headers)) {
                    echo "Email sent to $userEmail: Payment confirmation\n";

                    header("Location: subscription_verify.php");

                } else {
                    echo "Email sending failed to $userEmail\n";
                }
            } else {
                echo "User not found in the database";
            } 

        } else {
            echo "Error preparing the SQL statement: " . $mysqli->error;
        }

    // Close the database connection
    $conn->close();

?>
