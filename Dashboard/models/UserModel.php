<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class UserModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * Get user by ID
     * @param string $userId
     * @return array|null
     */
    public function getUserById($userId)
    {
        try {
            $sql = "SELECT * FROM users WHERE ADMIN_VERIFICATION_LINK = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . mysqli_error($this->conn));
                return null;
            }
            mysqli_stmt_bind_param($stmt, "s", $userId);
            if (!mysqli_stmt_execute($stmt)) {
                error_log("Failed to execute statement: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return null;
            }

            $result = mysqli_stmt_get_result($stmt);
            if (!$result) {
                error_log("Failed to get result: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return null;
            }

            if (mysqli_num_rows($result) === 0) {
                mysqli_stmt_close($stmt);
                return null;
            }

            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            return $user;
        } catch (Exception $e) {
            error_log("Error in getUserById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update user data by ID
     * @param string $userId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateUser($userId, $data)
    {
        try {
            $sql = "UPDATE users SET 
                        ADMIN_NAME = ?, 
                        ADMIN_NUMBER = ?, 
                        ADMIN_WEBSITE = ?, 
                        ADMIN_GOOGLE = ?, 
                        ADMIN_BIO = ?, 
                        ADMIN_FACEBOOK = ?, 
                        ADMIN_INSTAGRAM = ?, 
                        ADMIN_TWITTER = ?, 
                        ADMIN_LINKEDIN = ?, 
                        ADMIN_PASSWORD = ?, 
                        ADMIN_USER_STATUS = ?, 
                        ADMIN_TYPE = ?, 
                        ADMIN_DATE = NOW() 
                    WHERE ADMIN_ID = ?";

            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . mysqli_error($this->conn));
                return false;
            }

            mysqli_stmt_bind_param(
                $stmt,
                "sssssssssssss",
                $data['ADMIN_NAME'],
                $data['ADMIN_NUMBER'],
                $data['ADMIN_WEBSITE'],
                $data['ADMIN_GOOGLE'],
                $data['ADMIN_BIO'],
                $data['ADMIN_FACEBOOK'],
                $data['ADMIN_INSTAGRAM'],
                $data['ADMIN_TWITTER'],
                $data['ADMIN_LINKEDIN'],
                $data['ADMIN_PASSWORD'],
                $data['ADMIN_USER_STATUS'],
                $data['ADMIN_TYPE'],
                $userId
            );

            $result = mysqli_stmt_execute($stmt);
            if (!$result) {
                error_log("Failed to execute statement: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return false;
            }

            $affectedRows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            return $affectedRows > 0;
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
}
