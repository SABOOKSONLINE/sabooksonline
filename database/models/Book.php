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
     * Render/display books in HTML format
     * @param array $books
     */
    public function renderBooks($books)
    {
        foreach ($books as $book) {
            $username = ucwords(substr($book['PUBLISHER'], 0, 20));

            echo '<div class="item">
                    <div class="strip">
                        <a href="book.php?q=' . strtolower($book['CONTENTID']) . '">
                            <figure>
                                <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" class="owl-lazy" alt="" width="460" height="310">
                            </figure>
                        </a>
                        <div class="bottom-text">
                            <a href="creator?q=' . strtolower($book['USERID']) . '" class="text-dark">' . ucwords($book['PUBLISHER']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a><br>
                            <a href="creator?q=' . strtolower($book['USERID']) . '" class="text-dark">' . ucwords(substr($book['AUTHORS'], 0, 20)) . '</a>
                        </div>
                    </div>
                </div>';
        }
    }
}
