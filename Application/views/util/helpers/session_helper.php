<?php
// session_helper.php

// Only set cookie params and start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    $isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);

    $cookieParams = [
        'lifetime' => 0,
        'path' => '/',
        'domain' => $isLocal ? '' : '.sabooksonline.co.za',
        'secure' => !$isLocal,
        'httponly' => true,
        'samesite' => 'Lax',
    ];

    session_set_cookie_params($cookieParams);
    session_start();
}

/**
 * Sets user session data
 * @param mysqli $conn Active database connection
 * @param string $email User email to look up
 * @return bool True on success, false on failure
 */
function setUserSession(mysqli $conn, string $email): bool
{
    // Verify connection is still open
    if (!$conn || $conn->connect_error) {
        error_log("Database connection error in setUserSession()");
        return false;
    }

    $stmt = mysqli_prepare($conn, "SELECT ADMIN_ID, ADMIN_EMAIL, ADMIN_SUBSCRIPTION, 
                                  ADMIN_PROFILE_IMAGE, ADMIN_USERKEY, ADMIN_USER_STATUS 
                                  FROM users WHERE ADMIN_EMAIL = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }

    $result = mysqli_stmt_get_result($stmt);
    $userData = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$userData) {
        return false;
    }

    // Set all session variables
    $_SESSION['ADMIN_ID'] = $userData['ADMIN_ID'];
    $_SESSION['ADMIN_EMAIL'] = $userData['ADMIN_EMAIL']; // Added missing field
    $_SESSION['ADMIN_SUBSCRIPTION'] = $userData['ADMIN_SUBSCRIPTION'];
    $_SESSION['ADMIN_PROFILE_IMAGE'] = $userData['ADMIN_PROFILE_IMAGE'];
    $_SESSION['ADMIN_USERKEY'] = $userData['ADMIN_USERKEY'];
    $_SESSION['ADMIN_USER_STATUS'] = $userData['ADMIN_USER_STATUS'];

    return true;
}
