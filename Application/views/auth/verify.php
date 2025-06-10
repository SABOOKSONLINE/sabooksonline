<?php
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../util/helpers/session_helper.php";

session_start();
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT * FROM users WHERE verify_token = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if ($user['USER_STATUS'] == "Verified") {
            $_SESSION['alert'] = ['type' => 'info', 'message' => 'Your email is already verified.'];
            header("Location: /login");
            exit;
        }

        $verifiedStatus = 'Verified';

        $updateSql = "UPDATE users SET USER_STATUS = ?, verify_token = NULL WHERE verify_token = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ss", $verifiedStatus, $token);
        mysqli_stmt_execute($updateStmt);

        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Email verified successfully. You may now log in.'];
        header("Location: /dashboards");
        exit;
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Invalid or expired verification link.'];
        header("Location: /signup");
        exit;
    }
} else {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'No verification token provided.'];
    header("Location: /signup");
    exit;
}
