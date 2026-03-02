<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AcademicBookModel
{
    private $conn;

    public function __construct($connection)
    {
        $this->conn = $connection;
    }

    public function selectBooks(array $filters = []): array
    {
        $where = [];
        $params = [];
        $types = '';

        // Only show academic books that have a cover image available
        // (we still allow books without PDFs; we just hide ones with no cover)
        $where[] = "(academic_books.cover_image_path IS NOT NULL AND academic_books.cover_image_path <> '')";

        // Always exclude teacher guides/resources (case-insensitive check on title)
        $where[] = "(LOWER(academic_books.title) NOT LIKE ? AND LOWER(academic_books.title) NOT LIKE ? AND LOWER(academic_books.title) NOT LIKE ?)";
        $params[] = '%teacher%';
        $params[] = '%teacher\'s guide%';
        $params[] = '%guide%';
        $types .= 'sss';

        // Search filter
        if (!empty($filters['search'])) {
            $where[] = "(academic_books.title LIKE ? OR academic_books.author LIKE ? OR academic_books.ISBN LIKE ? OR academic_books.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'ssss';
        }

        // Subject filter
        if (!empty($filters['subject'])) {
            $where[] = "academic_books.subject = ?";
            $params[] = $filters['subject'];
            $types .= 's';
        }

        // Level filter
        if (!empty($filters['level'])) {
            $where[] = "academic_books.level = ?";
            $params[] = $filters['level'];
            $types .= 's';
        }

        // Language filter
        if (!empty($filters['language'])) {
            $where[] = "academic_books.language = ?";
            $params[] = $filters['language'];
            $types .= 's';
        }

        // Price range filter
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $where[] = "academic_books.ebook_price >= ?";
            $params[] = floatval($filters['min_price']);
            $types .= 'd';
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $where[] = "academic_books.ebook_price <= ?";
            $params[] = floatval($filters['max_price']);
            $types .= 'd';
        }

        // Build WHERE clause
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Sort order
        $orderBy = 'created_at DESC';
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_low':
                    $orderBy = 'ebook_price ASC';
                    break;
                case 'price_high':
                    $orderBy = 'ebook_price DESC';
                    break;
                case 'title_asc':
                    $orderBy = 'title ASC';
                    break;
                case 'title_desc':
                    $orderBy = 'title DESC';
                    break;
                case 'newest':
                    $orderBy = 'created_at DESC';
                    break;
                case 'oldest':
                    $orderBy = 'created_at ASC';
                    break;
            }
        }

        $sql = "SELECT academic_books.*, users.ADMIN_NAME, users.ADMIN_USERKEY
                FROM academic_books
                LEFT JOIN users
                    ON academic_books.publisher_id = users.ADMIN_ID
                {$whereClause}
                ORDER BY academic_books.{$orderBy}";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }

        mysqli_stmt_close($stmt);
        return $books;
    }

    public function getFilterOptions(): array
    {
        $subjects = [];
        $levels = [];
        $languages = [];
        $priceRange = ['min_price' => 0, 'max_price' => 1000];

        // Keep filter options aligned with the Academic listing rules
        // Only show academic books that have a cover image available (same as selectBooks)
        // NOTE: use SQL string literal escaping (''), not backslashes, for portability
        $baseWhere = "WHERE (cover_image_path IS NOT NULL AND cover_image_path <> '') AND (LOWER(title) NOT LIKE '%teacher%' AND LOWER(title) NOT LIKE '%teacher''s guide%' AND LOWER(title) NOT LIKE '%guide%')";

        // Get distinct subjects
        $result = mysqli_query($this->conn, "SELECT DISTINCT subject FROM academic_books {$baseWhere} AND subject IS NOT NULL AND subject != '' ORDER BY subject");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $subjects[] = $row['subject'];
            }
        }

        // Get distinct levels
        $result = mysqli_query($this->conn, "SELECT DISTINCT level FROM academic_books {$baseWhere} AND level IS NOT NULL AND level != '' ORDER BY level");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $levels[] = $row['level'];
            }
        }

        // Get distinct languages
        $result = mysqli_query($this->conn, "SELECT DISTINCT language FROM academic_books {$baseWhere} AND language IS NOT NULL AND language != '' ORDER BY language");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $languages[] = $row['language'];
            }
        }

        // Get price range
        $result = mysqli_query($this->conn, "SELECT MIN(ebook_price) as min_price, MAX(ebook_price) as max_price FROM academic_books {$baseWhere} AND ebook_price > 0");
        if ($result) {
            $priceData = mysqli_fetch_assoc($result);
            if ($priceData) {
                $priceRange = $priceData;
            }
        }

        return [
            'subjects' => $subjects,
            'levels' => $levels,
            'languages' => $languages,
            'price_range' => $priceRange
        ];
    }


    public function selectBookByPublicKey(string $public_key): ?array
    {
        // For the detail view, we load the book by public_key only.
        // We no longer require a PDF to exist; the view can handle missing
        // content (e.g. showing "No Cover" or "Not available" states).
        $sql = "SELECT academic_books.*, users.ADMIN_NAME, users.ADMIN_USERKEY
                FROM academic_books
                LEFT JOIN users
                    ON academic_books.publisher_id = users.ADMIN_ID
                WHERE public_key = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "s", $public_key);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $book = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $book ?: null;
    }

    /**
     * Get books that have been added to the academic listings table.
     * Results are ordered by the time they were listed (newest first).
     * An optional limit may be provided for home‑page preview.
     */
    public function getAcademicListedBooks(?int $limit = null): array
    {
        $sql = "SELECT ab.*, u.ADMIN_NAME, u.ADMIN_USERKEY
                FROM academic_listings al
                JOIN academic_books ab ON al.book_id = ab.id
                LEFT JOIN users u ON ab.publisher_id = u.ADMIN_ID
                ORDER BY al.created_at DESC";
        if ($limit !== null) {
            $sql .= " LIMIT " . intval($limit);
        }

        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            throw new Exception("Query failed: " . mysqli_error($this->conn));
        }

        $books = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $books[] = $row;
        }
        mysqli_free_result($result);
        return $books;
    }

    public function selectUserById($id): ?array
    {
        $sql = "SELECT * FROM users 
                WHERE ADMIN_ID = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $user ?: null;
    }
}
