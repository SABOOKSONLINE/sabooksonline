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
        header('Location: /dashboards');
    } else {
        // Pay now → redirect to PayFast with correct amount
        $this->generatePaymentFormPlan(
            $planDetails['name'],
            $planDetails['amount'],
            $planDetails['billing'],
            $paymentOption,
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
    $title = $book['TITLE'] ?? 'Untitled Book';
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
        'return_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/return',
        'cancel_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/cancel',
        'notify_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/notify',
        'name_first'      => $userName,
        'email_address'   => $userEmail,
        'm_payment_id'    => uniqid(),
        'amount'          => number_format($retailPrice, 2, '.', ''),
        'item_name'       => $title,
        'custom_str1'     => $bookId,
    ];

    

    $signature = $this->generateSignature($data, 'SABooksOnline2021');
    $data['signature'] = $signature;

    $htmlForm = '<form action="https://www.payfast.co.za/eng/process" method="post">';
    foreach ($data as $name => $value) {
        $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.htmlspecialchars($value, ENT_QUOTES).'" />';
    }
    $htmlForm .= '<input class="ud-btn btn-thm mt-2" type="submit" value="Pay With PayFast">
    <img src="https://my.sabooksonline.co.za/img/Payfast By Network_dark.svg" width="200px"></form>';

    echo $htmlForm;
}
    public function generatePaymentFormPlan($plan, $planPrice, $subscriptionType, $paymentOption, $user) {
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

    $data = [
    'merchant_id'     => '18172469',
    'merchant_key'    => 'gwkk16pbxdd8m',
    'return_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/return',
    'cancel_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/cancel',
    'notify_url'      => 'https://11-july-2023.sabooksonline.co.za/payment/notify',
    'name_first'      => $userName,
    'email_address'   => $userEmail,
    'm_payment_id'    => uniqid(),
    'item_name'       => $plan,
    'custom_str1'     => $paymentOption,
    'custom_str2'     => $subscriptionType,
    'subscription_type' => 1,
    'billing_date'    => date('Y-m-d'), // Start immediately
    'amount'          => $formattedAmount, // Subscription amount
    // debug i must write amount not recuriing amount note
    'recurring_amount'=> $formattedAmount, // Recurring amount
    'cycles'          => 0, // Unlimited billing
    'frequency'       => ($subscriptionType === 'Yearly') ? 7 : 3, // 7 = Yearly, 3 = Monthly
    ];



    // Generate signature
    $signature = $this->generateSignature($data, 'SABooksOnline2021');
    $data['signature'] = $signature;

    $htmlForm = '<form action="https://www.payfast.co.za/eng/process" method="post">';
    foreach ($data as $name => $value) {
        $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.htmlspecialchars($value, ENT_QUOTES).'" />';
    }
    $htmlForm .= '<input class="ud-btn btn-thm mt-2" type="submit" value="Pay With PayFast">
    <img src="https://my.sabooksonline.co.za/img/Payfast By Network_dark.svg" width="200px"></form>';

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


    public function paymentNotify() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = $_POST;

        // 1. Signature check
        $submittedSignature = $postData['signature'] ?? '';
        unset($postData['signature']);

        $expectedSignature = $this->generateSignature($postData, 'SABooksOnline2021');

        if ($expectedSignature !== $submittedSignature) {
            die("Invalid signature");
        }

        // 2. Check status
        if ($postData['payment_status'] === 'COMPLETE') {
            $paymentId = $postData['m_payment_id'];
            $amount = $postData['amount_gross'];
            $bookId = $postData['custom_str1'];
            $userEmail = $postData['email_address'];

            $user = $this->userModel->getUserByEmail($userEmail);
            if (!$user) {
                die("User not found");
            }
            $userId = $user['ADMIN_ID'];

            // 3. DB insert
            $stmt = $this->conn->prepare("INSERT INTO purchases (user_id, book_id, payment_id, amount, payment_status) VALUES (?, ?, ?, ?, 'COMPLETE')");
            $stmt->bind_param("isss", $userId, $bookId, $paymentId, $amount);
            $stmt->execute();

            include __DIR__ . '/../views/payment/return.php';
        } else {
            include __DIR__ . '/../views/payment/cancel.php';
        }
    }
}


}
