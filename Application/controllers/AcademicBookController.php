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

    public function getAllBooks(): array
    {
        try {
            return $this->academicBookModel->selectBooks();
        } catch (Exception $e) {
            error_log("Get all academic books error: " . $e->getMessage());
            return [];
        }
    }

    public function getBookById(string $public_key): ?array
    {
        try {
            return $this->academicBookModel->selectBookByPublicKey($public_key);
        } catch (Exception $e) {
            error_log("Get academic book by ID error: " . $e->getMessage());
            return null;
        }
    }
}
