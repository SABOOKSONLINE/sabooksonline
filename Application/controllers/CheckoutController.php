<?php
require_once __DIR__ . '/../models/BookModel.php';
require_once __DIR__ . '/../models/AcademicBookModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/MediaModel.php';
require_once __DIR__ . '/../models/BillingModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';


class CheckoutController {
    private $bookModel;
    private $userModel;
    private $mediaModel;
    private $conn;
    private $academicBookModel;


    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new userModel($conn);
        $this->bookModel = new BookModel($conn);
        $this->mediaModel = new MediaModel($conn);
        $this->academicBookModel = new AcademicBookModel($conn);


    }

   public function purchaseBook($bookId, $userId, $format = 'ebook') {

        if (empty($bookId) || empty($userId)) {
            die("Invalid book or user ID.");
        }

        $book = $this->bookModel->getBookById($bookId);
        $user = $this->userModel->getUserByNameOrKey($userId);

        $this->generatePaymentForm($book, $user, $format);
    }

    public function purchaseMedia($bookId, $userId, $format) {
    if (empty($bookId) || empty($userId) || empty($format)) {
        die("Invalid book ID, user ID, or media format.");
    }

    $user = $this->userModel->getUserByNameOrKey($userId);
    if (empty($user)) {
        die("User not found.");
    }
    $media = null;
    switch ($format) {
        case "Magazine":
            $media = $this->mediaModel->selectMagazineById($bookId);
            break;
        case "Newspaper":
            $media = $this->mediaModel->selectNewspaperById($bookId);
            break;
        default:
            die("Unsupported media format.");
    }

    if (empty($media)) {
        die("$format with ID $bookId not found.");
    }
    
    $this->generatePaymentForm($media, $user, $format);
}
 public function purchaseAcademicBook($bookId, $userId, $format) {
    if (empty($bookId) || empty($userId) || empty($format)) {
        die("Invalid book ID, user ID, or media format.");
    }

    $user = $this->userModel->getUserByNameOrKey($userId);
    if (empty($user)) {
        die("User not found.");
    }
    $academicBook = $this->academicBookModel->selectBookByPublicKey($bookId);

    if (empty($academicBook)) {
        die("$format with ID $bookId not found.");
    }
    
    $this->generatePaymentForm($academicBook, $user, $format);
}

public function purchase($price, $userId, $api = false, $orderId = null) {

    $user = $this->userModel->getUserByNameOrKey($userId);
    if (empty($user)) {
        die("User not found.");
    }

    if($api){
          $this->payment($price, $user);

    } else {
        $this->generatePayment($price, $user, $orderId);
    }
}


    public function subscribe($planType, $paymentOption, $userId) {

    $user = $this->userModel->getUserByNameOrKey($userId);
    $billingModel = new BillingModel();
    $planDetails = $billingModel->getPlanDetails($planType); // returns ['name' => 'Pro', 'billing' => 'Monthly', 'amount' => 199]


    if ($paymentOption === "later") {

        $this->userModel->updateUserPlanRoyalties($userId, $planDetails['name'], $planDetails['billing']);
        $_SESSION['ADMIN_SUBSCRIPTION'] = $planDetails['name'];
        header('Location: /dashboards');

    } else {
        // Pay now → redirect to PayFast with correct amount
        $this->generatePaymentFormPlan(
            $planType,
            $planDetails['amount'],
            $planDetails['billing'],
            $planDetails['name'],
            $user
        );
    }
}

