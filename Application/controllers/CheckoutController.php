<?php
require_once __DIR__ . '/../models/BookModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BillingModel.php';
require_once __DIR__ . '/../../vendor/autoload.php';


class CheckoutController {
    private $bookModel;
    private $userModel;
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new userModel($conn);
        $this->bookModel = new BookModel($conn);
    }

   public function purchaseBook($bookId, $userId) {
    if (empty($bookId) || empty($userId)) {
        die("Invalid book or user ID.");
    }

    $book = $this->bookModel->getBookById($bookId);
    $user = $this->userModel->getUserByNameOrKey($userId);

    if (!$book) {
        die("Book not found.");
    }
    if (!$user) {
        die("User not found.");
    }

    $this->generatePaymentForm($book, $user);    
}

    public function subscribe($planType, $paymentOption, $userId) {
    if (empty($userId)) {
        die("Invalid user ID.");
    }

    $user = $this->userModel->getUserByNameOrKey($userId);
    if (!$user) {
        die("User not found.");
    }

    // Extract plan details from the planType
    $billingModel = new BillingModel();
    $planDetails = $billingModel->getPlanDetails($planType); // returns ['name' => 'Pro', 'billing' => 'Monthly', 'amount' => 199]

    if (!$planDetails) {
        die("Invalid plan type.");
    }

    if ($paymentOption === "later") {
        // Save to DB or update user record as Pay Later
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


    public function generatePaymentForm($book, $user) {
    if (!$book || !$user) {
        die("Invalid book or user data.");
    }

    $bookId = $book['ID'] ?? '';
    $cover = $book['COVER'] ?? '';
    $title = html_entity_decode($book['TITLE']) ?? 'Untitled Book';
    $publisher = $book['PUBLISHER'] ?? 'Unknown';
    $description = $book['DESCRIPTION'] ?? 'No description.';
    $retailPrice = $book['RETAILPRICE'] ?? 0;

    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';

    if (empty($bookId) || empty($userEmail)) {
        die("Missing book ID or user email.");
    }

    $data = [
        'merchant_id'     => '18172469',
        'merchant_key'    => 'gwkk16pbxdd8m',
        'return_url'      => 'https://www.sabooksonline.co.za/payment/return',
        'cancel_url'      => 'https://www.sabooksonline.co.za/payment/cancel',
        'notify_url'      => 'https://www.sabooksonline.co.za/payment/notify',
        'name_first'      => $userName,
        'email_address'   => $userEmail,
        'm_payment_id'    => uniqid(),
        'amount'          => number_format($retailPrice, 2, '.', ''),
        'item_name'       => $title,
        'custom_str1'     => $bookId,
    ];

    

    $signature = $this->generateSignature($data, 'SABooksOnline2021');
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
    public function generatePaymentFormPlan($plan, $planPrice, $subscriptionType, $planName, $user) {
    if (!$plan || !$user) {
        die("Invalid plan or user data.");
    }

    $userName = $user['ADMIN_NAME'] ?? 'Customer';
    $userEmail = $user['ADMIN_EMAIL'] ?? '';

    if (empty($userEmail)) {
        die("Missing user email.");
    }

    // Default PayFast parameters
    $formattedAmount = number_format($planPrice, 2, '.', '');
    $amount = number_format( sprintf( '%.2f', $planPrice ), 2, '.', '' );

    $data = array(
    // Merchant details
    'merchant_id' => '18172469',//18172469   test: 10030247
    'merchant_key' => 'gwkk16pbxdd8m',//gwkk16pbxdd8m    test: g84pzvwrmr8rj
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

    $signature = $this->generateSignature($data, 'SABooksOnline2021');
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
