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
                    STATUS, ISBN, RETAILPRICE, KEYWORDS, PUBLISHER, LANGUAGES, STOCK, AUTHORS
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssssss",
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
            $data['authors']
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
    public function updateBook($contentId, $data)
    {
        $sql = "UPDATE posts SET 
                    TITLE = ?, CATEGORY = ?, WEBSITE = ?, DESCRIPTION = ?, COVER = ?, 
                    USERID = ?, TYPE = ?, DATEPOSTED = ?, STATUS = ?, ISBN = ?, 
                    RETAILPRICE = ?, KEYWORDS = ?, PUBLISHER = ?, 
                    LANGUAGES = ?, STOCK = ?, AUTHORS = ?
                WHERE CONTENTID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssssss",
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
            $contentId
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
        $sql = "SELECT * FROM audiobooks WHERE book_id = ?";
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

        $audiobook = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        return $audiobook;
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
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "isis",
            $data['book_id'],
            $data['narrator'],
            $data['duration_minutes'],
            $data['release_date']
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
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
    public function insertAudiobookChapter($audiobook_id, $data)
    {
        $sql = "INSERT INTO audiobook_chapters (audiobook_id, chapter_number, chapter_title, audio_url, duration_minutes) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "iissi",
            $audiobook_id,
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
    public function updateAudiobookChapter($chapter_id, $data)
    {
        $sql = "UPDATE audiobook_chapters SET chapter_number = ?, chapter_title = ?, audio_url = ?, duration_minutes = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }
        mysqli_stmt_bind_param(
            $stmt,
            "issii",
            $data['chapter_number'],
            $data['chapter_title'],
            $data['audio_url'],
            $data['duration_minutes'],
            $chapter_id
        );
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows > 0;
    }
}
