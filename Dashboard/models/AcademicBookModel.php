<?php
// Updated: Academic book update now supports saving without PDF
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

    public function insertBook(array $data): bool
    {
        $sql = "INSERT INTO academic_books (
                    publisher_id,
                    public_key,
                    title,
                    author,
                    editor,
                    description,
                    subject,
                    level,
                    language,
                    edition,
                    pages,
                    ISBN,
                    publish_date,
                    cover_image_path,
                    ebook_price,
                    pdf_path,
                    physical_book_price,
                    link,
                    approved,
                    aligned,
                    status
                ) VALUES (
                    ?,
                    ?,
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?,
                    ?,
                    ?,
                    ?
                )";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        // Prepare variables for binding (null values need to be converted)
        $approved = $data['approved'] ?? 0;
        $aligned = $data['aligned'] ?? 0;
        $status = $data['status'] ?? '';
        
        mysqli_stmt_bind_param(
            $stmt,
            "isssssssssssssdsssiis",
            $data['publisher_id'],
            $data['public_key'],
            $data['title'],
            $data['author'],
            $data['editor'],
            $data['description'],
            $data['subject'],
            $data['level'],
            $data['language'],
            $data['edition'],
            $data['pages'],
            $data['isbn'],
            $data['publish_date'],
            $data['cover_image_path'],
            $data['ebook_price'],
            $data['pdf_path'],
            $data['physical_book_price'],
            $data['link'],
            $approved,
            $aligned,
            $status
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }


    public function updateBook(array $data): bool
    {
        // Convert empty pdf_path to null for proper NULL handling in database
        $pdf_path = (!empty($data['pdf_path']) && trim($data['pdf_path']) !== '') ? $data['pdf_path'] : null;
        
        $sql = "UPDATE academic_books SET
                    title = ?,
                    author = ?,
                    editor = ?,
                    description = ?,
                    subject = ?,
                    level = ?,
                    language = ?,
                    edition = ?,
                    pages = ?,
                    ISBN = ?,
                    publish_date = ?,
                    cover_image_path = ?,
                    pdf_path = ?,
                    ebook_price = ?,
                    physical_book_price = ?,
                    link = ?,
                    approved = ?,
                    aligned = ?,
                    status = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        // Extract values to variables for mysqli_stmt_bind_param (requires variables by reference)
        $title = $data['title'];
        $author = $data['author'];
        $editor = $data['editor'];
        $description = $data['description'];
        $subject = $data['subject'];
        $level = $data['level'];
        $language = $data['language'];
        $edition = $data['edition'];
        $pages = $data['pages'];
        $isbn = $data['isbn'];
        $publish_date = $data['publish_date'];
        $cover_image_path = $data['cover_image_path'];
        $ebook_price = $data['ebook_price'];
        $physical_book_price = $data['physical_book_price'];
        $link = $data['link'];
        $approved = $data['approved'] ?? 0;
        $aligned = $data['aligned'] ?? 0;
        $status = $data['status'] ?? '';
        $id = $data['id'];
        
        // Type string: 20 parameters (19 SET values + 1 WHERE id)
        // s=string, d=double, i=integer
        // Order: title(s), author(s), editor(s), description(s), subject(s), level(s), language(s), edition(s), pages(s), ISBN(s), publish_date(s), cover_image_path(s), pdf_path(s), ebook_price(d), physical_book_price(d), link(s), approved(i), aligned(i), status(s), id(i)
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssddsiisi",
            $title,
            $author,
            $editor,
            $description,
            $subject,
            $level,
            $language,
            $edition,
            $pages,
            $isbn,
            $publish_date,
            $cover_image_path,
            $pdf_path,
            $ebook_price,
            $physical_book_price,
            $link,
            $approved,
            $aligned,
            $status,
            $id
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function deleteBook(int $id): bool
    {
        $sql = "DELETE FROM academic_books WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function selectBooks(int $publisher_id, array $filters = []): array
    {
        $sql = "SELECT * FROM academic_books WHERE publisher_id = ?";
        $params = [$publisher_id];
        $types = "i";
        
        // Add search filter
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE ? OR author LIKE ? OR editor LIKE ? OR subject LIKE ? OR isbn LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sssss";
        }
        
        // Add status filter (based on PDF availability)
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'available') {
                $sql .= " AND pdf_path IS NOT NULL AND pdf_path != ''";
            } elseif ($filters['status'] === 'draft') {
                $sql .= " AND (pdf_path IS NULL OR pdf_path = '')";
            }
        }
        
        // Add subject filter
        if (!empty($filters['subject'])) {
            $sql .= " AND subject = ?";
            $params[] = $filters['subject'];
            $types .= "s";
        }
        
        // Add price range filter
        if (!empty($filters['min_price'])) {
            $sql .= " AND (ebook_price >= ? OR physical_book_price >= ?)";
            $minPrice = (float)$filters['min_price'];
            $params[] = $minPrice;
            $params[] = $minPrice;
            $types .= "dd";
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND (ebook_price <= ? OR physical_book_price <= ?)";
            $maxPrice = (float)$filters['max_price'];
            $params[] = $maxPrice;
            $params[] = $maxPrice;
            $types .= "dd";
        }
        
        // Add date range filter
        if (!empty($filters['date_from'])) {
            $sql .= " AND publish_date >= ?";
            $params[] = $filters['date_from'];
            $types .= "s";
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND publish_date <= ?";
            $params[] = $filters['date_to'];
            $types .= "s";
        }
        
        // Add sort order
        $orderBy = "academic_books.created_at DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'title_asc':
                    $orderBy = "title ASC";
                    break;
                case 'title_desc':
                    $orderBy = "title DESC";
                    break;
                case 'date_newest':
                    $orderBy = "publish_date DESC, created_at DESC";
                    break;
                case 'date_oldest':
                    $orderBy = "publish_date ASC, created_at ASC";
                    break;
                case 'price_low':
                    $orderBy = "COALESCE(ebook_price, physical_book_price, 0) ASC";
                    break;
                case 'price_high':
                    $orderBy = "COALESCE(ebook_price, physical_book_price, 0) DESC";
                    break;
            }
        }
        
        $sql .= " ORDER BY " . $orderBy;

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
    
    /**
     * Get distinct subjects for filter dropdown
     * @param int $publisher_id Publisher ID
     * @return array Subjects
     */
    public function getSubjectsByPublisherId(int $publisher_id): array
    {
        $sql = "SELECT DISTINCT subject FROM academic_books WHERE publisher_id = ? AND subject IS NOT NULL AND subject != '' ORDER BY subject";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            return [];
        }
        
        mysqli_stmt_bind_param($stmt, "i", $publisher_id);
        if (!mysqli_stmt_execute($stmt)) {
            return [];
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row['subject'];
        }
        
        mysqli_stmt_close($stmt);
        return $subjects;
    }
    
    /**
     * Get price range for publisher's academic books
     * @param int $publisher_id Publisher ID
     * @return array Min and max prices
     */
    public function getPriceRangeByPublisherId(int $publisher_id): array
    {
        $sql = "SELECT 
                    MIN(LEAST(
                        COALESCE(ebook_price, 999999),
                        COALESCE(physical_book_price, 999999)
                    )) as min_price,
                    MAX(GREATEST(
                        COALESCE(ebook_price, 0),
                        COALESCE(physical_book_price, 0)
                    )) as max_price
                FROM academic_books
                WHERE publisher_id = ? 
                AND (ebook_price > 0 OR physical_book_price > 0)";
        
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            return ['min_price' => 0, 'max_price' => 1000];
        }
        
        mysqli_stmt_bind_param($stmt, "i", $publisher_id);
        if (!mysqli_stmt_execute($stmt)) {
            return ['min_price' => 0, 'max_price' => 1000];
        }
        
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        
        return [
            'min_price' => $row['min_price'] ?? 0,
            'max_price' => $row['max_price'] ?? 1000
        ];
    }

    public function selectBookById(int $id, int $publisher_id): ?array
    {
        $sql = "SELECT * FROM academic_books WHERE id = ? AND publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "ii", $id, $publisher_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $book = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);
        return $book ?: null;
    }
}
