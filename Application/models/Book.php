<?php
class Book
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
    public function getBooksByCategory($category, $limit)
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
        $sql = "SELECT p.* FROM posts AS p WHERE p.CONTENTID = ? AND p.STATUS = 'active'";

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

    /**
     * Fetch All books
     * @return array
     */
    public function getBooks($limit)
    {
        $sql = "SELECT * FROM posts LIMIT ?";

        // prepared statements for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $limit);
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
}
