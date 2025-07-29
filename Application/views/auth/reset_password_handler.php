<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../Config/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = isset($_POST['token']) ? htmlspecialchars(trim($_POST['token'])) : "";
    $password = isset($_POST['new_password']) ? trim($_POST['new_password']) : "";
    $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : "";

    if (empty($token)) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Invalid reset link, please try again.',
            'title' => 'Missing Credentials'
        ];
        header("Location: /forgot-password");
        exit;
    }

    if (empty($password) || empty($confirmPassword)) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Password is required.',
            'title' => 'Missing Credentials'
        ];
        header("Location: /forgot-password");
        exit;
    }

    if ($password !== $confirmPassword) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Passwords do not match. Please try again.',
        ];
        header("Location: /reset_password/$token");
        exit;
    }


    // validate token
    $sql = "SELECT RESETLINK FROM users WHERE RESETLINK = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Database error: ' . mysqli_error($conn)];
        header("Location: /signup");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (!mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid or expired token, please try again.'];
        header("Location: /forgot-password");
        exit;
    }
    mysqli_stmt_close($stmt);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (updatePasswordWithToken($conn, $hashedPassword, $token)) {
        session_regenerate_id(true);

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Your password has been reset successfully. You may now login.'
        ];
        unset($hashedPassword, $token);
        header("Location: /login");
        exit();
    } else {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'title' => 'Error',
            'message' => 'Password reset failed. The link may have expired or was already used.'
        ];
        header("Location: /forgot-password");
        exit();
    }
}

/**
 * Updates a user's password using a reset token
 * 
 * @param mysqli $conn Database connection
 * @param string $hashedPassword The new hashed password
 * @param string $token The reset token from the user
 * @return bool True if update succeeded, false if failed
 */
function updatePasswordWithToken($conn, $hashedPassword, $token)
{
    $resetTokenSql = "UPDATE users SET RESETLINK = '', ADMIN_PASSWORD = ? WHERE RESETLINK = ?";

    $stmt = mysqli_prepare($conn, $resetTokenSql);
    if (!$stmt) {
        error_log("Password reset preparation failed: " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $token);

    $success = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    return ($success && $affected > 0);
}
