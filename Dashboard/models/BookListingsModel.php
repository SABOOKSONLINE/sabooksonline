<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class BookListingsModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * Select books by userId
     * @param string $userId User ID
     * @return array Books data or an empty array if not found
     * @throws Exception If the query fails
     */
    public function selectBooksByUserId($userId)
    {
        $sql = "SELECT * FROM posts WHERE USERID = ? ORDER BY ID DESC";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $userId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to fetch result: " . mysqli_error($this->conn));
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $books;
    }

    /**
     * Select book by contentId
     * @param string $userId User ID
     * @param string $contentId Book Content ID
     * @return array|null Book data or null if not found
     * @throws Exception If the query fails
     */
    public function selectBookByContentId($userId, $contentId)
    {
        $sql = "SELECT * FROM posts WHERE USERID = ? AND CONTENTID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ss", $userId, $contentId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to fetch result: " . mysqli_error($this->conn));
        }

        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $book = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $book;
    }

    /**
     * Insert a new book
     * @param array $data Book data
     * @return bool True if insertion was successful
     * @throws Exception If the query fails
     */
    public function insertBook($data)
    {
        $data['contentid'] = uniqid('', true);

        $sql = "INSERT INTO posts (
                    TITLE, CATEGORY, WEBSITE, DESCRIPTION, COVER, CONTENTID, USERID, TYPE, DATEPOSTED,
                    STATUS, ISBN, RETAILPRICE, KEYWORDS, PUBLISHER, LANGUAGES, STOCK, AUTHORS, PDFURL
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssss",
            $data['title'],
            $data['category'],
            $data['website'],
            $data['description'],
            $data['cover'],
            $data['contentid'],
            $data['userid'],
            $data['type'],
            $data['dateposted'],
            $data['status'],
            $data['isbn'],
            $data['price'],
            $data['keywords'],
            $data['publisher'],
            $data['languages'],
            $data['stock'],
            $data['authors'],
            $data['pdf']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    /**
     * Update an existing book
     * @param string $contentId Book Content ID
     * @param array $data Book data
     * @return bool True if update was successful
     * @throws Exception If the query fails
     */
    public function updateBook($bookId, $data)
    {
        $sql = "UPDATE posts SET 
                    TITLE = ?, CATEGORY = ?, WEBSITE = ?, DESCRIPTION = ?, COVER = ?, 
                    USERID = ?, TYPE = ?, DATEPOSTED = ?, STATUS = ?, ISBN = ?, 
                    RETAILPRICE = ?, KEYWORDS = ?, PUBLISHER = ?, 
                    LANGUAGES = ?, STOCK = ?, AUTHORS = ?, PDFURL = ?
                WHERE ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssss",
            $data['title'],
            $data['category'],
            $data['website'],
            $data['description'],
            $data['cover'],
            $data['userid'],
            $data['type'],
            $data['dateposted'],
            $data['status'],
            $data['isbn'],
            $data['price'],
            $data['keywords'],
            $data['publisher'],
            $data['languages'],
            $data['stock'],
            $data['authors'],
            $data['pdf'],
            $bookId,
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $affectedRows > 0;
    }

    /**
     * Delete a book
     * @param string $contentId Book Content ID
     * @return bool True if deletion was successful
     * @throws Exception If the query fails
     */
    public function deleteBook($contentId)
    {
        $sql = "DELETE FROM posts WHERE CONTENTID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $contentId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $affectedRows > 0;
    }

    /**
     * Get all categories
     * @return array Categories data
     * @throws Exception If the query fails
     */
    public function getCategories()
    {
        $sql = "SELECT * FROM category";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to fetch result: " . mysqli_error($this->conn));
        }

        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $categories;
    }

    public function selectAudiobookByBookId($bookId)
    {
        $sql = "SELECT 
                    audiobooks.id AS audiobook_id,
                    audiobooks.book_id,
                    audiobooks.narrator,
                    audiobooks.duration_minutes AS audiobook_duration,
                    audiobooks.release_date,
                    audiobook_chapters.id AS chapter_id,
                    audiobook_chapters.chapter_number,
                    audiobook_chapters.chapter_title,
                    audiobook_chapters.audio_url,
                    audiobook_chapters.duration_minutes AS chapter_duration
                FROM audiobooks 
                LEFT JOIN audiobook_chapters 
                  ON audiobooks.id = audiobook_chapters.audiobook_id
                WHERE audiobooks.book_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $bookId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to fetch result: " . mysqli_error($this->conn));
        }

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);

        // When only one chapter exists, return a single entry; otherwise return a list.
        if (count($rows) === 1) {
            return $rows[0];
        }
        return $rows;
    }

    public function selectAudiobookByContentId($contentId)
    {
        $sql = "SELECT 
                    p.*, 
                    a.id AS a_id,
                    a.book_id AS a_book_id,
                    a.narrator AS a_narrator,
                    a.duration_minutes AS a_duration_minutes,
                    a.release_date AS a_release_date,
                    a.release_date AS a_release_date,
                    a.created_at AS a_created_at
                FROM posts AS p
                LEFT JOIN audiobooks AS a ON a.book_id = p.ID
                WHERE p.CONTENTID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $contentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $book = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $book;
    }

    public function deleteAudiobookByBookId($bookId)
    {
        mysqli_begin_transaction($this->conn);

        try {
            $sqlGet = "SELECT id FROM audiobooks WHERE book_id = ?";
            $stmtGet = mysqli_prepare($this->conn, $sqlGet);
            if (!$stmtGet) {
                throw new Exception("Failed to prepare get statement: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmtGet, "i", $bookId);
            if (!mysqli_stmt_execute($stmtGet)) {
                throw new Exception("Failed to execute get statement: " . mysqli_stmt_error($stmtGet));
            }
            $resultGet = mysqli_stmt_get_result($stmtGet);
            $audiobookIds = [];
            while ($row = mysqli_fetch_assoc($resultGet)) {
                $audiobookIds[] = $row['id'];
            }
            mysqli_stmt_close($stmtGet);

            if (!empty($audiobookIds)) {
                $placeholders = implode(',', array_fill(0, count($audiobookIds), '?'));
                $sqlDelChapters = "DELETE FROM audiobook_chapters WHERE audiobook_id IN ($placeholders)";
                $stmtDelChapters = mysqli_prepare($this->conn, $sqlDelChapters);
                if (!$stmtDelChapters) {
                    throw new Exception("Failed to prepare delete chapters statement: " . mysqli_error($this->conn));
                }
                $types = str_repeat("i", count($audiobookIds));
                mysqli_stmt_bind_param($stmtDelChapters, $types, ...$audiobookIds);
                if (!mysqli_stmt_execute($stmtDelChapters)) {
                    throw new Exception("Failed to execute delete chapters statement: " . mysqli_stmt_error($stmtDelChapters));
                }
                mysqli_stmt_close($stmtDelChapters);
            }

            $sqlDelAudiobooks = "DELETE FROM audiobooks WHERE book_id = ?";
            $stmtDelAudiobooks = mysqli_prepare($this->conn, $sqlDelAudiobooks);
            if (!$stmtDelAudiobooks) {
                throw new Exception("Failed to prepare delete audiobook statement: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmtDelAudiobooks, "i", $bookId);
            if (!mysqli_stmt_execute($stmtDelAudiobooks)) {
                throw new Exception("Failed to execute delete audiobook statement: " . mysqli_stmt_error($stmtDelAudiobooks));
            }
            mysqli_stmt_close($stmtDelAudiobooks);

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            throw $e;
        }
    }


    /**
     * Insert a new audiobook
     * @param int $book_id Book ID (posts.ID or posts.CONTENTID)
     * @param array $data Audiobook data (expects: narrator, duration_minutes, release_date)
     * @return int Inserted audiobook ID
     * @throws Exception If the query fails
     */
    public function insertAudiobook($data)
    {
        $sql = "INSERT INTO audiobooks (book_id, narrator, duration_minutes, release_date) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Insert Audiobook: Failed to prepare statement: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_bind_param($stmt, "isis", $data['book_id'], $data['narrator'], $data['duration_minutes'], $data['release_date'])) {
            throw new Exception("Insert Audiobook: Failed to bind parameters: " . mysqli_stmt_error($stmt));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Insert Audiobook: Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $insertId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);
        return $insertId;
    }

    /**
     * Update an existing audiobook
     * @param int $audiobook_id Audiobook ID
     * @param array $data Audiobook data (expects: narrator, duration_minutes, release_date)
     * @return bool True if update was successful
     * @throws Exception If the query fails
     */
    public function updateAudiobook($book_id, $data)
    {
        $sql = "UPDATE audiobooks SET narrator = ?, duration_minutes = ?, release_date = ? WHERE book_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "sisi",
            $data['narrator'],
            $data['duration_minutes'],
            $data['release_date'],
            $book_id
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }

    /**
     * Insert a new audiobook chapter
     * @param int $audiobook_id Audiobook ID
     * @param array $data Chapter data (expects: chapter_number, chapter_title, audio_url, duration_minutes)
     * @return int Inserted chapter ID
     * @throws Exception If the query fails
     */
    public function insertAudiobookChapter($data)
    {
        $sql = "INSERT INTO audiobook_chapters (audiobook_id, chapter_number, chapter_title, audio_url, duration_minutes) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "iissi",
            $data['audiobook_id'],
            $data['chapter_number'],
            $data['chapter_title'],
            $data['audio_url'],
            $data['duration_minutes']
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }
        $insertId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);
        return $insertId;
    }

    /**
     * Update an existing audiobook chapter
     * @param int $chapter_id Chapter ID
     * @param array $data Chapter data (expects: chapter_number, chapter_title, audio_url, duration_minutes)
     * @return bool True if update was successful
     * @throws Exception If the query fails
     */
    public function updateAudiobookChapter($chapterId, $data)
    {
        $sql = "UPDATE audiobook_chapters SET chapter_number = ?, chapter_title = ?, audio_url = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "issi",
            $data['chapter_number'],
            $data['chapter_title'],
            $data['audio_url'],
            $chapterId
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }

    public function deleteAudiobookChapter($chapterId)
    {
        $sql = "DELETE FROM audiobook_chapters WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $chapterId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }

    public function getAdminName($userKey)
    {
        $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $userKey);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to fetch result: " . mysqli_error($this->conn));
        }

        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        return $user['ADMIN_NAME'];
    }
}
