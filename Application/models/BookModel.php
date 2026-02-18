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
        $sql = "SELECT * FROM posts WHERE LOWER(category) = LOWER(?) AND STATUS = 'active' ORDER BY TITLE";

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

    public function getBookListingsByCategory($category, $limit = 10)
    {
        $sql = "SELECT p.* FROM posts AS p
                JOIN listings AS l ON p.CONTENTID = l.CONTENTID
                WHERE l.CATEGORY = ?
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
                    hc.*, 
                    a.id AS a_id,
                    a.book_id AS a_book_id,
                    a.narrator AS a_narrator,
                    a.duration_minutes AS a_duration_minutes,
                    a.release_date AS a_release_date,
                    a.created_at AS a_created_at,
                    s.id AS audiobook_sample_id,
                    s.book_id,
                    s.url AS sample_url
                FROM posts AS p
                LEFT JOIN book_hardcopy AS hc
                    ON book_id = p.ID
                LEFT JOIN audiobooks AS a 
                    ON a.book_id = p.ID
                LEFT JOIN audiobook_samples AS s 
                    ON s.book_id = p.ID
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
    public function getBooks()
    {
        $sql = "SELECT
                p.*,
                a.id AS a_id
            FROM posts AS p
            LEFT JOIN audiobooks AS a ON a.book_id = p.ID
            ORDER BY RAND()";

        $stmt = mysqli_prepare($this->conn, $sql);

        if (!$stmt) {
            error_log("SQL Prepare Error: " . mysqli_error($this->conn));
            return [];
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result || mysqli_num_rows($result) === 0) {
            return [];
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        return $books;
    }


    /**
     * Fetch All books
     * @return array
     */
    public function getAllBooks($updatedSince = null)
    {
        // Base SQL query with joins
        $sql = "SELECT
                p.*,
                a.id AS a_id,
                a.book_id AS a_book_id,
                a.narrator AS a_narrator,
                l.CATEGORY AS listing_category,
                h.hc_id,
                h.hc_price,
                h.hc_stock_count
            FROM posts AS p
            LEFT JOIN audiobooks AS a ON a.book_id = p.ID
            LEFT JOIN listings AS l ON p.CONTENTID = l.CONTENTID
            LEFT JOIN book_hardcopy AS h ON h.book_id = p.ID";

        // Add a conditional WHERE clause for delta syncing
        if ($updatedSince) {
            // Create a DateTime object from the ISO 8601 string
            // This is the correct way to handle dates from the client
            $lastUpdatedDateTime = new DateTime($updatedSince);
            $formattedDate = $lastUpdatedDateTime->format('Y-m-d H:i:s');

            $sql .= " WHERE p.updated_at > ?";
            $sql .= " ORDER BY p.updated_at ASC";
        }

        // Prepare the statement for executing the query
        $stmt = mysqli_prepare($this->conn, $sql);

        // Bind the parameter if it exists
        if ($updatedSince) {
            // Bind the formatted date string
            mysqli_stmt_bind_param($stmt, "s", $formattedDate);
        }

        // Execute the statement and get the result
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            return [];
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Important: Return the date in ISO 8601 format to the client
            $row['updated_at'] = (new DateTime($row['updated_at']))->format(DateTime::ISO8601);
            $books[] = $row;
        }

        return $books;
    }

    public function getAcademicBooks($updatedSince = null): array
    {
        $sql = "SELECT * FROM academic_books";

        if ($updatedSince) {
            try {
                $lastUpdatedDateTime = new DateTime($updatedSince);
                $formattedDate = $lastUpdatedDateTime->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                throw new Exception("Invalid 'updatedSince' timestamp format: " . $e->getMessage());
            }

            $sql .= " WHERE updated_at > ?";
            $sql .= " ORDER BY updated_at ASC";
        } else {
            $sql .= " ORDER BY RAND()";
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement for academic books: " . mysqli_error($this->conn));
        }

        if ($updatedSince) {
            if (!mysqli_stmt_bind_param($stmt, "s", $formattedDate)) {
                throw new Exception("Failed to bind parameters for academic books: " . mysqli_stmt_error($stmt));
            }
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to execute statement for academic books: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            throw new Exception("Failed to get result for academic books: " . mysqli_stmt_error($stmt));
        }

        $academicBooks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($row['updated_at'])) {
                try {
                    $row['updated_at'] = (new DateTime($row['updated_at']))->format(DateTime::ISO8601);
                } catch (Exception $e) {
                    error_log("Error formatting updated_at for academic book ID {$row['ID']}: " . $e->getMessage());
                }
            }
            $academicBooks[] = $row;
        }

        mysqli_stmt_close($stmt);

        return $academicBooks;
    }

    /**
     * Fetch All Ebooks
     * @return array
     */
    public function getEbooks()
    {
        $sql = "SELECT * FROM posts WHERE PDFURL IS NOT NULL";

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

    public function getBanners($screen)
    {
        $sql = "SELECT * FROM Mobile_banners WHERE screen = ? ";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $screen);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if (!$result || mysqli_num_rows($result) == 0) {
            mysqli_stmt_close($stmt);
            return [];
        }

        $banners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $banners;
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

    public function getBooksByViews()
    {
        $sql = "SELECT * FROM posts ORDER BY RAND() LIMIT 10";

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

        // closing the prepared statement
        mysqli_stmt_close($stmt);
        return $books;
    }

    /**
     * Get books with filters - only regular books (posts table)
     */
    public function selectBooksWithFilters(array $filters = []): array
    {
        $where = [];
        $params = [];
        $types = '';
        
        // Base condition - only active books
        $where[] = "p.STATUS = 'active'";
        
        // Search filter
        if (!empty($filters['search'])) {
            $where[] = "(p.TITLE LIKE ? OR p.PUBLISHER LIKE ? OR p.DESCRIPTION LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'sss';
        }
        
        // Category filter
        if (!empty($filters['category'])) {
            $where[] = "p.CATEGORY = ?";
            $params[] = $filters['category'];
            $types .= 's';
        }
        
        // Format filter (ebook, audiobook, hardcopy)
        if (!empty($filters['format'])) {
            switch ($filters['format']) {
                case 'ebook':
                    $where[] = "p.PDFURL IS NOT NULL AND p.PDFURL != ''";
                    break;
                case 'audiobook':
                    $where[] = "a.id IS NOT NULL";
                    break;
                case 'hardcopy':
                    $where[] = "hc.hc_id IS NOT NULL";
                    break;
            }
        }
        
        // Price range filter
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $where[] = "p.RETAILPRICE >= ?";
            $params[] = floatval($filters['min_price']);
            $types .= 'd';
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $where[] = "p.RETAILPRICE <= ?";
            $params[] = floatval($filters['max_price']);
            $types .= 'd';
        }
        
        // Build WHERE clause
        $whereClause = 'WHERE ' . implode(' AND ', $where);
        
        // Sort order
        $orderBy = 'p.CREATED_AT DESC';
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_low':
                    $orderBy = 'p.RETAILPRICE ASC';
                    break;
                case 'price_high':
                    $orderBy = 'p.RETAILPRICE DESC';
                    break;
                case 'title_asc':
                    $orderBy = 'p.TITLE ASC';
                    break;
                case 'title_desc':
                    $orderBy = 'p.TITLE DESC';
                    break;
                case 'newest':
                    $orderBy = 'p.CREATED_AT DESC';
                    break;
                case 'oldest':
                    $orderBy = 'p.CREATED_AT ASC';
                    break;
            }
        }
        
        // Query with joins for format detection
        $sql = "SELECT 
                    p.*,
                    a.id AS a_id,
                    hc.hc_id,
                    CASE 
                        WHEN p.PDFURL IS NOT NULL AND p.PDFURL != '' THEN 'ebook'
                        WHEN a.id IS NOT NULL THEN 'audiobook'
                        WHEN hc.hc_id IS NOT NULL THEN 'hardcopy'
                        ELSE NULL
                    END as book_format
                FROM posts p
                LEFT JOIN audiobooks a ON a.book_id = p.ID
                LEFT JOIN book_hardcopy hc ON hc.book_id = p.ID
                {$whereClause}
                GROUP BY p.ID
                ORDER BY {$orderBy}";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            return [];
        }
        
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
        if (!mysqli_stmt_execute($stmt)) {
            return [];
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $books;

    /**
     * Get filter options for library (categories, formats, price range)
     */
    public function getFilterOptions(): array
    {
        $categories = [];
        $priceRange = ['min_price' => 0, 'max_price' => 1000];
        
        // Get categories from regular books
        $result = mysqli_query($this->conn, "SELECT DISTINCT CATEGORY FROM posts WHERE STATUS = 'active' AND CATEGORY IS NOT NULL AND CATEGORY != '' ORDER BY CATEGORY");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = $row['CATEGORY'];
            }
        }
        
        // Get price range from regular books
        $result = mysqli_query($this->conn, "SELECT MIN(RETAILPRICE) as min_price, MAX(RETAILPRICE) as max_price FROM posts WHERE STATUS = 'active' AND RETAILPRICE > 0");
        if ($result) {
            $data = mysqli_fetch_assoc($result);
            if ($data && $data['min_price'] !== null) {
                $priceRange = [
                    'min_price' => floatval($data['min_price']),
                    'max_price' => floatval($data['max_price'])
                ];
            }
        }
        
        return [
            'categories' => $categories,
            'price_range' => $priceRange
        ];
    }
}
