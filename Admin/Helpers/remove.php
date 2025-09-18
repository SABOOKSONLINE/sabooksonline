<?php
require_once __DIR__ . "/../Core/Conn.php";
require_once __DIR__ . "/sessionAlerts.php";

function createDeletedUsersTable(mysqli $conn): bool
{
    $sql = "CREATE TABLE IF NOT EXISTS deleted_users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usr_name VARCHAR(65) NOT NULL,
                usr_email VARCHAR(255) NOT NULL,
                usr_public_key VARCHAR(65) NOT NULL,
                usr_subscription VARCHAR(65) NOT NULL,
                deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

function getUserBy(mysqli $conn, int $id): array
{
    $sql = "SELECT ADMIN_NAME, ADMIN_EMAIL, ADMIN_USERKEY, ADMIN_SUBSCRIPTION
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

function saveUserDetails(mysqli $conn, array $data): bool
{
    $sql = "INSERT IGNORE INTO deleted_users (usr_name, usr_email, usr_public_key, usr_subscription)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        'ssss',
        $data['usr_name'],
        $data['usr_email'],
        $data['usr_public_key'],
        $data['usr_subscription']
    );

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}

function deleteUserBy(mysqli $conn, int $id): bool
{
    $sql = "DELETE FROM users WHERE ADMIN_ID = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        throw new Exception("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}


if (isset($_GET["id"])) {
    $user = getUserBy($conn, (int) $_GET["id"]);

    if (!empty($user)) {
        $deletedUser = [
            'usr_name'         => $user['ADMIN_NAME'],
            'usr_email'        => $user['ADMIN_EMAIL'],
            'usr_public_key'   => $user['ADMIN_USERKEY'],
            'usr_subscription' => $user['ADMIN_SUBSCRIPTION'],
        ];

        createDeletedUsersTable($conn);

        if (saveUserDetails($conn, $deletedUser)) {
            if (deleteUserBy($conn, (int) $_GET["id"])) {
                setAlert("success", "User archived and deleted successfully.");
            } else {
                setAlert("warning", "User archived, but failed to delete.");
            }
        } else {
            setAlert("danger", "Failed to archive user.");
        }
    } else {
        setAlert("info", "No user found with that ID.");
    }

    header("Location: /admin/users");
    exit;
}
