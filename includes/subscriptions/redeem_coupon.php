<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Set $type based on session
$type = $_SESSION['upgrade'] ?? '';
$_SESSION['payment'] = 'true';

// Database connection
$dsn = 'mysql:host=localhost;dbname=Sibusisomanqa_update_3';
$username = 'sabooks_library';
$password = '1m0g7mR3$';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if coupon code is provided
if (isset($_POST['coupon_code'])) {  
    $couponCode = $_POST['coupon_code'];

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare("SELECT benefit_description FROM sabo_coupons WHERE code = :code LIMIT 1");
    $stmt->execute([':code' => $couponCode]);
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if coupon was found
    if ($coupon) {
        // Extract benefit description
        $benefitDescription = htmlspecialchars($coupon['benefit_description']);

        // Assuming benefit_description contains a number of days in the format "30 days"
        if (preg_match('/(\d+)/', $benefitDescription, $matches)) {
            $days = (int)$matches[1];

            // Create a DateTime object for the current date
            $currentDate = new DateTime();

            // Add the number of days
            $currentDate->add(new DateInterval("P{$days}D"));

            // Format the new date
            $next_payment_date = $currentDate->format('Y-m-d');
        } else {
            echo "Error: Benefit description does not contain a valid number of days.";
            $next_payment_date = null;
        }
    } else {
        echo "Error: Coupon code not found.";
        $next_payment_date = null;
    }
} else {
    echo "Coupon code not set.";
    $next_payment_date = null;
}

// Update payment and subscription details
function updatePaymentAndSubscription($pdo, $userkey, $orderamount, $item_name, $token, $type, $next_payment_date) {
    $pdo->beginTransaction();
    try {
        $payment_date = date("Y-m-d");
        $status = 'Paid';    

        // Insert payment details
        $stmt = $pdo->prepare("INSERT INTO payments (invoice_id, payment_date, amount_paid, token, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userkey, $payment_date, $orderamount, $token, $status]);

        // Update or insert subscription details
        $stmt = $pdo->prepare("SELECT * FROM subscriptions_p WHERE user_id = ? AND status = 'Active'");
        $stmt->execute([$userkey]);
        $subscription = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($subscription) {
            $stmt = $pdo->prepare("UPDATE subscriptions_p SET next_invoice_date = ?, amount = ?, plan = ? WHERE user_id = ?");
            $stmt->execute([$next_payment_date, $orderamount, $type, $userkey]);
        } else {
            $frequency = ''; // Set frequency value
            $stmt = $pdo->prepare("INSERT INTO subscriptions_p (user_id, start_date, next_invoice_date, status, frequency, amount, plan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userkey, $payment_date, $next_payment_date, 'Active', $frequency, $orderamount, $type]);    
        }
  
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Transaction failed: " . $e->getMessage());
        return false;
    }
}

// Handle payment and subscription update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userid'], $_POST['amount'], $_POST['item'], $_POST['coupon_code'])) {
    $payment_date = date("Y-m-d");

    $userkey = $_POST['userid'];
    $orderamount = $_POST['amount'];
    $item_name = $_POST['item'];
    $token = $_POST['coupon_code'];     

    if (updatePaymentAndSubscription($pdo, $userkey, $orderamount, $item_name, $token, $type, $next_payment_date)) {
        // Send confirmation email to the user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE ADMIN_USERKEY = ?");
        $stmt->execute([$userkey]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);  

        if ($user) {
            $userEmail = $user['ADMIN_EMAIL'];
            $subject = "Payment Confirmation - SA Books Online";
            $headers = "From: noreply@sabooksonline.co.za\r\nContent-Type: text/html; charset=UTF-8\r\n";

            $message = "<p>Your subscription payment has been successfully received. Here are your subscription details:</p>";
            $message .= "<p><b>Subscription Plan:</b> $item_name<br>";
            $message .= "<b>Subscription Amount:</b> $orderamount<br>";
            $message .= "<b>Email Address:</b> $userEmail<br>";
            $message .= "<b>Payment Date:</b> $payment_date<br>";
            $message .= "<b>Next Payment Date:</b> $next_payment_date</p>";
            $message .= "<p><a href='https://sabooksonline.co.za/login'>Go To Dashboard</a></p>";

            mail($userEmail, $subject, $message, $headers);

            header("Location: ../backend/scripts/subscription-activate.php?subscription_type=$type");
            exit(); // Ensure script execution is stopped after redirection
        } else {
            echo "User not found in the database";
        }       
    } else {
        header("Location: https://sabooksonline.co.za/dashboard/service-plan?result=Failed");
        exit(); // Ensure script execution is stopped after redirection
    }
} else {
    echo "not set";
}
?>
  