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

    if (empty($media)) {
        die("$format with ID $bookId not found.");
    }
    
    $this->generatePaymentForm($academicBook, $user, $format);
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


   public function generatePaymentForm($book, $user, $format = 'Ebook') {
    if (!$book || !$user) {
        die("Invalid book or user data.");
    }

    $bookId = $book['ID'] ?? $book['id'] ?? '';
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
    }

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
        'amount'          => number_format($price, 2, '.', ''),
        'item_name'       => $title,
        'custom_str1'     => $bookId,
        'custom_str2'     => $userKey,
        'custom_str3'     => ucfirst($format),
    ];

    $signature = $this->generateSignature($data, 'SABooksOnline2021');
    $data['signature'] = $signature;

    $htmlForm = '<form id="payfastForm" action="https://www.payfast.co.za/eng/process" method="post" style="display:none;">';
    foreach ($data as $name => $value) {
        $htmlForm .= '<input name="'.$name.'" type="hidden" value="'.htmlspecialchars($value, ENT_QUOTES).'" />';
    }
    $htmlForm .= '</form>';

    // $htmlForm .= '<script>
    //     document.addEventListener("DOMContentLoaded", function() {
    //         document.getElementById("payfastForm").submit();
    //     });
    // </script>';

    // echo $htmlForm;
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
