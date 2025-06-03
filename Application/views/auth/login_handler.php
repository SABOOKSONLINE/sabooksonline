<?php
session_start();
require_once __DIR__ . "/../../Config/connection.php";
require_once __DIR__ . "/../util/helpers/session_helper.php"; // adjust this path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["log_email"] ?? '');
    $password = $_POST["log_pwd2"] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email and password are required.'];
        header("Location: /login");
        exit;
    }

    // Prepare statement
    $stmt = mysqli_prepare($conn, "SELECT ADMIN_ID, ADMIN_PASSWORD FROM users WHERE ADMIN_EMAIL = ?");
    if (!$stmt) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Database error: ' . mysqli_error($conn)];
        header("Location: /login");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_bind_result($stmt, $userId, $hashedPassword);
        mysqli_stmt_fetch($stmt);

        if (password_verify($password, $hashedPassword)) {
            // Set full session using helper
            if (setUserSession($conn, $email)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Login successful! Welcome back.'];
                header("Location: /dashboards");
                exit;
            } else {
                $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Login succeeded, but session could not be set.'];
                header("Location: /login");
                exit;
            }
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Incorrect password.'];
            header("Location: /login");
            exit;
        }
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email not found.'];
        header("Location: /login");
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: /login");
    exit;
}
