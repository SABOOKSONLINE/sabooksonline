<?php

/**
 * Class UserModel
 * Handles user-related data operations.
 */
class UserModel {
    /**
     * @var mysqli The database connection object
     */
    private $conn;

    /**
     * UserModel constructor.
     *
     * @param mysqli $dbConn An active MySQLi database connection
     */
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findUserByEmail($email) {
        // $email = mysqli_real_escape_string($this->conn, $email);
        $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$email' LIMIT 1;";
        return mysqli_query($this->conn, $sql);
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public function startSession($userData) {
        session_start();
        $_SESSION['ADMIN_ID'] = $userData['ADMIN_ID'];
        $_SESSION['ADMIN_SUBSCRIPTION'] = $userData['ADMIN_SUBSCRIPTION'];
        $_SESSION['ADMIN_NAME'] = $userData['ADMIN_NAME'];
        $_SESSION['ADMIN_EMAIL'] = $userData['ADMIN_EMAIL'];
        $_SESSION['ADMIN_NUMBER'] = $userData['ADMIN_NUMBER'];
        $_SESSION['ADMIN_WEBSITE'] = $userData['ADMIN_WEBSITE'];
        $_SESSION['ADMIN_BIO'] = $userData['ADMIN_BIO'];
        $_SESSION['ADMIN_TYPE'] = $userData['ADMIN_TYPE'];
        $_SESSION['ADMIN_FACEBOOK'] = $userData['ADMIN_FACEBOOK'];
        $_SESSION['ADMIN_TWITTER'] = $userData['ADMIN_TWITTER'];
        $_SESSION['ADMIN_LINKEDIN'] = $userData['ADMIN_LINKEDIN'];
        $_SESSION['ADMIN_GOOGLE'] = $userData['ADMIN_GOOGLE'];
        $_SESSION['ADMIN_INSTAGRAM'] = $userData['ADMIN_INSTAGRAM'];
        $_SESSION['ADMIN_CUSTOMER_PLESK'] = $userData['ADMIN_PINTEREST'];
        $_SESSION['ADMIN_PASSWORD'] = $userData['ADMIN_PASSWORD'];
        $_SESSION['ADMIN_DATE'] = $userData['ADMIN_DATE'];
        $_SESSION['ADMIN_VERIFICATION_LINK'] = $userData['ADMIN_VERIFICATION_LINK'];
        $_SESSION['ADMIN_PROFILE_IMAGE'] = $userData['ADMIN_PROFILE_IMAGE'];
        $_SESSION['ADMIN_USERKEY'] = $userData['ADMIN_USERKEY'];
        $_SESSION['ADMIN_USER_STATUS'] = $userData['ADMIN_USER_STATUS'];
        $_SESSION['ADMIN_SERVICES'] = $userData['ADMIN_SERVICES'];
    
    }


    

    /**
     * Retrieves a user by either their admin name or admin user key.
     * Converts dashes to spaces to allow for URL-friendly content IDs.
     *
     * @param string $contentid The admin name or user key, potentially URL slugged
     * @return array|null Returns an associative array of user data if found, otherwise null
     */
    public function getUserByNameOrKey($contentid) {
        $contentid = str_replace('-', ' ', $contentid);

        $sql = "SELECT * FROM users WHERE ADMIN_NAME = ? OR ADMIN_USERKEY = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $contentid, $contentid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc(); // Only using first match
    }

    /**
     * Fetches all posts associated with a specific user key.
     *
     * @param string $userKey The unique user ID (USERID) from the posts table
     * @return array An array of associative arrays representing posts
     */
    public function getPostsByUserKey($userKey) {
        $sql = "SELECT * FROM posts WHERE USERID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        return $posts;
    }
}
