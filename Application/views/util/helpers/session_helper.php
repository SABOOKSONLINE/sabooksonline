<?php
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
    $_SESSION['ADMIN_EMAIL'] = $userData['ADMIN_EMAIL'];


    return true;
}
