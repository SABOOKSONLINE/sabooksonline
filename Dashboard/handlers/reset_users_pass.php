<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../database/connection.php";

    $email = trim($_POST['email'] ?? '');
    $newPassword = trim($_POST['user_new_password'] ?? '');
    $confirmPassword = trim($_POST['user_confrm_new_password'] ?? '');

    $verified_emails = ["tebogo@sabooksonline.co.za", "pearl@sabooksonline.co.za", ""];

    session_start();

    if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'All fields are required.'
        ];
        header("Location: /dashboards/reset_password");
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Passwords do not match.'
        ];
        header("Location: /dashboards/reset_password");
        exit;
    }

    if (!validateAdminId($conn, $_GET['adminId'], $verified_emails)) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Access denied: Your admin account is not verified.'
        ];
        header("Location: /dashboards/reset_password");
        exit;
    }

    resetPassword($conn, $email, $newPassword);
}

function resetPassword($conn, $userEmail, $userPassword)
{
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET ADMIN_PASSWORD = ? WHERE ADMIN_EMAIL  = ?");
    $stmt->bind_param("ss", $hashedPassword, $userEmail);

    if ($stmt->execute()) {
        $_SESSION['success'] = "<b>" . $userEmail . "</b>: password updated successfully.";
    } else {
        $_SESSION['danger'] = "Failed to update password. Please try again."  . $stmt->error;
    }

    $stmt->close();
    header("Location: /dashboards/reset_password");
    exit;
}

function validateAdminId($conn, $adminId, $verified_emails)
{
    $stmt = $conn->prepare("SELECT ADMIN_EMAIL FROM users WHERE ADMIN_ID = ?");
    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("i", $adminId);
    $stmt->execute();

    $email = null;
    $stmt->bind_result($email);

    if ($stmt->fetch() && in_array($email, $verified_emails)) {
        $stmt->close();
        return true;
    }

    $stmt->close();
    return false;
}
