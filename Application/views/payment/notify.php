<?php
// Acknowledge receipt to PayFast
header('HTTP/1.0 200 OK');
flush();

define('SANDBOX_MODE', false);
$pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
$pfPassphrase = 'SABooksOnline2021';

// // Logging setup
// $logFile = __DIR__ . '/pf_log.txt';
// function logMessage($msg) {
//     global $logFile;
//     file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND | LOCK_EX);
// }

// Get posted PayFast data
$pfData = $_POST ?? [];
foreach ($pfData as $key => $val) {
    $pfData[$key] = stripslashes($val);
}

// Build parameter string
$pfParamString = '';
foreach ($pfData as $key => $val) {
    if ($key !== 'signature') {
        $pfParamString .= $key . '=' . urlencode($val) . '&';
    }
}
$pfParamString = rtrim($pfParamString, '&');

// Signature check
function pfValidSignature($pfData, $pfParamString, $pfPassphrase) {
    $tempParamString = $pfPassphrase ? $pfParamString . '&passphrase=' . urlencode($pfPassphrase) : $pfParamString;
    $signature = md5($tempParamString);
    return isset($pfData['signature']) && $pfData['signature'] === $signature;
}

// Get valid PayFast IPs
function getPayfastIps() {
    $hosts = ['www.payfast.co.za', 'sandbox.payfast.co.za', 'w1w.payfast.co.za', 'w2w.payfast.co.za'];
    $ips = [];
    foreach ($hosts as $host) {
        $hostIps = gethostbynamel($host);
        if ($hostIps !== false) {
            $ips = array_merge($ips, $hostIps);
        }
    }
    return array_unique($ips);
}

// Validate IP address
function pfValidIP() {
    $validIps = getPayfastIps();
    $remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
    return in_array($remoteIp, $validIps, true);
}

// Validate amount
function pfValidPaymentData($cartTotal, $pfData) {
    return isset($pfData['amount_gross']) && abs((float)$cartTotal - (float)$pfData['amount_gross']) < 0.01;
}

// Confirm with PayFast server
function pfValidServerConfirmation($pfParamString, $pfHost) {
    if (!in_array('curl', get_loaded_extensions())) return false;

    $url = 'https://' . $pfHost . '/eng/query/validate';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $pfParamString,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return ($response === 'VALID');
}

// Begin processing
// logMessage("ITN received: " . json_encode($pfData));
echo "itn recieved";

$isSignatureValid = pfValidSignature($pfData, $pfParamString, $pfPassphrase);
$isIPValid = pfValidIP();
$isAmountValid = isset($pfData['amount_gross']) ? pfValidPaymentData($pfData['amount_gross'], $pfData) : false;
$isServerConfirmed = pfValidServerConfirmation($pfParamString, $pfHost);

$message1 = "Signature valid: " . ($isSignatureValid ? "YES" : "NO");
$message2 = "IP valid: " . ($isIPValid ? "YES" : "NO");
$message3 = "Amount valid: " . ($isAmountValid ? "YES" : "NO");
$message4 = "Server confirmed: " . ($isServerConfirmed ? "YES" : "NO");

echo $message1 . "<br>";
echo $message2 . "<br>";
echo $message3 . "<br>";
echo $message4 . "<br>";


if ($isSignatureValid && $isIPValid && $isAmountValid && $isServerConfirmed) {
    require_once __DIR__ . '/../../Config/connection.php';


    $amount = floatval($pfData['amount_gross']);
    $orderName = $pfData['item_name'] ?? '';
    $token = $pfData['token'] ?? '';
    $bookId = isset($pfData['custom_str1']) ? (int)$pfData['custom_str1'] : null;
    $invoiceId = $pfData['m_payment_id'] ?? '';
    $plan = $pfData['custom_str2'] ?? '';
    $email = $pfData['email_address'] ?? '';
    $type = $pfData['name_last'] ?? '';
    $paymentDate = date("Y-m-d");
    $status = 'COMPLETE';

    $logDetails = '';

    if ($bookId) {
        // Book purchase
        $sql = "INSERT INTO book_purchases (user_email, book_id, payment_id, amount, payment_status, payment_date)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdss", $email, $bookId, $invoiceId, $amount, $status, $paymentDate);

        if ($stmt->execute()) {
            echo "✅ Book purchase saved.\n";

        } else {
            echo "❌ Book purchase failed: " . $stmt->error . "\n";
        }
        $stmt->close();
    } else {
        // Subscription or site plan
        // Check for existing payment with token first
        $checkSql = "SELECT * FROM payment_plans WHERE token = ? AND status = 'COMPLETE'";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bind_param("s", $token);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            // Update existing payment record
            $updateSql = "UPDATE payment_plans SET invoice_id = ?, payment_date = ?, amount_paid = ? WHERE token = ?";
            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->bind_param("ssds", $invoiceId, $paymentDate, $amount, $token);

            if ($stmtUpdate->execute()) {
                echo "✅ Existing payment updated.\n";
            } else {
                echo "❌ Payment update failed: " . $stmtUpdate->error . "\n";
            }
            $stmtUpdate->close();
        } else {
            // Insert new payment record
            $insertSql = "INSERT INTO payment_plans (invoice_id, payment_date, amount_paid, token, status)
                        VALUES (?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($insertSql);
            $stmtInsert->bind_param("ssdss", $invoiceId, $paymentDate, $amount, $token, $status);

            if ($stmtInsert->execute()) {
                echo "✅ Subscription payment recorded.\n";
            } else {
                echo "❌ Failed to record subscription: " . $stmtInsert->error . "\n";
            }
            $stmtInsert->close();
            
            require_once __DIR__ . '/../../models/UserModel.php';
            $userModel = new UserModel($conn);
            $userModel->updateUserPlanMonthly($invoiceId, $plan, $type);


            
        }
        $stmtCheck->close();

    }

    $conn->close();
    // logMessage($logDetails);
    echo "✅ Payment processed successfully.";

    http_response_code(200);
} else {
    // logMessage("❌ Payment validation failed.");
    echo "❌ Payment validation failed.";
    http_response_code(400);
}
?>
