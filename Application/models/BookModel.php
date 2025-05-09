<?php
class BookModel
{
    private $conn;

    // Constructor: Initialize database connection
    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    /**
     * Fetch books by category (default: 'Editors Choice')
     * @param string $category
     * @param int $limit
     * @return array
     */
    public function getBooksByCategory($category)
    {
        $sql = "SELECT * FROM posts WHERE category = ? AND STATUS = 'active' ORDER BY TITLE";

        // prepared statements for executing the quesry
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $category);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $books;
    }

    public function getBookListingsByCategory($category, $limit = 6)
    {
        $sql = "SELECT p.* FROM posts AS p
                JOIN listings AS l ON p.CONTENTID = l.CONTENTID
                WHERE l.CATEGORY = ? AND p.STATUS = 'active'
                ORDER BY RAND() LIMIT ?";

        // prepared statements for executing the quesry
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $category, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        // closing the prepared statement
        mysqli_stmt_close($stmt);
        return $books;
    }


    /**
     * Fetch a single book by its CONTENTID
     * @param string $contentId
     * @return array|null
     */
    public function getBookById($contentId)
    {
        $sql = "SELECT 
                    p.*, 
                    a.id AS a_id,
                    a.book_id AS a_book_id,
                    a.narrator AS a_narrator,
                    a.duration_minutes AS a_duration_minutes,
                    a.release_date AS a_release_date,
                    a.created_at AS a_created_at
                FROM posts AS p
                LEFT JOIN audiobooks AS a ON a.book_id = p.ID
                WHERE p.CONTENTID = ?
                AND p.STATUS = 'active'";

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

    public function getChaptersByAudiobookId($audiobookId)
    {
        $sql = "SELECT 
                    a.*,
                    ac.*
                FROM audiobooks AS a
                LEFT JOIN audiobook_chapters AS ac ON a.id = ac.audiobook_id
                WHERE audiobook_id = ?
                ORDER BY chapter_number ASC";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $audiobookId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $chapters = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $chapters[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $chapters;
    }

    /**
     * Fetch All books
     * @return array
     */
    public function getBooks()
    {
        $sql = "SELECT * FROM posts ORDER BY TITLE";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        return $books;
    }

    public function searchBooks($keyword)
    {
        if ($keyword === '') return [];
        $safe_keyword = $this->conn->real_escape_string($keyword);
        $sql = "SELECT * FROM posts
                WHERE title LIKE '%$safe_keyword%' 
                   OR publisher LIKE '%$safe_keyword%' 
                   OR description LIKE '%$safe_keyword%' 
                ORDER BY TITLE";

        $result = $this->conn->query($sql);
        $books = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }

        return $books;
    }

    public function getBooksByPublisher($userId)
    {
        $sql = "SELECT * FROM posts WHERE USERID = ? ORDER BY TITLE";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        // closing the prepared statement
        mysqli_stmt_close($stmt);
        return $books;
    }

    public function getBookCategories()
    {

        $sql = "SELECT * FROM category ORDER BY CATEGORY";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }

        // closing the prepared statement
        mysqli_stmt_close($stmt);
        return $categories;
    }
}
