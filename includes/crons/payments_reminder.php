<?php
// Set error reporting for debugging (comment out in production)
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

// SQL query to select the last data for each user using mysqli
$sql = "SELECT
            u.ADMIN_USERKEY,
            u.ADMIN_NAME,
            u.ADMIN_EMAIL,
            sp.next_invoice_date,
            sp.amount,
            p.payment_date,
            p.amount_paid
        FROM
            users u
            LEFT JOIN subscriptions_p sp ON u.ADMIN_USERKEY = sp.user_id
            LEFT JOIN payments p ON u.ADMIN_USERKEY = p.invoice_id
        WHERE (u.ADMIN_USERKEY, sp.next_invoice_date, p.payment_date) IN (
            SELECT u.ADMIN_USERKEY, MAX(sp.next_invoice_date), MAX(p.payment_date)
            FROM users u
            LEFT JOIN subscriptions_p sp ON u.ADMIN_USERKEY = sp.user_id
            LEFT JOIN payments p ON u.ADMIN_USERKEY = p.invoice_id
            GROUP BY u.ADMIN_USERKEY
        )";

$result = $conn->query($sql);

if (!$result) {
    die("Error in the SQL query: " . $conn->error);
}

// Fetch and send emails with different actions based on payment status
while ($row = $result->fetch_assoc()) {
    $adminUserName = $row['ADMIN_NAME'];
    $adminUserKey = $row['ADMIN_USERKEY'];
    $userEmail = $row['ADMIN_EMAIL'];
    $nextInvoiceDate = $row['next_invoice_date'];
    $amount = $row['amount'];
    $paymentDate = $row['payment_date'];
    $amountPaid = $row['amount_paid'];

    // Calculate days remaining or days overdue
    $currentTimestamp = time();
    $nextPaymentTimestamp = strtotime($nextInvoiceDate);
    $date1 = new DateTime(date("Y-m-d", $nextPaymentTimestamp));
    $date2 = new DateTime(date("Y-m-d", $currentTimestamp));
    $dateDiff = $date2->diff($date1);
    $daysRemaining = $dateDiff->days;

    // Construct the email message
    $to = $userEmail;
    $headers = "From: noreply@sabooksonline.co.co.za"; // Change this to your email address

    if ($daysRemaining <= 0) {
        // Payment is overdue
        if ($daysRemaining <= -5) {
            // Overdue for more than 5 days, suspend account
            $message = "Hello $adminUserName,\n\nYour payment of $amount is overdue for more than 5 days. As a result, your account has been suspended. To reactivate your account, please make the payment as soon as possible.";
            $message .= "<br><br><b>Payment Date:</b> ".$daysRemaining;
            $message .= "<br><br><b>Next Payment Date:</b> ".$nextInvoiceDate;
        } else {
            // Overdue but less than 5 days, send a reminder
            $message = "Hello $adminUserName,\n\nYour payment of $amount is overdue. Please make the payment as soon as possible to avoid account suspension.";
            $message .= "<br><br><b>Payment Date:</b> ".$daysRemaining;
            $message .= "<br><br><b>Next Payment Date:</b> ".$nextInvoiceDate;
        }
    } elseif ($daysRemaining <= 5) {
        // Payment is upcoming with 5 or fewer days left
        $message = "Hello $adminUserName,\n\nYour payment of $amount is due on $nextInvoiceDate. You have $daysRemaining days left to make the payment. Please make sure your card is active and has funds available for auto deduction.";
        $message .= "<br><br><b>Payment Date:</b> ".$daysRemaining;
        $message .= "<br><br><b>Next Payment Date:</b> ".$nextInvoiceDate;
    }

    // Construct the email message and send it to the user
    $to = $userEmail;
    $reg_name = $adminUserName;
    $subject = "Payment Reminder - SA Books Online";

    $headers = "From: noreply@sabooksonline.co.co.za"; // Change this to your email address

    $button_link = "https://my.sabooksonline.co.za/login";
    $link_text = "Go To Dashboard";
    
    $sub_type = '';
    
    include '../templates/email.php';
    

    // Send the email
    if (mail($to, $subject, $message2, $headers)) {
        echo "Email sent to $userEmail: $subject\n"; 
    } else {
        echo "Email sending failed to $userEmail\n";
    }
}

// Close the database connection
$conn->close();
?>
