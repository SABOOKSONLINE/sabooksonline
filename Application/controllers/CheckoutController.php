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
    
    // Ensure price is a valid float
    $price = (float)$price;
    if ($price <= 0 || !is_numeric($price)) {
        error_log("Invalid price for Yoco payment: $price");
        die("Invalid payment amount. Please contact support.");
    }
    
    $userId = $user['ADMIN_ID'] ?? '';
    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';
    
    if (empty($userId) || empty($userEmail)) {
        error_log("Missing user data for Yoco payment - User ID: $userId, Email: $userEmail");
        die("Invalid user data. Please log in again.");
    }
    
    // Get Yoco secret key from environment variable (try multiple sources)
    $yocoSecretKey = getenv('YOCO_SECRET_KEY') ?: $_ENV['YOCO_SECRET_KEY'] ?? $_SERVER['YOCO_SECRET_KEY'] ?? '';
    
    // If still empty, try to read from .env file directly as fallback
    if (empty($yocoSecretKey)) {
        // Try multiple possible .env file paths
        $possiblePaths = [
            __DIR__ . '/../../.env',  // From Application/controllers/
            __DIR__ . '/../../../.env', // Alternative path
            dirname(__DIR__, 3) . '/.env', // Go up 3 levels
        ];
        
        foreach ($possiblePaths as $envFile) {
            if (file_exists($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $line = trim($line);
                    // Skip comments and empty lines
                    if (empty($line) || $line[0] === '#') {
                        continue;
                    }
                    // Check if line contains YOCO_SECRET_KEY
                    if (strpos($line, 'YOCO_SECRET_KEY') !== false) {
                        // Handle both YOCO_SECRET_KEY=value and # YOCO_SECRET_KEY=value (commented)
                        if ($line[0] !== '#') {
                            $parts = explode('=', $line, 2);
                            if (count($parts) === 2 && trim($parts[0]) === 'YOCO_SECRET_KEY') {
                                $yocoSecretKey = trim($parts[1], " \t\n\r\0\x0B\"'");
                                // Set it in environment for future use
                                putenv("YOCO_SECRET_KEY=$yocoSecretKey");
                                $_ENV['YOCO_SECRET_KEY'] = $yocoSecretKey;
                                $_SERVER['YOCO_SECRET_KEY'] = $yocoSecretKey;
                                break 2; // Break out of both loops
                            }
                        }
                    }
                }
            }
        }
    }
    
    // Debug logging for localhost (remove in production or make conditional)
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
               strpos($httpHost, 'localhost') !== false ||
               strpos($httpHost, '127.0.0.1') !== false;
    
    if ($isLocal) {
        error_log("YOCO_SECRET_KEY Debug:");
        error_log("  getenv(): " . (getenv('YOCO_SECRET_KEY') ? 'SET (' . substr(getenv('YOCO_SECRET_KEY'), 0, 10) . '...)' : 'NOT SET'));
        error_log("  _ENV: " . (isset($_ENV['YOCO_SECRET_KEY']) ? 'SET (' . substr($_ENV['YOCO_SECRET_KEY'], 0, 10) . '...)' : 'NOT SET'));
        error_log("  _SERVER: " . (isset($_SERVER['YOCO_SECRET_KEY']) ? 'SET (' . substr($_SERVER['YOCO_SECRET_KEY'], 0, 10) . '...)' : 'NOT SET'));
        error_log("  Final key: " . (empty($yocoSecretKey) ? 'EMPTY' : substr($yocoSecretKey, 0, 10) . '...'));
    }
    
    // Validate that we have a key
    if (empty($yocoSecretKey)) {
        error_log("YOCO_SECRET_KEY is not set in environment variables. Check .env file or server environment variables.");
        error_log("Checked getenv(), _ENV, _SERVER, and direct .env file read.");
        foreach ($possiblePaths ?? [__DIR__ . '/../../.env'] as $path) {
            error_log(".env file path checked: $path");
            error_log(".env file exists: " . (file_exists($path) ? 'YES' : 'NO'));
        }
        die("Payment initialization failed: Configuration error. Please contact support.");
    }
    
    // Validate key format (should start with 'sk_' for secret keys)
    if (!str_starts_with($yocoSecretKey, 'sk_')) {
        error_log("YOCO_SECRET_KEY format appears invalid (should start with 'sk_')");
        die("Payment initialization failed: Invalid configuration. Please contact support.");
    }
    
    if ($price < 2) {
        die("Minimum payment amount is R2.00");
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
    
    // Determine base URL for localhost vs production
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
               strpos($httpHost, 'localhost') !== false ||
               strpos($httpHost, '127.0.0.1') !== false ||
               strpos($httpHost, 'localhost:') !== false ||
               strpos($httpHost, '127.0.0.1:') !== false;
    
    // Check if using live key (starts with 'sk_live_')
    $isLiveKey = str_starts_with($yocoSecretKey, 'sk_live_');
    
    // Yoco live keys require HTTPS URLs
    // If on localhost with live key, use production URLs for redirects (or ngrok HTTPS URL if available)
    if ($isLocal && $isLiveKey) {
        // Check if ngrok or similar HTTPS tunnel is being used
        $ngrokUrl = getenv('NGROK_URL') ?: $_ENV['NGROK_URL'] ?? $_SERVER['NGROK_URL'] ?? '';
        
        if (!empty($ngrokUrl)) {
            // Use ngrok HTTPS URL
            $baseUrl = rtrim($ngrokUrl, '/');
            error_log("Using ngrok HTTPS URL for localhost with live key: $baseUrl");
        } else {
            // Fallback: Use production URLs for redirects when testing live key on localhost
            // Note: This means payment return will go to production, not localhost
            $baseUrl = 'https://www.sabooksonline.co.za';
            error_log("WARNING: Using live Yoco key on localhost. Redirect URLs set to production (https://www.sabooksonline.co.za)");
            error_log("For localhost testing with live key, consider using ngrok: Set NGROK_URL in .env");
        }
    } else {
        // Normal behavior: use appropriate URL based on environment
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $baseUrl = $isLocal ? "$protocol://$httpHost" : 'https://www.sabooksonline.co.za';
    }
    
    $checkoutData = [
        'amount' => (int)round($price * 100), // Amount in cents (rounded to avoid precision issues)
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
    
    // SSL configuration - disable verification for local development
    // In production, you should have proper SSL certificates configured
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
               strpos($httpHost, 'localhost') !== false ||
               strpos($httpHost, '127.0.0.1') !== false ||
               strpos($httpHost, 'localhost:') !== false ||
               strpos($httpHost, '127.0.0.1:') !== false;
    
    if ($isLocal) {
        // Disable SSL verification for local development
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // Additional options for localhost testing
        curl_setopt($ch, CURLOPT_CAINFO, '');
        curl_setopt($ch, CURLOPT_CAPATH, '');
    } else {
        // Enable SSL verification for production
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    }
    
    // Set timeout to prevent hanging requests
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    
    // Follow redirects
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    // curl_close() is deprecated in PHP 8.5+ - handles are automatically closed when out of scope
    
    if ($curlError) {
        error_log("Yoco Payment cURL Error: $curlError");
        die("Payment initialization failed: Network error. Please try again or contact support.");
    }
    
    if ($httpCode !== 200 && $httpCode !== 201) {
        error_log("Yoco Payment Error - HTTP Code: $httpCode, Response: $response, Price: $price, User ID: $userId");
        $errorMsg = "Payment initialization failed. ";
        if ($response) {
            $errorData = json_decode($response, true);
            if (isset($errorData['message'])) {
                $errorMsg .= "Error: " . $errorData['message'];
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
    // Yoco returns 'id' as the checkoutId in the response
    if (isset($responseData['id'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['yoco_checkout_id'] = $responseData['id'];
        
        // Debug logging for localhost
        $httpHost = $_SERVER['HTTP_HOST'] ?? '';
        $isLocal = in_array($httpHost, ['localhost', '127.0.0.1', '::1']) || 
                   strpos($httpHost, 'localhost') !== false ||
                   strpos($httpHost, '127.0.0.1') !== false;
        if ($isLocal) {
            error_log("Yoco Checkout Created - Checkout ID: " . $responseData['id']);
        }
    }
    
    if (isset($responseData['redirectUrl'])) {
        header('Location: ' . $responseData['redirectUrl']);
        exit;
    } else {
        error_log("Yoco Response missing redirectUrl: " . print_r($responseData, true));
        die("Failed to get payment URL. Please contact support.");
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
