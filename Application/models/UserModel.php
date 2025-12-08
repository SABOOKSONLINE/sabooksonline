<?php

/**
 * Class UserModel
 * Handles user-related data operations.
 */
class UserModel
{
    /**
     * @var mysqli The database connection object
     */
    private $conn;

    /**
     * UserModel constructor.
     *
     * @param mysqli $dbConn An active MySQLi database connection
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getCreators($updatedSince = null)
    {
        // Base SQL query
        $sql = "SELECT c.* FROM users c
            JOIN posts b ON c.ADMIN_USERKEY = b.USERID";

        // If a date is provided, apply the delta sync condition
        if ($updatedSince) {
            // Convert the ISO 8601 string from the client into a PHP DateTime object
            $lastUpdatedDateTime = new DateTime($updatedSince);
            $formattedDate = $lastUpdatedDateTime->format('Y-m-d H:i:s');

            $sql .= " WHERE c.updated_at > ?";
        }

        // Group and order
        $sql .= " GROUP BY c.ADMIN_USERKEY ORDER BY c.updated_at ASC";

        // Prepare and bind
        $stmt = $this->conn->prepare($sql);

        if ($updatedSince) {
            $stmt->bind_param("s", $formattedDate);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are any results to return
        if ($result->num_rows === 0) {
            return [];
        }

        $creators = [];
        while ($row = $result->fetch_assoc()) {
            // Important: Convert the database timestamp to a universal ISO 8601 string
            // before returning it to the app
            $row['updated_at'] = (new DateTime($row['updated_at']))->format(DateTime::ISO8601);
            $creators[] = $row;
        }

        return $creators;
    }





    public function getPublishedBookIds($userKey)
    {
        $sql = "
        SELECT 
            id
        FROM posts
        WHERE USERID = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookIds = [];
        while ($row = $result->fetch_assoc()) {
            $bookIds[] = $row['id'];
        }

        $stmt->close();
        return $bookIds;
    }

    public function getPublishedMagazines($userKey)
    {
        $sql = "
        SELECT 
            id
        FROM magazines
        WHERE publisher_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookIds = [];
        while ($row = $result->fetch_assoc()) {
            $bookIds[] = $row['id'];
        }

        $stmt->close();
        return $bookIds;
    }

    public function getPublishedNewspapers($userKey)
    {
        $sql = "
        SELECT 
            id
        FROM newspapers
        WHERE publisher_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookIds = [];
        while ($row = $result->fetch_assoc()) {
            $bookIds[] = $row['id'];
        }

        $stmt->close();
        return $bookIds;
    }


    public function publishedAcademicBooks($userKey)
    {
        $sql = "
        SELECT 
            id
        FROM academic_books
        WHERE publisher_id = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userKey);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookIds = [];
        while ($row = $result->fetch_assoc()) {
            $bookIds[] = $row['id'];
        }

        $stmt->close();
        return $bookIds;
    }


    public function getPurchasedBookIdsAndFormats($email)
    {
        $sql = "
        SELECT 
            book_id AS id,
            format
        FROM book_purchases
        WHERE user_email = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        $stmt->close();
        return $books;
    }


    public function getPurchasedBooksByUserEmail($email)
    {
        $sql = "
            SELECT 
                b.id AS ID,
                b.cover AS COVER,
                b.title AS TITLE,
                b.publisher AS PUBLISHER,
                b.description AS DESCRIPTION,
                b.retailprice AS RETAILPRICE,
                b.pdfurl AS PDF_URL,
                bp.format AS FORMAT
            FROM book_purchases bp
            JOIN posts b ON bp.book_id = b.id
            WHERE bp.user_email = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        $stmt->close();
        return $books;
    }

    public function findUserByEmail($email)
    {
        // $email = mysqli_real_escape_string($this->conn, $email);
        $sql = "SELECT * FROM users WHERE ADMIN_EMAIL = '$email' LIMIT 1;";
        return mysqli_query($this->conn, $sql);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function startSession($userData)
    {
        if (session_status() === PHP_SESSION_NONE) {
            $cookieDomain = ".sabooksonline.co.za";
            session_set_cookie_params(0, '/', $cookieDomain);
            session_start();
        }


        $_SESSION['ADMIN_ID'] = $userData['ADMIN_ID'];
        $_SESSION['ADMIN_SUBSCRIPTION'] = $userData['ADMIN_SUBSCRIPTION'];
        $_SESSION['ADMIN_PROFILE_IMAGE'] = $userData['ADMIN_PROFILE_IMAGE'];
        $_SESSION['ADMIN_USERKEY'] = $userData['ADMIN_USERKEY'];
        $_SESSION['ADMIN_USER_STATUS'] = $userData['ADMIN_USER_STATUS'];
        $_SESSION['ADMIN_EMAIL'] = $userData['ADMIN_EMAIL'];
    }




    /**
     * Retrieves a user by either their admin name or admin user key.
     * Converts dashes to spaces to allow for URL-friendly content IDs.
     *
     * @param string $contentid The admin name or user key, potentially URL slugged
     * @return array|null Returns an associative array of user data if found, otherwise null
     */
    public function getUserByNameOrKey($contentid)
    {
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

    public function updateUserPlanRoyalties($userId, $planName, $billingCycle)
    {

        $sql = "UPDATE users SET admin_subscription = ?, billing_cycle = ?, subscription_status = 'royalties' WHERE ADMIN_USERKEY = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $planName, $billingCycle, $userId);
        return $stmt->execute();
    }

    public function updateUserPlanMonthly($userId, $planName, $billingCycle)
    {

        $sql = "UPDATE users SET admin_subscription = ?, billing_cycle = ?, subscription_status = ? WHERE ADMIN_USERKEY = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $planName, $billingCycle, $planName, $userId);
        return $stmt->execute();
    }

    //signup user
    public function insertGoogleUser($name, $email, $profileImage, $googleId)
    {

        $userKey = uniqid('', true);
        $subscription = 'Free';
        $verificationLink = $userKey;
        $status = 'Verified';
        $status1 = 'approved';


        $insert = $this->conn->prepare("
        INSERT INTO users (
            ADMIN_NAME, ADMIN_EMAIL, ADMIN_PROFILE_IMAGE, 
            ADMIN_USERKEY, ADMIN_SUBSCRIPTION, ADMIN_VERIFICATION_LINK, 
            ADMIN_USER_STATUS, RESETLINK, USER_STATUS
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $insert->bind_param(
            "sssssssss",
            $name,
            $email,
            $profileImage,
            $userKey,
            $subscription,
            $verificationLink,
            $status1,
            $userKey,
            $status
        );

        if ($insert->execute()) {
            $insert->close();
            return "success";
        } else {
            return "error: " . $insert->error;
        }
    }




    /**
     * Fetches all posts associated with a specific user key.
     *
     * @param string $userKey The unique user ID (USERID) from the posts table
     * @return array An array of associative arrays representing posts
     */
    public function getPostsByUserKey($userKey)
    {
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
