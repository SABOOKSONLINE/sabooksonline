<?php
require_once __DIR__ . '/../models/UserModel.php';


class AuthController {

    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function loginWithGoogle($email,$reg_name,$profileImage) {


    $result = $this->userModel->findUserByEmail($email);

    if (!mysqli_num_rows($result)) {
        $this->userModel->insertGoogleUser($reg_name, $email, $profileImage, $email);
        $result = $this->userModel->findUserByEmail($email);
    }

    $userData = mysqli_fetch_assoc($result);
    $this->userModel->startSession($userData);

    return true;
}
public function getUserInfo($email) {
    header('Content-Type: application/json');

    $result = $this->userModel->findUserByEmail($email);

    $userData = mysqli_fetch_assoc($result);


    $subscriptionStatus = strtolower($userData['subscription_status'] ?? '');
    $adminSub = $userData['ADMIN_SUBSCRIPTION'] ?? '';
    $billingCycle = $userData['billing_cycle'] ?? '';

    switch ($subscriptionStatus) {
        case 'free':
            $subscriptionText = ''; // No label for free plan
            break;

        case 'royalties':
            $subscriptionText = $adminSub . '  Royalties';
            break;

        case 'pro':
        case 'premium':
            $subscriptionText = ucfirst($subscriptionStatus) . ' ' . ucfirst($billingCycle);
            break;

        default:
            $subscriptionText = 'Unknown Plan';
            break;
    }
    
    $purchasedBooks = $this->userModel->getPurchasedBookIdsAndFormats($email);
    $publishedBooks = $this->userModel->getPublishedBookIds($userData['ADMIN_USERKEY']);
    $publishedMagazines = $this->userModel->getPublishedMagazines($userData['ADMIN_ID']);
    $publishedNewspapers = $this->userModel->getPublishedNewspapers($userData['ADMIN_ID']);

    // Login success
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'adminKey' => $userData['ADMIN_USERKEY'],
        'email' => $userData['ADMIN_EMAIL'],
        'profile' => $userData['ADMIN_PROFILE_IMAGE'],
        'name' => $userData['ADMIN_NAME'],
        'subscription' => $subscriptionText,
        'number' => $userData['ADMIN_NUMBER'],
        'purchasedBooks' => $purchasedBooks,
        'publishedBooks' => $publishedBooks,
        'publishedMagazines' => $publishedMagazines,
        'publishedNewspapers' => $publishedNewspapers,

    ]);
    exit;
}

    public function loginWithForm($email, $password, $name = null, $picture = null, $isform = true) {
    header('Content-Type: application/json');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid email address.',
        ]);
        exit;
    }

    $result = $this->userModel->findUserByEmail($email);

   if (!mysqli_num_rows($result)) {
    if ($isform) {
        http_response_code(404); // 400 Bad Request is more accurate for missing data
        echo json_encode([
            'success' => false,
            'message' => 'email not found.',
        ]);
        exit;
    }

    $this->userModel->insertGoogleUser($name, $email, $picture, $email);
    $result = $this->userModel->findUserByEmail($email);
}


    $userData = mysqli_fetch_assoc($result);

    if ($isform) {
    if (!$this->userModel->verifyPassword($password, $userData['ADMIN_PASSWORD'])) {
        http_response_code(401); // 401 Unauthorized is correct
        echo json_encode([
            'success' => false,
            'message' => 'Incorrect password.',
        ]);
        exit;
    }
}


    $subscriptionStatus = strtolower($userData['subscription_status'] ?? '');
    $adminSub = $userData['ADMIN_SUBSCRIPTION'] ?? '';
    $billingCycle = $userData['billing_cycle'] ?? '';

    switch ($subscriptionStatus) {
        case 'free':
            $subscriptionText = ''; // No label for free plan
            break;

        case 'royalties':
            $subscriptionText = $adminSub . '  Royalties';
            break;

        case 'pro':
        case 'premium':
            $subscriptionText = ucfirst($subscriptionStatus) . ' ' . ucfirst($billingCycle);
            break;

        default:
            $subscriptionText = 'Unknown Plan';
            break;
    }
    
    $purchasedBooks = $this->userModel->getPurchasedBookIdsAndFormats($email);
    $publishedBooks = $this->userModel->getPublishedBookIds($userData['ADMIN_USERKEY']);
    $publishedMagazines = $this->userModel->getPublishedMagazines($userData['ADMIN_ID']);
    $publishedNewspapers = $this->userModel->getPublishedNewspapers($userData['ADMIN_ID']);

    // Login success
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'adminKey' => $userData['ADMIN_USERKEY'],
        'email' => $userData['ADMIN_EMAIL'],
        'profile' => $userData['ADMIN_PROFILE_IMAGE'],
        'name' => $userData['ADMIN_NAME'],
        'subscription' => $subscriptionText,
        'number' => $userData['ADMIN_NUMBER'],
        'purchasedBooks' => $purchasedBooks,
        'publishedBooks' => $publishedBooks,
        'publishedMagazines' => $publishedMagazines,
        'publishedNewspapers' => $publishedNewspapers,

    ]);
    exit;
}

}
