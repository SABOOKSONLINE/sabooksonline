<?php
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/default_mailer.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $token = $token = bin2hex(random_bytes(16));
    $resetLink = "https://" . $_SERVER["HTTP_HOST"] . "/reset-password/{$token}";
    $message = "Click the link to reset your password:";

    try {
        insertResetLink($conn, $email, $token);
        sendEmail($email, $resetLink, $message);
        header("Location: /registration_success");
        exit;
    } catch (Exception $e) {
        $e->getMessage();
        exit;
    }
}

function insertResetLink($conn, $email, $token)
{

    $sql = "UPDATE users SET RESETLINK = ? WHERE ADMIN_EMAIL = ?";
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
