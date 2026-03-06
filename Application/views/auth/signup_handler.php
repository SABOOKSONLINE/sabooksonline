<?php
session_start();
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/mailer.php";
require_once __DIR__ . "/../../../load_env.php";

$secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? getenv('RECAPTCHA_SECRET_KEY');
$captcha = $_POST['g-recaptcha-response'] ?? '';

if (!$captcha) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Please verify that you are not a robot.'];
    header("Location: /signup");
    exit;
}

$url = "https://www.google.com/recaptcha/api/siteverify";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'secret'   => $secretKey,
    'response' => $captcha,
    'remoteip' => $_SERVER['REMOTE_ADDR']
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response);

if (!$responseData || !$responseData->success) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Captcha verification failed. Please try again.'];
    header("Location: /signup");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["reg_name"] ?? '');
    $phone = trim($_POST["reg_phone"] ?? '');
    $email = trim($_POST["reg_mail"] ?? '');
    $confirm_email = trim($_POST["confirm_mail"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    $token = bin2hex(random_bytes(16));

    if (empty($name) || empty($phone) || empty($email) || empty($confirm_email) || empty($password) || empty($confirm_password)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'All fields are required.'];
        header("Location: /signup");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid email format.'];
        header("Location: /signup");
        exit;
    }

    if ($email !== $confirm_email) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Emails do not match.'];
        header("Location: /signup");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Passwords do not match.'];
        header("Location: /signup");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "SELECT ADMIN_ID FROM users WHERE ADMIN_EMAIL = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'An account with that email already exists.'];
        header("Location: /signup");
        exit;
    }
    mysqli_stmt_close($stmt);

    $userKey = uniqid('', true);
    $subscription = 'Free';
    $profile = "https://www.vecteezy.com/free-vector/default-profile-picture";
    $status = "Unverified";

    $sql = "INSERT INTO users (
        ADMIN_NAME, ADMIN_EMAIL, ADMIN_NUMBER, ADMIN_PASSWORD, 
        ADMIN_USERKEY, ADMIN_SUBSCRIPTION, ADMIN_VERIFICATION_LINK, ADMIN_PROFILE_IMAGE, ADMIN_USER_STATUS, RESETLINK, USER_STATUS, verify_token
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssss",
        $name,
        $email,
        $phone,
        $hashedPassword,
        $userKey,
        $subscription,
        $userKey,
        $profile,
        $status,
        $userKey,
        $status,
        $token
    );

    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Registration failed. Please try again.'];
        header("Location: /signup");
        exit;
    }
    mysqli_stmt_close($stmt);

    try {
        require_once __DIR__ . "/../../helpers/NotificationHelper.php";
        NotificationHelper::sendWelcomeNotification($email, $name, $conn);
    } catch (Exception $e) {
        error_log("Failed to send welcome notification: " . $e->getMessage());
    }

    mysqli_close($conn);

    $verifyLink = "https://" . $_SERVER['HTTP_HOST'] . "/verify/{$token}";
    sendVerificationEmail($email, $name, $verifyLink);
    header("Location: /registration_success");
    exit;
} else {
    header("Location: /signup");
    exit;
}
