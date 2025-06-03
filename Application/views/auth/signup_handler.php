<?php
session_start();
require_once __DIR__ . "/../../Config/connection.php";

function setUserSession(mysqli $conn, string $email): bool
{
    $stmt = mysqli_prepare($conn, "SELECT ADMIN_ID, ADMIN_SUBSCRIPTION, ADMIN_PROFILE_IMAGE, ADMIN_USERKEY, ADMIN_USER_STATUS 
                                   FROM users WHERE ADMIN_EMAIL = ?");
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userData = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$userData) return false;

    $_SESSION['ADMIN_ID'] = $userData['ADMIN_ID'];
    $_SESSION['ADMIN_SUBSCRIPTION'] = $userData['ADMIN_SUBSCRIPTION'];
    $_SESSION['ADMIN_PROFILE_IMAGE'] = $userData['ADMIN_PROFILE_IMAGE'];
    $_SESSION['ADMIN_USERKEY'] = $userData['ADMIN_USERKEY'];
    $_SESSION['ADMIN_USER_STATUS'] = $userData['ADMIN_USER_STATUS'];

    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["reg_name"] ?? '');
    $phone = trim($_POST["reg_phone"] ?? '');
    $email = trim($_POST["reg_mail"] ?? '');
    $confirm_email = trim($_POST["confirm_mail"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    // Validate fields
    if (
        empty($name) || empty($phone) || empty($email) || empty($confirm_email)
        || empty($password) || empty($confirm_password)
    ) {
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

    // Check if user already exists
    $stmt = mysqli_prepare($conn, "SELECT ADMIN_ID FROM users WHERE ADMIN_EMAIL = ?");
    if (!$stmt) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Database error: ' . mysqli_error($conn)];
        header("Location: /signup");
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'An account with that email already exists.'];
        header("Location: /signup");
        exit();
    }
    mysqli_stmt_close($stmt);

    // Insert new user
    $userKey = uniqid('', true);
    $subscription = 'Free';
    $verificationLink = $userKey;
    $profile = "https://www.vecteezy.com/free-vector/default-profile-picture";
    $status = "active";

    $sql = "INSERT INTO users (
        ADMIN_NAME, ADMIN_EMAIL, ADMIN_NUMBER, ADMIN_PASSWORD, 
        ADMIN_USERKEY, ADMIN_SUBSCRIPTION, ADMIN_VERIFICATION_LINK, ADMIN_PROFILE_IMAGE, ADMIN_USER_STATUS, RESETLINK, USER_STATUS
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Database insert error: ' . mysqli_error($conn)];
        header("Location: /signup");
        exit;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssss",
        $name,
        $email,
        $phone,
        $hashedPassword,
        $userKey,
        $subscription,
        $verificationLink,
        $profile,
        $status,
        $userKey,
        $status
    );

    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Registration failed. Please try again.'];
        exit;
    }

    mysqli_stmt_close($stmt);

    // Set session if user data was fetched successfully
    if (setUserSession($conn, $email)) {
        mysqli_close($conn);
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Registration successful. Welcome!'];
        header("Location: /dashboards");
        exit;
    } else {
        mysqli_close($conn);
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Registered, but session could not be started. Please log in manually.'];
        header("Location: /login");
        exit;
    }
} else {
    header("Location: /signup");
    exit;
}
