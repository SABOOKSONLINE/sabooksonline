<?php
/**
 * Yoco Payment Return Handler
 * 
 * Handles payment return from Yoco payment gateway.
 * Verifies payment status and updates order accordingly.
 */

session_start();
require_once __DIR__ . '/../../Config/connection.php';
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../controllers/CartController.php';
require_once __DIR__ . '/../../helpers/PaymentHelper.php';

// Initialize variables
$isLocal = isLocalhost();
$checkoutId = getCheckoutId();
$orderId = getPendingOrderId();

// Comprehensive logging - always log (not just localhost)
error_log("=== PAYMENT RETURN WORKFLOW START ===");
error_log("Timestamp: " . date('Y-m-d H:i:s'));
error_log("HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'not set'));
error_log("Is Localhost: " . ($isLocal ? 'YES' : 'NO'));

// Session logging
error_log("--- SESSION DATA ---");
error_log("Session ID: " . session_id());
error_log("pending_order_id: " . ($_SESSION['pending_order_id'] ?? 'NOT SET'));
error_log("yoco_checkout_id: " . ($_SESSION['yoco_checkout_id'] ?? 'NOT SET'));
error_log("ADMIN_ID: " . ($_SESSION['ADMIN_ID'] ?? 'NOT SET'));
error_log("ADMIN_USERKEY: " . ($_SESSION['ADMIN_USERKEY'] ?? 'NOT SET'));
error_log("Full Session: " . json_encode($_SESSION));

// Request logging
error_log("--- REQUEST DATA ---");
error_log("GET Params: " . json_encode($_GET));
error_log("POST Params: " . json_encode($_POST));
error_log("Retrieved checkoutId: " . ($checkoutId ?? 'NULL'));
error_log("Retrieved orderId: " . ($orderId ?? 'NULL'));

// Debug logging for localhost
logDebug("Payment Return - Initial", [
    'get_params' => $_GET,
    'checkout_id' => $checkoutId,
    'order_id' => $orderId,
    'session_checkout_id' => $_SESSION['yoco_checkout_id'] ?? 'not set'
]);

// Validate checkout ID is present
if (!$checkoutId) {
    error_log("❌ VALIDATION FAILED: checkoutId is required but not found");
    error_log("Cannot verify payment without checkoutId");
    
    // Mark order as failed if we can't verify payment
    if ($orderId) {
        error_log("Attempting to mark order #$orderId as 'failed' due to missing checkoutId");
        $updateResult = updateOrderPaymentStatus($conn, $orderId, 'failed');
        error_log("Update result: " . ($updateResult ? 'SUCCESS' : 'FAILED'));
    } else {
        error_log("⚠️ No orderId available - cannot update order status");
    }
    
    error_log("=== PAYMENT RETURN WORKFLOW END (FAILED - NO CHECKOUT ID) ===");
    header('Location: /payment/cancel?error=verification_failed');
    exit;
}

error_log("✅ Checkout ID validated: $checkoutId");

// Verify payment with Yoco API
error_log("--- VERIFYING PAYMENT WITH YOCO API ---");
error_log("Checkout ID: $checkoutId");
error_log("Is Localhost: " . ($isLocal ? 'YES' : 'NO'));

$verification = verifyYocoPayment($checkoutId, $isLocal);

error_log("Verification Result: " . ($verification['success'] ? 'SUCCESS' : 'FAILED'));
if (!$verification['success']) {
    error_log("Verification Error: " . ($verification['error'] ?? 'Unknown error'));
}

if (!$verification['success']) {
    // API verification failed - update order status to 'failed'
    error_log("❌ YOCO API VERIFICATION FAILED");
    error_log("Error: " . $verification['error']);
    
    if ($orderId) {
        error_log("Attempting to update order #$orderId to 'failed' status");
        $updateResult = updateOrderPaymentStatus($conn, $orderId, 'failed');
        error_log("Database update result: " . ($updateResult ? 'SUCCESS ✅' : 'FAILED ❌'));
        
        // Verify the update
        $verifyStmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
        $verifyStmt->bind_param("i", $orderId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();
        $verifyData = $verifyResult->fetch_assoc();
        $verifyStmt->close();
        error_log("Verified DB status after update: " . ($verifyData['payment_status'] ?? 'NOT FOUND'));
        
        // Send failure email notification
        $cartModel = new CartModel($conn);
        $orderDetails = $cartModel->getOrderDetails($orderId, $_SESSION['ADMIN_ID']);
        if ($orderDetails) {
            $address = $cartModel->getDeliveryAddress($_SESSION['ADMIN_ID']);
            sendPaymentNotificationEmails($orderDetails, $address, 'failure', 'verification_failed');
            error_log("Failure email sent");
        } else {
            error_log("⚠️ Could not get order details for email");
        }
    } else {
        error_log("⚠️ No orderId available - cannot update order status");
    }
    
    clearPaymentSession(true);
    error_log("=== PAYMENT RETURN WORKFLOW END (VERIFICATION FAILED) ===");
    header('Location: /payment/cancel');
    exit;
}

error_log("✅ Yoco API verification successful");

// Check if payment was successful
error_log("--- CHECKING PAYMENT STATUS ---");
$checkoutData = $verification['data'];
error_log("Yoco Checkout Data Status: " . ($checkoutData['status'] ?? 'NOT SET'));
error_log("Full Checkout Data: " . json_encode($checkoutData));

$paymentSuccessful = isPaymentSuccessful($checkoutData);
error_log("Payment Successful Check: " . ($paymentSuccessful ? 'YES ✅' : 'NO ❌'));

if ($paymentSuccessful) {
    error_log("--- PAYMENT SUCCESSFUL - UPDATING ORDER ---");
    
    // Payment successful - update order status
    if ($orderId) {
        error_log("Order ID available: $orderId");
        error_log("Attempting to update order #$orderId to 'paid' status");
        
        // Get current status before update
        $beforeStmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
        $beforeStmt->bind_param("i", $orderId);
        $beforeStmt->execute();
        $beforeResult = $beforeStmt->get_result();
        $beforeData = $beforeResult->fetch_assoc();
        $beforeStmt->close();
        error_log("Payment status BEFORE update: " . ($beforeData['payment_status'] ?? 'NOT FOUND'));
        
        $updateResult = updateOrderPaymentStatus($conn, $orderId, 'paid');
        error_log("Database update attempt result: " . ($updateResult ? 'SUCCESS ✅' : 'FAILED ❌'));
        
        // Verify the update immediately
        $verifyStmt = $conn->prepare("SELECT payment_status, updated_at FROM orders WHERE id = ?");
        $verifyStmt->bind_param("i", $orderId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();
        $verifyData = $verifyResult->fetch_assoc();
        $verifyStmt->close();
        
        error_log("Payment status AFTER update: " . ($verifyData['payment_status'] ?? 'NOT FOUND'));
        error_log("Order updated_at timestamp: " . ($verifyData['updated_at'] ?? 'NOT SET'));
        
        if ($verifyData['payment_status'] === 'paid') {
            error_log("✅ CONFIRMED: Order #$orderId payment_status is now 'paid'");
        } else {
            error_log("❌ WARNING: Order #$orderId payment_status is NOT 'paid' (current: " . ($verifyData['payment_status'] ?? 'NULL') . ")");
        }
        
        if ($updateResult) {
            error_log("--- SENDING SUCCESS EMAIL ---");
            // Get order details for email notification
            $cartModel = new CartModel($conn);
            $orderDetails = $cartModel->getOrderDetails($orderId, $_SESSION['ADMIN_ID']);
            
            if ($orderDetails) {
                error_log("Order details retrieved for email");
                $address = $cartModel->getDeliveryAddress($_SESSION['ADMIN_ID']);
                sendPaymentNotificationEmails($orderDetails, $address, 'success');
                error_log("Success email sent");
            } else {
                error_log("⚠️ Could not get order details for email");
            }
        } else {
            error_log("❌ Database update failed - email not sent");
        }
    } else {
        error_log("❌ CRITICAL: No orderId available - cannot update order to 'paid'");
        error_log("Session pending_order_id: " . ($_SESSION['pending_order_id'] ?? 'NOT SET'));
    }
    
    clearPaymentSession(false);
    error_log("=== PAYMENT RETURN WORKFLOW END (SUCCESS) ===");
} else {
    error_log("--- PAYMENT NOT SUCCESSFUL - UPDATING TO FAILED ---");
    
    // Payment failed or not successful - update order status to 'failed'
    if ($orderId) {
        error_log("Order ID available: $orderId");
        error_log("Attempting to update order #$orderId to 'failed' status");
        
        $updateResult = updateOrderPaymentStatus($conn, $orderId, 'failed');
        error_log("Database update result: " . ($updateResult ? 'SUCCESS ✅' : 'FAILED ❌'));
        
        // Verify the update
        $verifyStmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
        $verifyStmt->bind_param("i", $orderId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();
        $verifyData = $verifyResult->fetch_assoc();
        $verifyStmt->close();
        error_log("Verified DB status after update: " . ($verifyData['payment_status'] ?? 'NOT FOUND'));
        
        // Send failure email notification
        $cartModel = new CartModel($conn);
        $orderDetails = $cartModel->getOrderDetails($orderId, $_SESSION['ADMIN_ID']);
        
        if ($orderDetails) {
            $address = $cartModel->getDeliveryAddress($_SESSION['ADMIN_ID']);
            sendPaymentNotificationEmails($orderDetails, $address, 'failure', 'failed');
            error_log("Failure email sent");
        } else {
            error_log("⚠️ Could not get order details for email");
        }
    } else {
        error_log("⚠️ No orderId available - cannot update order status");
    }
    
    clearPaymentSession(false);
    error_log("=== PAYMENT RETURN WORKFLOW END (PAYMENT FAILED) ===");
    header('Location: /payment/cancel');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful</title>

<style>
    body {
        margin: 0;
        padding: 0;
        background: rgba(0,0,0,0.75);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        font-family: Arial, sans-serif;
    }

    .modal-box {
        background: #ffffff;
        padding: 40px 30px;
        border-radius: 16px;
        width: 380px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.25);
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .success-check {
        width: 90px;
        height: 90px;
        background: #28a745;
        border-radius: 50%;
        margin: 0 auto 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: popIn 0.4s ease;
    }

    @keyframes popIn {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-check svg {
        width: 55px;
        height: 55px;
        fill: #fff;
    }

    h2 {
        margin: 0;
        font-size: 26px;
        font-weight: bold;
    }

    p {
        margin-top: 10px;
        color: #333;
        font-size: 15px;
    }

    #continueBtn {
        margin-top: 25px;
        padding: 12px 20px;
        background: #28a745;
        border: none;
        color: #fff;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: 0.2s;
    }

    #continueBtn:hover {
        background: #218838;
    }
</style>
</head>

<body>

<div class="modal-box">
    <div class="success-check">
        <!-- SVG TICK -->
        <svg viewBox="0 0 24 24">
            <path d="M9 16.2l-3.5-3.5L4 14.2l5 5 11-11-1.4-1.4z"></path>
        </svg>
    </div>

    <h2>Thank You!</h2>
    <p>Your purchase has been completed successfully.</p>
    <?php 
    // Show debug info for localhost testing
    if ($isLocal && isset($orderId)): 
        // Verify the order was actually updated
        $verifyStmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
        $verifyStmt->bind_param("i", $orderId);
        $verifyStmt->execute();
        $verifyResult = $verifyStmt->get_result();
        $orderStatus = $verifyResult->fetch_assoc();
        $verifyStmt->close();
    ?>
        <div style="background: #f0f0f0; padding: 15px; border-radius: 8px; margin: 15px 0; font-size: 13px;">
            <strong>🧪 Localhost Test Mode:</strong><br>
            Order ID: <?= $orderId ?><br>
            Checkout ID: <?= htmlspecialchars($checkoutId ?? 'not set') ?><br>
            Payment Status: <strong style="color: <?= ($orderStatus['payment_status'] ?? '') === 'paid' ? '#28a745' : '#dc3545' ?>">
                <?= strtoupper($orderStatus['payment_status'] ?? 'unknown') ?>
            </strong><br>
            <?php if (($orderStatus['payment_status'] ?? '') === 'paid'): ?>
                ✅ <strong>SUCCESS!</strong> Order payment status has been updated to PAID.
            <?php else: ?>
                ⚠️ Payment status is: <?= htmlspecialchars($orderStatus['payment_status'] ?? 'not set') ?><br>
                <?php if ($checkoutId): ?>
                    <button onclick="manualUpdatePayment()" style="margin-top: 10px; padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        🔄 Manually Update to Paid (Test)
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <script>
        function manualUpdatePayment() {
            if (confirm('Manually update order #<?= $orderId ?> payment status to PAID? (Test only)')) {
                fetch('/payment/manual-update-status', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({order_id: <?= $orderId ?>, status: 'paid'})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Payment status updated to PAID!');
                        location.reload();
                    } else {
                        alert('Failed: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(err => {
                    alert('Error: ' + err.message);
                });
            }
        }
        </script>
    <?php endif; ?>
    <button id="continueBtn">Continue</button>
</div>

<script>
document.getElementById("continueBtn").onclick = function() {
    window.location.href = "/dashboards/bookshelf";
};
</script>

</body>
</html>