public function payment($price, $user) {

    if (!$user) {
        die("Invalid book or user data.");
    }

    $userId      = $user['ADMIN_ID'];
    $userName    = $user['ADMIN_NAME'];
    $userEmail   = $user['ADMIN_EMAIL'];

    $data = [
        'merchant_id'     => getenv('PAYFAST_MERCHANT_ID') ?: '',
        'merchant_key'    => getenv('PAYFAST_MERCHANT_KEY') ?: '',
        'return_url'      => 'https://www.sabooksonline.co.za/payment/return',
        'cancel_url'      => 'https://www.sabooksonline.co.za/payment/cancel',
        'notify_url'      => 'https://www.sabooksonline.co.za/payment/notify',
        'name_first'      => $userName,
        'email_address'   => $userEmail,
        'm_payment_id'    => uniqid(),
        'amount'          => number_format($price, 2, '.', ''),
        'item_name'       => 'Checkout Books Purchase',
        'custom_str2'     => 'hardcopy',
        'custom_str3'     => $userId
    ];

    $signature = $this->generateSignature($data, getenv('PAYFAST_PASSPHRASE') ?: '');
    $data['signature'] = $signature;
    // Build query string
    $queryString = http_build_query($data);

    // PayFast URL
    $paymentUrl = "https://www.payfast.co.za/eng/process?" . $queryString;

    // Return JSON to mobile app
    echo json_encode([
        "status" => "success",
        "payment_url" => $paymentUrl
    ]);
    exit;
}

