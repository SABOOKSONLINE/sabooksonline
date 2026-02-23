<?php 
session_start();
// Load environment variables first (connection.php also loads it, but ensure it's loaded)
// Use correct relative path: from Application/ to root is ../ (not ../..)
require_once __DIR__ . '/../load_env.php';
require_once __DIR__ . '/Config/connection.php';
require_once __DIR__ . '/controllers/CheckoutController.php';

$userId = $_SESSION['ADMIN_USERKEY'] ?? null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bookId = $_POST['bookId'] ?? null;
    $hardcopy = $_POST['price'] ?? null;
    $audiobookId = $_POST['audiobookId'] ?? null;
    $magazineId = $_POST['magazineId'] ?? null;
    $newspaperId = $_POST['newspaperId'] ?? null;
    $academicBookId = $_POST['academicBookId'] ?? null;



    if (!$userId) {
        $_SESSION['buy'] = 'yes';
        header('Location: /login');
        exit;
    }

    $checkout = new CheckoutController($conn);

    if ($bookId) {
        $checkout->purchaseBook($bookId, $userId);
    }

    elseif ($hardcopy) {
        // Convert price to float and validate
        $price = (float)$hardcopy;
        if ($price <= 0) {
            die("Invalid price amount. Please contact support.");
        }
        
        // Check if this is a cart checkout (has shipping_price)
        $shippingPrice = $_POST['shipping_price'] ?? null;
        $orderId = null;
        
        if ($shippingPrice !== null) {
            // This is a cart checkout - create order first
            require_once __DIR__ . '/models/CartModel.php';
            require_once __DIR__ . '/controllers/CartController.php';
            
            $cartController = new CartController($conn);
            require_once __DIR__ . '/models/UserModel.php';
            $userModel = new userModel($conn);
            $user = $userModel->getUserByNameOrKey($userId);
            $userIdInt = $user['ADMIN_ID'] ?? null;
            
            if ($userIdInt) {
                // Ensure user ID is in session for CartController
                if (!isset($_SESSION['ADMIN_ID'])) {
                    $_SESSION['ADMIN_ID'] = $userIdInt;
                }
                
                // Create order before payment
                $orderId = $cartController->createOrder($userIdInt);
                if ($orderId) {
                    // Update order totals with actual shipping and payment method
                    $shippingFee = (float)$shippingPrice;
                    $cartController->updateOrderTotals($orderId, $price, $shippingFee, 'yoco');
                    
                    // Store order ID in session for verification on return
                    $_SESSION['pending_order_id'] = $orderId;
                } else {
                    die("Failed to create order. Please try again.");
                }
            } else {
                die("User not found. Please log in again.");
            }
        }
        
        $checkout->purchase($price, $userId, false, $orderId ?? null);
    }
     elseif ($audiobookId) {
        $checkout->purchaseBook($audiobookId, $userId, 'Audiobook');
    }

    elseif ($magazineId) {
        $checkout->purchaseMedia($magazineId, $userId, 'Magazine');
    }
    elseif ($newspaperId) {
        $checkout->purchaseMedia($newspaperId, $userId, 'Newspaper');
    }
    elseif ($academicBookId) {
        // Direct PayFast purchase for academic books is disabled
        // Academic books should be purchased via cart checkout (hardcopy) or are free (digital)
        header('Location: /books/academic/' . urlencode($academicBookId) . '?error=direct_purchase_disabled');
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['planType'])) {
    $planType = $_POST['planType']; // e.g., "Pro-Monthly"
    $paymentOption = $_POST['paymentOption'];
   
    if (!$userId) {
        header('Location: /login');
        exit;
    }

    $checkout = new CheckoutController($conn);
    $checkout->subscribe($planType, $paymentOption, $userId);

} 


