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
            isbn,
            publish_date,
            cover_image_path,
            pdf_path,
            ebook_price,
            physical_book_price,
            link
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
            die();
        }

        mysqli_stmt_bind_param(
            $stmt,
            "isssssssssssssddsi",
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
            $data['pdf_path'],
            $data['ebook_price'],
            $data['physical_book_price'],
            $data['link']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Execute failed: " . mysqli_stmt_error($stmt));
            die();
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    public function updateBook(array $data): bool
    {
        $sql = "UPDATE academic_books SET
                    publisher_id = ?,
                    public_key = ?,
                    title = ?,
                    author = ?,
                    editor = ?,
                    description = ?,
                    subject = ?,
                    level = ?,
                    language = ?,
                    edition = ?,
                    pages = ?,
                    isbn = ?,
                    publish_date = ?,
                    cover_image_path = ?,
                    pdf_path = ?,
                    ebook_price = ?,
                    physical_book_price = ?,
                    link = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "isssssssssssssssssi",
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
            $data['pdf_path'],
            $data['ebook_price'],
            $data['physical_book_price'],
            $data['link'],
            $data['id']
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

    public function selectBooks(int $publisher_id): array
    {
        $sql = "SELECT * FROM academic_books WHERE publisher_id = ?";

        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . mysqli_error($this->conn));
        }

        mysqli_stmt_bind_param($stmt, "i", $publisher_id);

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