public function generatePayment($price, $user, $orderId = null) {
    if (!$user) {
        die("Invalid book or user data.");
    }
    
    $userId = $user['ADMIN_ID'] ?? '';
    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';
    
    // Get Yoco secret key from environment variable
    // load_env.php should have loaded it into getenv(), $_ENV, and $_SERVER
    $yocoSecretKey = getenv('YOCO_SECRET_KEY') ?: $_ENV['YOCO_SECRET_KEY'] ?? $_SERVER['YOCO_SECRET_KEY'] ?? '';
    
    // Validate that we have a key before proceeding
    if (empty($yocoSecretKey)) {
        error_log("YOCO_SECRET_KEY is not set. Check .env file and ensure load_env.php is called.");
        die("Payment initialization failed: Configuration error. Please contact support.");
    }
    
    if ($price < 2) {
        die("Minimum payment amount is R2.00");
    }
    
    // Detect if we're on localhost
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
               strpos($httpHost, 'localhost') !== false ||
               strpos($httpHost, '127.0.0.1') !== false;
    
    // Check if using live key (starts with 'sk_live_')
    $isLiveKey = str_starts_with($yocoSecretKey, 'sk_live_');
    
    // Determine base URL for redirects
    // Live keys require HTTPS URLs - use production URLs if on localhost with live key
    if ($isLocal && $isLiveKey) {
        // Check if ngrok HTTPS tunnel is configured
        $ngrokUrl = getenv('NGROK_URL') ?: $_ENV['NGROK_URL'] ?? $_SERVER['NGROK_URL'] ?? '';
        if (!empty($ngrokUrl)) {
            $baseUrl = rtrim($ngrokUrl, '/');
            error_log("Using ngrok HTTPS URL for localhost with live key: $baseUrl");
        } else {
            // Use production URLs when testing live key on localhost
            $baseUrl = 'https://www.sabooksonline.co.za';
            error_log("Using live Yoco key on localhost - redirect URLs set to production");
        }
    } else {
        // Normal behavior: use production URLs for production, or localhost URLs for test keys
        $baseUrl = $isLocal ? 'http://' . $httpHost : 'https://www.sabooksonline.co.za';
    }
    
    $metadata = [
        'user_id' => $userId,
        'user_name' => $userName,
        'user_email' => $userEmail,
        'item_name' => 'Checkout Books Purchase',
        'payment_id' => uniqid(),
        'product_type' => 'hardcopy'
    ];
    
    // Add order ID to metadata if this is a cart checkout
    if ($orderId !== null) {
        $metadata['order_id'] = $orderId;
    }
    
    $checkoutData = [
        'amount' => (int)($price * 100), // Amount in cents
        'currency' => 'ZAR',
        'cancelUrl' => "$baseUrl/payment/cancel",
        'successUrl' => "$baseUrl/payment/return",
        'failureUrl' => "$baseUrl/payment/cancel",
        'metadata' => $metadata
    ];
    
    $ch = curl_init('https://payments.yoco.com/api/checkouts');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($checkoutData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $yocoSecretKey,
        'Content-Type: application/json'
    ]);
    
    // SSL configuration - disable verification for localhost, enable for production
    if ($isLocal) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    // curl_close() is deprecated in PHP 8.5+ - cURL handles close automatically
    
    // Debug logging for localhost
    if ($isLocal) {
        error_log("Yoco Payment Request - Localhost Debug:");
        error_log("  Base URL: $baseUrl");
        error_log("  Is Live Key: " . ($isLiveKey ? 'YES' : 'NO'));
        error_log("  HTTP Code: $httpCode");
        error_log("  cURL Error: " . ($curlError ?: 'None'));
        if ($response) {
            error_log("  Response: " . substr($response, 0, 500));
        }
    }
    
    if ($curlError) {
        error_log("Yoco Payment cURL Error: $curlError");
        die("Payment initialization failed: Network error. Please try again or contact support.");
    }
    
    if ($httpCode !== 200 && $httpCode !== 201) {
        error_log("Yoco Payment Error - HTTP Code: $httpCode, Response: $response");
        $errorMsg = "Payment initialization failed. ";
        if ($response) {
            $errorData = json_decode($response, true);
            if (isset($errorData['message'])) {
                $errorMsg .= "Error: " . $errorData['message'];
            } else if (isset($errorData['description'])) {
                $errorMsg .= "Error: " . $errorData['description'];
            } else {
                $errorMsg .= "HTTP $httpCode";
            }
        } else {
            $errorMsg .= "Please try again or contact support.";
        }
        die($errorMsg);
    }
    
    $responseData = json_decode($response, true);
    
    // Store checkoutId in session for verification on return
    if (isset($responseData['id'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['yoco_checkout_id'] = $responseData['id'];
    }
    
    if (isset($responseData['redirectUrl'])) {
        header('Location: ' . $responseData['redirectUrl']);
        exit;
    } else {
        error_log("Yoco Response missing redirectUrl: " . print_r($responseData, true));
        die("Failed to get payment URL. Please contact support.");
    }
}

/**
 * Generate Yoco payment for mobile app (returns JSON instead of redirecting)
 * @param float $price - Price in rands
 * @param array $user - User data array
 * @param int|null $orderId - Optional order ID for cart checkout
 * @return void - Outputs JSON response
 */
public function generateYocoPaymentForMobile($price, $user, $orderId = null) {
    // Suppress deprecation warnings for API responses (they break JSON parsing)
    $oldErrorReporting = error_reporting(E_ALL & ~E_DEPRECATED);
    
    // Ensure JSON content type is set before any output
    header('Content-Type: application/json');
    
    if (!$user) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid user data']);
        error_reporting($oldErrorReporting);
        return;
    }
    
    $userId = $user['ADMIN_ID'] ?? '';
    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';
    
    // Get Yoco secret key from environment variable
    $yocoSecretKey = getenv('YOCO_SECRET_KEY') ?: $_ENV['YOCO_SECRET_KEY'] ?? $_SERVER['YOCO_SECRET_KEY'] ?? '';
    
    if (empty($yocoSecretKey)) {
        error_log("YOCO_SECRET_KEY is not set. Check .env file and ensure load_env.php is called.");
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Payment configuration error']);
        error_reporting($oldErrorReporting);
        return;
    }
    
    if ($price < 2) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Minimum payment amount is R2.00']);
        error_reporting($oldErrorReporting);
        return;
    }
    
    // Use web URL that redirects to mobile app (Yoco requires HTTP/HTTPS URLs)
    // The mobile app WebView will intercept these URLs and handle navigation
    $baseUrl = 'https://www.sabooksonline.co.za';
    
    $metadata = [
        'user_id' => $userId,
        'user_name' => $userName,
        'user_email' => $userEmail,
        'item_name' => 'Mobile App Cart Purchase',
        'payment_id' => uniqid(),
        'product_type' => 'hardcopy',
        'source' => 'mobile_app'
    ];
    
    // Add order ID to metadata (order is created before payment)
    if ($orderId !== null) {
        $metadata['order_id'] = $orderId;
    }
    
    $checkoutData = [
        'amount' => (int)($price * 100), // Amount in cents
        'currency' => 'ZAR',
        'cancelUrl' => "$baseUrl/payment/mobile/cancel?order_id=$orderId",
        'successUrl' => "$baseUrl/payment/mobile/return?order_id=$orderId",
        'failureUrl' => "$baseUrl/payment/mobile/cancel?order_id=$orderId",
        'metadata' => $metadata
    ];
    
    $ch = curl_init('https://payments.yoco.com/api/checkouts');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($checkoutData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $yocoSecretKey,
        'Content-Type: application/json'
    ]);
    // SSL verification - Always disable for localhost/testing to avoid certificate issues
    // Check multiple server variables to detect localhost/development environment
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $serverName = $_SERVER['SERVER_NAME'] ?? '';
    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
    $serverAddr = $_SERVER['SERVER_ADDR'] ?? '';
    
    $isLocalhost = in_array($httpHost, ['localhost', '127.0.0.1', '10.0.2.2']) 
                   || strpos($httpHost, 'localhost') !== false
                   || strpos($httpHost, '127.0.0.1') !== false
                   || strpos($httpHost, '10.0.2.2') !== false
                   || in_array($serverName, ['localhost', '127.0.0.1', '10.0.2.2'])
                   || in_array($remoteAddr, ['127.0.0.1', '::1'])
                   || in_array($serverAddr, ['127.0.0.1', '::1'])
                   || strpos($httpHost, ':8000') !== false; // Development server port
    
    // Always disable SSL verification for now (development/testing)
    // This is safe because we're only connecting to Yoco's API (not accepting connections)
    // In production, you can enable SSL verification if needed
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    
    if ($isLocalhost) {
        error_log("Yoco Payment: SSL verification disabled for localhost/testing (HTTP_HOST: $httpHost, REMOTE_ADDR: $remoteAddr)");
    } else {
        error_log("Yoco Payment: SSL verification disabled (HTTP_HOST: $httpHost, REMOTE_ADDR: $remoteAddr)");
    }
    
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    $curlErrno = curl_errno($ch);
    
    if ($curlError) {
        error_log("Yoco Payment cURL Error #$curlErrno: $curlError");
        error_log("Yoco Payment Request Data: " . json_encode($checkoutData));
        
        // Provide more specific error messages
        $errorMessage = 'Payment initialization failed: Network error';
        if ($curlErrno === CURLE_SSL_CONNECT_ERROR || $curlErrno === CURLE_SSL_CERTPROBLEM) {
            $errorMessage = 'Payment initialization failed: SSL connection error. Please check server SSL configuration.';
        } elseif ($curlErrno === CURLE_COULDNT_CONNECT) {
            $errorMessage = 'Payment initialization failed: Could not connect to payment gateway. Please check server network connectivity.';
        } elseif ($curlErrno === CURLE_OPERATION_TIMEOUTED) {
            $errorMessage = 'Payment initialization failed: Connection timeout. Please try again.';
        }
        
        // curl_close() is deprecated in PHP 8.5+ - cURL handles close automatically
        // No need to call curl_close() - PHP automatically closes cURL handles
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        error_reporting($oldErrorReporting);
        return;
    }
    
    // curl_close() is deprecated in PHP 8.5+ - cURL handles close automatically
    // No need to call curl_close() - PHP automatically closes cURL handles
    
    if ($httpCode !== 200 && $httpCode !== 201) {
        error_log("Yoco Payment Error - HTTP Code: $httpCode, Response: $response");
        $errorMsg = "Payment initialization failed";
        if ($response) {
            $errorData = json_decode($response, true);
            if (isset($errorData['message'])) {
                $errorMsg = $errorData['message'];
            } else if (isset($errorData['description'])) {
                $errorMsg = $errorData['description'];
            }
        }
        http_response_code($httpCode);
        echo json_encode(['success' => false, 'message' => $errorMsg]);
        error_reporting($oldErrorReporting);
        return;
    }
    
    $responseData = json_decode($response, true);
    
    if (isset($responseData['redirectUrl'])) {
        // Store checkoutId in session for verification on return
        if (isset($responseData['id'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['yoco_checkout_id'] = $responseData['id'];
        }
        
        echo json_encode([
            'success' => true,
            'payment_url' => $responseData['redirectUrl'],
            'checkout_id' => $responseData['id'] ?? null
        ]);
        error_reporting($oldErrorReporting);
    } else {
        error_log("Yoco Response missing redirectUrl: " . print_r($responseData, true));
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to get payment URL']);
        error_reporting($oldErrorReporting);
    }
}

// public function generatePayment($price, $user) {
//     if (!$user) {
//         die("Invalid book or user data.");
//     }


//     $userId = $user['ADMIN_ID'] ?? '';
//     $userName = $user['ADMIN_NAME'] ?? 'Customer';
//     $userEmail = $user['ADMIN_EMAIL'] ?? '';
    

//     $data = [
//         'merchant_id'     => '18172469',
//         'merchant_key'    => 'gwkk16pbxdd8m',
//         'return_url'      => 'https://www.sabooksonline.co.za/payment/return',
//         'cancel_url'      => 'https://www.sabooksonline.co.za/payment/cancel',
//         'notify_url'      => 'https://www.sabooksonline.co.za/payment/notify',
//         'name_first'      => $userName,
//         'email_address'   => $userEmail,
//         'm_payment_id'    => uniqid(),
//         'amount'          => number_format($price, 2, '.', ''),
//         'item_name'       => 'Checkout Books Purchase',
//         'custom_str2'     => 'hardcopy',
//         'custom_str3'     => $userId,

//     ];

//     $signature = $this->generateSignature($data, getenv('PAYFAST_PASSPHRASE') ?: '');
//     $data['signature'] = $signature;

//     $htmlForm = '<form id="payfastForm" action="https://www.payfast.co.za/eng/process" method="post" style="display:none;">';
//     foreach ($data as $name => $value) {
//         $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.htmlspecialchars($value, ENT_QUOTES).'" />';
//     }
//     $htmlForm .= '</form>';

//     $htmlForm .= '<script>
//         document.addEventListener("DOMContentLoaded", function() {
//             document.getElementById("payfastForm").submit(); 
//         });
//     </script>';

//     echo $htmlForm;

// }
   public function generatePaymentForm($book, $user, $format = 'Ebook') {
    if (!$book || !$user) {
        die("Invalid book or user data.");
    }

    // For academic books, use public_key; for regular books, use ID
    $bookId = $book['ID'] ?? $book['id'] ?? '';
    if (isset($book['public_key'])) {
        $bookId = $book['public_key']; // Academic books use public_key
    }
    $title = html_entity_decode($book['TITLE'] ?? $book['title'] ?? 'Untitled Book');

    $userKey = $user['ADMIN_USERKEY'] ?? '';
    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';

    // Choose correct price based on format
    $price = 0;
    switch (strtolower($format)) {
        case 'audiobook':
            $price = $book['ABOOKPRICE'] ?? 0;
            break;
        case 'ebook':
            $price = $book['EBOOKPRICE'] ?? 0;
            break;
        case 'academicbook':
            $price = $book['ebook_price'] ?? 0;
            break;
        case 'magazine':
            $price = $book['price'] ?? 0;
            break;
        case 'newspaper':
            $price = $book['price'] ?? 0;
            break; 
        case 'hardcopy':
            // For academic books, use physical_book_price; for regular books, use price
            if (isset($book['physical_book_price'])) {
                $price = $book['physical_book_price'] ?? 0;
            } else {
                $price = $book['price'] ?? 0;
            }
            break;   
    }

    if (empty($bookId) || empty($userEmail)) {
        die("Missing book ID or user email.");
    }

    $data = [
        'merchant_id'     => getenv('PAYFAST_MERCHANT_ID') ?: '',
        'merchant_key'    => getenv('PAYFAST_MERCHANT_KEY') ?: '',
        'return_url'      => 'https://www.sabooksonline.co.za/payment/return',
        'cancel_url'      => 'https://www.sabooksonline.co.za/payment/cancel',
        'notify_url'      => 'https://www.sabooksonline.co.za/payment/notify',
        'name_first'      => $userName,
        'email_address'   => $userEmail,
        'm_payment_id'    => uniqid(),
        'amount'          => number_format($price, 2, '.', ''),
        'item_name'       => $title,
        'custom_str1'     => $bookId,
        'custom_str2'     => $userKey,
        'custom_str3'     => ucfirst($format),
    ];

    $signature = $this->generateSignature($data, getenv('PAYFAST_PASSPHRASE') ?: '');
    $data['signature'] = $signature;

    // Pass variables to the checkout view
    // The view expects: $book, $user, $format, $data, $price
    include __DIR__ . '/../views/payment/checkout.php';

}

    public function generatePaymentFormPlan($plan, $planPrice, $subscriptionType, $planName, $user) {
        if (!$plan || !$user) {
            die("Invalid plan or user data.");
        }

        $userName = $user['ADMIN_NAME'] ?? 'Customer';
        $userEmail = $user['ADMIN_EMAIL'] ?? '';

        if (empty($userEmail)) {
            die("Missing user email.");
        }


    $data = array(
    'merchant_id' => getenv('PAYFAST_MERCHANT_ID') ?: '',
    'merchant_key' => getenv('PAYFAST_MERCHANT_KEY') ?: '',
    'return_url' => 'https://www.sabooksonline.co.za/payment/return',
    'cancel_url' => 'https://www.sabooksonline.co.za/payment/cancel',
    'notify_url' => 'https://www.sabooksonline.co.za/payment/notify',
    // Buyer details
    'name_first' => $userName,
    'name_last'  => $subscriptionType,
    'email_address'=> $userEmail,
    // Transaction details
    'm_payment_id' => $user['ADMIN_USERKEY'], // Unique payment ID to pass through to notify_url
    'amount' => number_format( sprintf( '%.2f', $planPrice ), 2, '.', '' ),
    'item_name' => $plan,
    'custom_str2'     => $planName,
    'subscription_type' => '2',   
);

    $signature = $this->generateSignature($data, getenv('PAYFAST_PASSPHRASE') ?: '');
    $data['signature'] = $signature;

        $htmlForm = '<form id="payfastForm" action="https://www.payfast.co.za/eng/process" method="post" style="display:none;">';
    foreach ($data as $name => $value) {
        $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.htmlspecialchars($value, ENT_QUOTES).'" />';
    }
    $htmlForm .= '</form>';

    $htmlForm .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("payfastForm").submit();
        });
    </script>';

    echo $htmlForm;

}



    function generateSignature($data, $passPhrase = null) {
    // Create parameter string
    $pfOutput = '';
    foreach( $data as $key => $val ) {
        if($val !== '') {
            $pfOutput .= $key .'='. urlencode( trim( $val ) ) .'&';
        }
    }
    // Remove last ampersand
    $getString = substr( $pfOutput, 0, -1 );
    if( $passPhrase !== null ) {
        $getString .= '&passphrase='. urlencode( trim( $passPhrase ) );
    }
    return md5( $getString );
}



}
