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

    public function getPurchasedBooksByUserEmail($email)
    {
        $sql = "
        SELECT 
            b.id AS ID,
            b.contentid AS CONTENTID,
            b.cover AS COVER,
            b.title AS TITLE,
            b.publisher AS PUBLISHER,
            b.description AS DESCRIPTION,
            b.retailprice AS RETAILPRICE,
            b.category AS CATEGORY,
            b.abookprice AS APRICE,
            b.ebookprice AS EPRICE,
            b.pdfurl AS PDFURL,
            bp.payment_date,
            bp.format,
            bp.amount
        FROM book_purchases bp
        JOIN posts b ON bp.book_id = b.id
        WHERE bp.user_email = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $books = [];

            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }

            $stmt->close();
            return $books;
        } else {
            error_log("❌ Failed to fetch purchased books: " . $stmt->error);
            return [];
        }
    }

    public function getUserBooksByAction($userId, $actionType = 'library')
    {
        $query = "
    SELECT p.CONTENTID, p.TITLE, p.COVER, p.DESCRIPTION
    FROM user_book_actions AS uba
    INNER JOIN posts AS p 
        ON uba.book_id COLLATE utf8mb4_general_ci = p.CONTENTID COLLATE utf8mb4_general_ci
    WHERE uba.user_id COLLATE utf8mb4_general_ci = ? 
      AND uba.action_type COLLATE utf8mb4_general_ci = ?
    ORDER BY uba.created_at DESC
";



        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("ss", $userId, $actionType);
        $stmt->execute();
        $result = $stmt->get_result();

        $books = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $books;
    }


    /**
     * Get user by ID
     * @param string $userId
     * @return array|null
     */
    public function getUserById($userKey)
    {
        try {
            $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = ?";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . mysqli_error($this->conn));
                return null;
            }
            mysqli_stmt_bind_param($stmt, "s", $userKey);
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

    public function getBookIdsByUserEmail($email)
    {
        $sql = "SELECT book_id FROM book_purchases WHERE user_email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $bookIds = [];

            while ($row = $result->fetch_assoc()) {
                $bookIds[] = $row['book_id'];
            }

            $stmt->close();
            return $bookIds;
        } else {
            error_log("Failed to fetch book IDs: " . $stmt->error);
            return [];
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
            $fields = [
                'ADMIN_NAME' => $data['ADMIN_NAME'],
                'ADMIN_NUMBER' => $data['ADMIN_NUMBER'],
                'ADMIN_WEBSITE' => $data['ADMIN_WEBSITE'],
                'ADMIN_GOOGLE' => $data['ADMIN_GOOGLE'],
                'ADMIN_BIO' => $data['ADMIN_BIO'],
                'ADMIN_FACEBOOK' => $data['ADMIN_FACEBOOK'],
                'ADMIN_INSTAGRAM' => $data['ADMIN_INSTAGRAM'],
                'ADMIN_TWITTER' => $data['ADMIN_TWITTER'],
                'ADMIN_LINKEDIN' => $data['ADMIN_LINKEDIN'],
                'ADMIN_PROFILE_IMAGE' => $data['ADMIN_PROFILE_IMAGE'],
                'ADMIN_USER_STATUS' => $data['ADMIN_USER_STATUS'],
                'ADMIN_TYPE' => $data['ADMIN_TYPE']
            ];

            if (!empty($data['ADMIN_PASSWORD'])) {
                $fields['ADMIN_PASSWORD'] = $data['ADMIN_PASSWORD'];
            }

            $setParts = [];
            foreach ($fields as $key => $value) {
                $setParts[] = "$key = ?";
            }
            $setParts[] = "ADMIN_DATE = NOW()";
            $sql = "UPDATE users SET " . implode(", ", $setParts) . " WHERE ADMIN_ID = ?";

            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                error_log("Failed to prepare statement: " . mysqli_error($this->conn));
                return false;
            }

            $types = str_repeat('s', count($fields)) . 's';
            $params = array_values($fields);
            $params[] = $userId;

            mysqli_stmt_bind_param($stmt, $types, ...$params);

            $result = mysqli_stmt_execute($stmt);
            if (!$result) {
                error_log("Failed to execute statement: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return false;
            }

            $affectedRows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            return $affectedRows >= 0;
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
}
