<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../util/helpers/session_helper.php";
require_once __DIR__ . "/mailer.php";
require_once __DIR__ . "/../../../load_env.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /login");
    exit;
}

$recaptchaSecret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? getenv('RECAPTCHA_SECRET_KEY');
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Please confirm you are not a robot.',
        'title' => 'Captcha Required'
    ];
    header("Location: /login");
    exit;
}

$url = "https://www.google.com/recaptcha/api/siteverify";
$data = [
    'secret'   => $recaptchaSecret,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR']
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$verify = curl_exec($ch);
curl_close($ch);

$captchaSuccess = json_decode($verify);

if (!$captchaSuccess || !$captchaSuccess->success) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Captcha verification failed. Please try again.',
        'title' => 'Captcha Error'
    ];
    header("Location: /login");
    exit;
}

$email = trim($_POST["log_email"] ?? '');
$password = $_POST["log_pwd2"] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Email and password are required.',
        'title' => 'Missing Credentials'
    ];
    header("Location: /login");
    exit;
}

try {
    $stmt = mysqli_prepare($conn, "SELECT ADMIN_ID, ADMIN_PASSWORD, ADMIN_NAME, USER_STATUS FROM users WHERE ADMIN_EMAIL = ?");

    if (!$stmt) {
        throw new Exception('Database preparation error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Database execution error: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) !== 1) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Account not found.',
            'title' => 'Login Failed'
        ];
        header("Location: /login");
        exit;
    }

    mysqli_stmt_bind_result($stmt, $userId, $hashedPassword, $name, $userStatus);
    mysqli_stmt_fetch($stmt);

    if (!password_verify($password, $hashedPassword)) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Incorrect password.',
            'title' => 'Login Failed'
        ];
        header("Location: /login");
        exit;
    }

    if ($userStatus !== "Verified") {
        $token = bin2hex(random_bytes(16));

        if (!insertUserToken($conn, $email, $token)) {
            throw new Exception('Failed to generate verification token');
        }

        $verifyLink = "https://" . $_SERVER['HTTP_HOST'] . "/verify/{$token}";
        sendVerificationEmail($email, $name, $verifyLink);

        $_SESSION['alert'] = [
            'type' => 'info',
            'message' => 'Verification email sent. Please check your inbox.',
            'title' => 'Account Not Verified'
        ];

        header("Location: /registration_success");
        exit;
    }

    if (!setUserSession($conn, $email)) {
        throw new Exception('Failed to set user session');
    }

    try {
        require_once __DIR__ . "/../../helpers/NotificationHelper.php";
        $checkStmt = $conn->prepare("SELECT created_at FROM users WHERE ADMIN_EMAIL = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $userResult = $checkStmt->get_result();
        $userData = $userResult->fetch_assoc();
        $checkStmt->close();

        if ($userData && isset($userData['created_at'])) {
            $createdAt = strtotime($userData['created_at']);
            $hoursSinceSignup = (time() - $createdAt) / 3600;
            if ($hoursSinceSignup < 24) {
                NotificationHelper::sendWelcomeNotification($email, $name, $conn);
            }
        }
    } catch (Exception $e) {
        error_log("Failed to send welcome notification on login: " . $e->getMessage());
    }

    $redirectUri = $_SESSION["redirect_uri"] ?? '';
    $prefixes = ["/library", "/sell", "/membership"];
    $matched = false;

    foreach ($prefixes as $prefix) {
        if (str_starts_with($redirectUri, $prefix)) {
            $matched = true;
            break;
        }
    }

    if ($matched) {
        header("Location: " . $redirectUri);
    } else {
        header("Location: /dashboards");
    }
    exit;
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'A system error occurred. Please try again later.',
        'title' => 'System Error'
    ];
    header("Location: /login");
    exit;
} finally {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
}

function insertUserToken($conn, $email, $token): bool
{
    $sql = "UPDATE users SET verify_token = ? WHERE ADMIN_EMAIL = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("Token preparation failed: " . mysqli_error($conn));
        return false;
    }
    mysqli_stmt_bind_param($stmt, "ss", $token, $email);
    $success = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $affected > 0;
}
