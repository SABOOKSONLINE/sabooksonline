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
        'cancelUrl' => 'https://www.sabooksonline.co.za/payment/cancel',
        'successUrl' => 'https://www.sabooksonline.co.za/payment/return',
        'failureUrl' => 'https://www.sabooksonline.co.za/payment/cancel',
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
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
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
