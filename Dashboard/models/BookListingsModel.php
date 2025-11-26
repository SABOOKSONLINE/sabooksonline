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
        $sql = "SELECT 
                p.*, 
                h.hc_id,
                h.hc_price,
                h.hc_discount_percent,
                h.hc_country,
                h.hc_pages,
                h.hc_weight_kg,
                h.hc_height_cm,
                h.hc_width_cm,
                h.hc_release_date,
                h.hc_contributors,
                h.hc_stock_count,
                a.id AS audiobook_id,
                a.book_id AS audiobook_book_id,
                a.narrator,
                a.duration_minutes AS audiobook_duration,
                a.release_date AS audiobook_release_date,
                c.id AS chapter_id,
                c.chapter_number,
                c.chapter_title,
                c.audio_url,
                c.duration_minutes AS chapter_duration,
                s.id AS audiobook_sample_id,
                s.book_id AS sample_book_id,
                s.url AS sample_url
            FROM posts AS p
            LEFT JOIN book_hardcopy AS h
                ON h.book_id = p.ID
            LEFT JOIN audiobooks AS a 
                ON a.book_id = p.ID
            LEFT JOIN audiobook_chapters AS c 
                ON a.id = c.audiobook_id
            LEFT JOIN audiobook_samples AS s
                ON s.book_id = p.ID
            WHERE p.USERID = ? AND p.CONTENTID = ?
            ORDER BY c.chapter_number ASC
        ";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ss", $userId, $contentId);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result || mysqli_num_rows($result) == 0) {
            return null;
        }

        $book = null;

        while ($row = mysqli_fetch_assoc($result)) {
            if (!$book) {
                $book = $row;
                $book['chapters'] = [];
            }

            if ($row['chapter_id']) {
                $book['chapters'][] = [
                    'chapter_id' => $row['chapter_id'],
                    'chapter_number' => $row['chapter_number'],
                    'chapter_title' => $row['chapter_title'],
                    'audio_url' => $row['audio_url'],
                    'duration_minutes' => $row['chapter_duration'],
                ];
            }
        }

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
        $sql = "INSERT INTO posts (
                TITLE, CATEGORY, WEBSITE, DESCRIPTION, COVER, CONTENTID, USERID, TYPE, DATEPOSTED,
                STATUS, ISBN, RETAILPRICE, KEYWORDS, PUBLISHER, LANGUAGES, STOCK, AUTHORS, PDFURL, EBOOKPRICE, ABOOKPRICE
            ) VALUES (?, ?, ?, ?, ?, UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        $cover = $data['cover'];

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssssssss",
            $data['title'],
            $data['category'],
            $data['website'],
            $data['description'],
            $cover,
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
            $data['Eprice'],
            $data['Aprice']
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
                    LANGUAGES = ?, STOCK = ?, AUTHORS = ?, PDFURL = ?, EBOOKPRICE = ?, ABOOKPRICE = ?
                WHERE ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }


        $cover = $data['cover'];

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssssss",
            $data['title'],
            $data['category'],
            $data['website'],
            $data['description'],
            $cover,
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
            $data['Eprice'],
            $data['Aprice'],
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
                WHERE audiobooks.book_id = ?
                ORDER BY audiobook_chapters.chapter_number ASC";
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

    public function insertAudiobookSample($data)
    {

        $sql = "INSERT INTO audiobook_samples (url, book_id) VALUES (?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $data['sample_file'],
            $data['book_id'],
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $insertId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);
        return $insertId;
    }

    public function updateAudiobookSample($data)
    {
        $sql = "UPDATE audiobook_samples SET url = ? WHERE book_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $data['sample_file'],
            $data['book_id']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows;
    }

    public function deleteAudiobookSample($book_id)
    {
        $sql = "DELETE FROM audiobook_samples WHERE book_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $book_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute delete: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $affectedRows;
    }

    public function insertHardcopy($data)
    {
        $sql = "INSERT INTO book_hardcopy (
                    book_id,
                    hc_price,
                    hc_discount_percent,
                    hc_country,
                    hc_pages,
                    hc_weight_kg,
                    hc_height_cm,
                    hc_width_cm,
                    hc_release_date,
                    hc_contributors,
                    hc_stock_count
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            throw new Exception("Insert Hardcopy: Failed to prepare statement: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_bind_param(
            $stmt,
            "idissdddssi",
            $data['book_id'],
            $data['hc_price'],
            $data['hc_discount_percent'],
            $data['hc_country'],
            $data['hc_pages'],
            $data['hc_weight_kg'],
            $data['hc_height_cm'],
            $data['hc_width_cm'],
            $data['hc_release_date'],
            $data['hc_contributors'],
            $data['hc_stock_count']
        )) {
            throw new Exception("Insert Hardcopy: Failed to bind parameters: " . mysqli_stmt_error($stmt));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Insert Hardcopy: Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $insertId = mysqli_insert_id($this->conn);
        mysqli_stmt_close($stmt);

        return $insertId;
    }

    public function updateHardcopy($data)
    {
        $sql = "UPDATE book_hardcopy SET
                hc_price = ?, 
                hc_discount_percent = ?, 
                hc_country = ?, 
                hc_pages = ?, 
                hc_weight_kg = ?, 
                hc_height_cm = ?, 
                hc_width_cm = ?, 
                hc_release_date = ?, 
                hc_contributors = ?, 
                hc_stock_count = ?
            WHERE book_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            throw new Exception("Update Hardcopy: Failed to prepare statement: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_bind_param(
            $stmt,
            "dissdddssii",
            $data['hc_price'],
            $data['hc_discount_percent'],
            $data['hc_country'],
            $data['hc_pages'],
            $data['hc_weight_kg'],
            $data['hc_height_cm'],
            $data['hc_width_cm'],
            $data['hc_release_date'],
            $data['hc_contributors'],
            $data['hc_stock_count'],
            $data['book_id']
        )) {
            throw new Exception("Update Hardcopy: Failed to bind parameters: " . mysqli_stmt_error($stmt));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Update Hardcopy: Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);

        return $affectedRows;
    }

    public function getHardcopyByBookId($bookId)
    {
        $sql = "SELECT * FROM book_hardcopy WHERE book_id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            throw new Exception("Get Hardcopy: Failed to prepare statement: " . mysqli_error($this->conn));
        }

        if (!mysqli_stmt_bind_param($stmt, "i", $bookId)) {
            throw new Exception("Get Hardcopy: Failed to bind parameters: " . mysqli_stmt_error($stmt));
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Get Hardcopy: Failed to execute statement: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            throw new Exception("Get Hardcopy: Failed to fetch result: " . mysqli_error($this->conn));
        }

        $row = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        return $row ?: null; // return null if not found
    }
}
