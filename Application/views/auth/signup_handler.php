<?php
session_start();
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/mailer.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["reg_name"] ?? '');
    $phone = trim($_POST["reg_phone"] ?? '');
    $email = trim($_POST["reg_mail"] ?? '');
    $confirm_email = trim($_POST["confirm_mail"] ?? '');
    $password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    $token = bin2hex(random_bytes(16));

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
        exit;
    }
    mysqli_stmt_close($stmt);

    // Insert new user
    $userKey = uniqid('', true);
    $subscription = 'Free';
    $verificationLink = $userKey;
    $profile = "https://www.vecteezy.com/free-vector/default-profile-picture";
    $status = "Unverified";

    $sql = "INSERT INTO users (
        ADMIN_NAME, ADMIN_EMAIL, ADMIN_NUMBER, ADMIN_PASSWORD, 
        ADMIN_USERKEY, ADMIN_SUBSCRIPTION, ADMIN_VERIFICATION_LINK, ADMIN_PROFILE_IMAGE, ADMIN_USER_STATUS, RESETLINK, USER_STATUS, verify_token
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Database insert error: ' . mysqli_error($conn)];
        header("Location: /signup");
        exit;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssssss",
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
        $status,
        $token
    );

    if (!mysqli_stmt_execute($stmt)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Registration failed. Please try again.'];
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);


    $verifyLink = "https://" . $_SERVER['HTTP_HOST'] . "/verify/{$token}";
    sendVerificationEmail($email,$name, $verifyLink);
    header("Location: /registration_success");
    exit;
} else {
    header("Location: /signup");
    exit;
}
