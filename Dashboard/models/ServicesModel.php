<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ServiceModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function getServicesByUserId($userId)
    {
        $sql = "SELECT * FROM services WHERE USERID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $services = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $services;
    }

    public function getServiceById($userId, $id)
    {
        $sql = "SELECT * FROM services WHERE USERID = ? AND ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $userId, $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $service = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $service;
    }

    public function insertService($data)
    {
        $sql = "INSERT INTO services (
                    SERVICE, USERID, STATUS, CREATED, MODIFIED, MINIMUM, MAXIMUM
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssssss",
            $data['service'],
            $data['userid'],
            $data['status'],
            $data['created'],
            $data['modified'],
            $data['minimum'],
            $data['maximum']
        );

        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $success;
    }

    public function updateService($id, $data)
    {
        $sql = "UPDATE services SET 
                SERVICE = ?, 
                STATUS = ?, 
                MODIFIED = ?, 
                MINIMUM = ?, 
                MAXIMUM = ? 
            WHERE ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssddi",
            $data['service'],
            $data['status'],
            $data['modified'],
            $data['minimum'],
            $data['maximum'],
            $id
        );

        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            die("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $success && $affectedRows > 0;
    }



    public function deleteService($id)
    {
        $sql = "DELETE FROM services WHERE ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        $success = mysqli_stmt_execute($stmt);

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $affectedRows > 0;
    }
}
