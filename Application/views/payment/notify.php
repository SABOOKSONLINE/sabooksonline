<?php
// Acknowledge receipt to PayFast
header('HTTP/1.0 200 OK');
flush();

require_once __DIR__ . '/../../load_env.php';
define('SANDBOX_MODE', filter_var(getenv('PAYFAST_SANDBOX_MODE') ?: 'false', FILTER_VALIDATE_BOOLEAN));
$pfHost = SANDBOX_MODE ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
$pfPassphrase = getenv('PAYFAST_PASSPHRASE') ?: '';


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
    require_once __DIR__ . '/../../handlers/checkoutPayfast.php';

    $amount = floatval($pfData['amount_gross']);
    $orderName = $pfData['item_name'] ?? '';
    $token = $pfData['token'] ?? '';
    $bookId = $pfData['custom_str1'] ?? null; // Can be integer (regular) or string (academic public_key)
    $invoiceId = $pfData['m_payment_id'] ?? '';
    $plan = $pfData['custom_str2'] ?? '';
    $format = $pfData['custom_str3'] ?? '';
    $email = $pfData['email_address'] ?? '';
    $type = $pfData['name_last'] ?? '';
    $paymentDate = date("Y-m-d");
    
    // Calculate end_date based on billing cycle (monthly = 30 days, yearly = 365 days)
    if (strtolower($type) === 'yearly') {
        $endDate = date("Y-m-d", strtotime("+365 days"));
    } else {
        $endDate = date("Y-m-d", strtotime("+30 days"));
    }
    
    $status = 'COMPLETE';

    $logDetails = '';



    if ($plan === 'hardcopy') {
        createOrderAndNotify($format, $shippingFee = 70);    
    }

    elseif ($bookId) {
    // Determine if it's an academic book (string public_key) or regular book (integer ID)
    $isAcademic = !is_numeric($bookId);
    
    // Skip saving academic books to book_purchases - they should go through cart/orders
    if ($isAcademic) {
        echo "⚠️ Academic book purchase detected. Direct PayFast purchases for academic books are disabled.\n";
        echo "Academic books should be purchased via cart checkout (hardcopy) or are free (digital).\n";
    } else {
        // Regular book - save to book_purchases
        $bookIdValue = (int)$bookId;
        
        $sql = "INSERT INTO book_purchases (user_email, book_id, user_key, format, payment_id, amount, payment_status, payment_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssdss", $email, $bookIdValue, $plan, $format, $invoiceId, $amount, $status, $paymentDate);

        if ($stmt->execute()) {
            echo "✅ Book purchase saved.\n";
        } else {
            echo "❌ Book purchase failed: " . $stmt->error . "\n";
        }

        $stmt->close();
    }

    } else {
        // Check for existing payment with token first
        $checkSql = "SELECT * FROM payment_plans WHERE token = ? AND status = 'COMPLETE'";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bind_param("s", $token);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            // This is a recurring payment renewal - update existing payment record and extend end_date
            $existingPlan = $result->fetch_assoc();
            
            // Calculate new end_date based on billing cycle
            if (strtolower($type) === 'yearly') {
                $newEndDate = date("Y-m-d", strtotime("+365 days"));
            } else {
                $newEndDate = date("Y-m-d", strtotime("+30 days"));
            }
            
            // Update existing payment record and extend subscription
            $updateSql = "UPDATE payment_plans 
                         SET invoice_id = ?, 
                             payment_date = ?, 
                             amount_paid = ?, 
                             end_date = ?,
                             renewal_status = 'active',
                             updated_at = NOW() 
                         WHERE token = ?";
            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->bind_param("ssdss", $invoiceId, $paymentDate, $amount, $newEndDate, $token);

            if ($stmtUpdate->execute()) {
                echo "✅ Recurring payment renewal processed - subscription extended.\n";
                
                // Update user subscription to keep it active
                require_once __DIR__ . '/../../models/UserModel.php';
                $userModel = new UserModel($conn);
                $userModel->updateUserPlanMonthly($invoiceId, $plan, $type);
            } else {
                echo "❌ Payment update failed: " . $stmtUpdate->error . "\n";
            }
            $stmtUpdate->close();

        } else {

            $insertSql = "INSERT INTO payment_plans (
                            user_email, 
                            end_date,
                            invoice_id, 
                            payment_date, 
                            amount_paid, 
                            token, 
                            status, 
                            plan_name, 
                            renewal_status, 
                            is_recurring
                        ) VALUES (?,?, ?, ?, ?, ?, ?, ?, 'active', ?)";
            
            $isRecurring = strtolower($type) === 'monthly' ? 1 : 0;

            $stmtInsert = $conn->prepare($insertSql);
            $stmtInsert->bind_param("ssssdsssi", $email, $endDate, $invoiceId, $paymentDate, $amount, $token, $status, $orderName, $isRecurring);

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
    http_response_code(200);
} else {
    http_response_code(400);
}
?>
