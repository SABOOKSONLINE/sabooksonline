<?php
require_once __DIR__ . "/../Core/Conn.php";
ob_start();
session_start();

function getUserBy($conn, $id): array
{
    $sql = "SELECT ADMIN_ID, ADMIN_SUBSCRIPTION, ADMIN_PROFILE_IMAGE, ADMIN_USERKEY, ADMIN_USER_STATUS, ADMIN_EMAIL 
            FROM users WHERE ADMIN_ID = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $rows[0] ?? [];
}

function isValidAdmin($email): bool
{
    $admins = ["tebogo@sabooksonline.co.za"];
    return in_array($email, $admins, true);
}

function setUserSession($data)
{
    $_SESSION['ADMIN_ID'] = $data['ADMIN_ID'];
    $_SESSION['ADMIN_SUBSCRIPTION'] = $data['ADMIN_SUBSCRIPTION'];
    $_SESSION['ADMIN_PROFILE_IMAGE'] = $data['ADMIN_PROFILE_IMAGE'];
    $_SESSION['ADMIN_USERKEY'] = $data['ADMIN_USERKEY'];
    $_SESSION['ADMIN_USER_STATUS'] = $data['ADMIN_USER_STATUS'];
    $_SESSION['ADMIN_EMAIL'] = $data['ADMIN_EMAIL'];
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid request");
}

if (!isset($_SESSION['ADMIN_EMAIL']) || !isValidAdmin($_SESSION['ADMIN_EMAIL'])) {
    die("You don’t have permission to impersonate users");
}

$userData = getUserBy($conn, $id);
if (empty($userData)) {
    die("User not found");
}

session_unset();
session_destroy();

session_start();
setUserSession($userData);

$allowedPlans = ['pro', 'premium', 'standard', 'deluxe'];
$subscriptionPlan = $_SESSION['ADMIN_SUBSCRIPTION'];

if (!in_array(strtolower($subscriptionPlan), $allowedPlans)) {
    header("Location: /dashboards/bookshelf");
    exit;
} else {
    header("Location: /dashboards");
    exit;
}
