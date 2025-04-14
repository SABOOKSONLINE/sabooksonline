<!-- <?php
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
            echo '
                <div class="book-card">
                    <a class="book-card-cover" href="/book/' . strtolower($book['CONTENTID']) . '">
                        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" alt="' . strtolower($book['TITLE']) . '">
                    </a>
                    <div class="book-card-info">
                        <a class="book-card-little" href="/book/' . strtolower($book['CONTENTID']) . '">
                            ' . (strlen($book['TITLE']) > 30 ? substr($book['TITLE'], 0, 30) . '...' : $book['TITLE']) . '
                        </a>
                        <span class="book-card-pub">
                            Published by: <a class="text-muted" href="/creator?q=' . strtolower($book['USERID']) . '">' . ucwords($book['PUBLISHER']) . '</a>
                        </span>
                    </div>
                </div>
            ';
        }
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
     * Render/display a single book in HTML format
     * @param array $book
     */
    public function renderBook($book)
    {
        if ($book) {
            echo '
                <div class="container py-5">
  <div class="row align-items-center">
    <!-- Book Cover -->
    <div class="col-md-4 text-center mb-4 mb-md-0">
      <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" class="img-fluid rounded shadow" alt="Book Cover" style="max-height: 400px;">
    </div>

    <!-- Book Info -->
    <div class="col-md-8">
      <h2 class="fw-bold text-capitalize">' . $book['TITLE'] . '</h2>
      <p class="mb-1 text-capitalize"><strong>Published by:</strong> <span class="fw-semibold">' . $book['PUBLISHER'] . '</span></p>
      <p class="mb-3 text-capitalize"><strong>Author:</strong> <span class="fw-semibold">' . $book['PUBLISHER'] . '</span></p>

      <h6 class="mb-2">Book Synopsis:</h6>
      <p>
        ' . $book['DESCRIPTION'] . '
      </p>

      <!-- Ratings -->
      <div class="mb-3">
        <span class="text-warning">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
        </span>
        <span class="ms-2 text-muted">4.0</span>
      </div>

      <!-- Action Buttons & Price -->
      <div class="row g-2">
        <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
          <a href="" class="btn btn-green">READ NOW</a>
          <span class="ms-2 fw-bold">COMING SOON</span>
        </div>
        <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
          <a href="" class="btn btn-yellow"><i class="bi bi-headphones"></i> LISTEN TO AUDIOBOOK</a>
          <span class="ms-2 fw-bold">COMING SOON</span>
        </div>
        <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
          <a href="" class="btn btn-blue">BUY COPY</a>
          <span class="ms-2 fw-bold">R' . $book['RETAILPRICE'] . '.00</span>
        </div>
      </div>
    </div>
  </div>
</div>
            ';
        } else {
            echo 'Book not found.';
        }
    }
} -->
