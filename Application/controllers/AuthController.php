<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . "/../views/auth/mailer.php";



class AuthController {

    private $userModel;
        private $conn;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
        $this->conn = $conn;
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

public function signup($name, $email, $password) {
    // Check if user exists
    $stmt = $this->conn->prepare("SELECT ADMIN_ID FROM users WHERE ADMIN_EMAIL = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        http_response_code(409);
        echo json_encode(["message" => "Email already exists"]);
        return;
    }
    $stmt->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Extra values you want
    $userKey = uniqid('', true);
    $subscription = 'Free';
    $verificationLink = $userKey; // you keep this for internal usage
    $profile = "https://www.vecteezy.com/free-vector/default-profile-picture";
    $token = bin2hex(random_bytes(16)); // actual email verify token
    $status = "Unverified";

    $sql = "INSERT INTO users (
        ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD, 
        ADMIN_USERKEY, ADMIN_SUBSCRIPTION, ADMIN_VERIFICATION_LINK, 
        ADMIN_PROFILE_IMAGE, ADMIN_USER_STATUS, 
        RESETLINK, USER_STATUS, verify_token
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssss",
        $name,
        $email,
        $hashedPassword,
        $userKey,
        $subscription,
        $verificationLink,
        $profile,
        $status,
        $userKey,  // you’re reusing $userKey as RESETLINK
        $status,   // USER_STATUS (also Unverified)
        $token     // real verification token
    );

    if ($stmt->execute()) {
        // ✅ token link goes into email
        $verifyLink = "https://sabooksonline.co.za/verify/" . urlencode($token);
        sendVerificationEmail($email, $verifyLink, $name, "SABO Mobile App");

        echo json_encode([
            "message" => "Signup successful. Please verify email.",
            "verify_token" => $token
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Signup failed"]);
    }
    $stmt->close();
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
    $publishedAcademicBooks = $this->userModel->publishedAcademicBooks($userData['ADMIN_ID']);


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
        'publishedAcademicBooks' => $publishedAcademicBooks,


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
    $publishedAcademicBooks = $this->userModel->publishedAcademicBooks($userData['ADMIN_ID']);


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
        'publishedAcademicBooks' => $publishedAcademicBooks,


    ]);
    exit;
}

}
