<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class AcademicBookController
{
    private $academicBookModel;

    public function __construct($conn)
    {
        $this->academicBookModel = new AcademicBookModel($conn);
    }

    public function insertBook(array $data): bool
    {
        try {
            return $this->academicBookModel->insertBook($data);
        } catch (Exception $e) {
            error_log("Academic Book insert error: " . $e->getMessage());
            return false;
        }
    }

    public function updateBook(array $data): bool
    {
        try {
            return $this->academicBookModel->updateBook($data);
        } catch (Exception $e) {
            error_log("Academic Book update error: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBook(int $id): bool
    {
        try {
            return $this->academicBookModel->deleteBook($id);
        } catch (Exception $e) {
            error_log("Academic Book delete error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllBooks(int $publisher_id): array
    {
        try {
            return $this->academicBookModel->selectBooks($publisher_id);
        } catch (Exception $e) {
            error_log("Get all academic books error: " . $e->getMessage());
            return [];
        }
    }

    public function getBookById(int $id, int $publisher_id): ?array
    {
        try {
            return $this->academicBookModel->selectBookById($id, $publisher_id);
        } catch (Exception $e) {
            error_log("Get academic book by ID error: " . $e->getMessage());
            return null;
        }
    }
}
