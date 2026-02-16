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

    public function getAllBooks(array $filters = []): array
    {
        try {
            return $this->academicBookModel->selectBooks($filters);
        } catch (Exception $e) {
            error_log("Get all academic books error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getFilterOptions(): array
    {
        try {
            return $this->academicBookModel->getFilterOptions();
        } catch (Exception $e) {
            error_log("Get filter options error: " . $e->getMessage());
            return ['subjects' => [], 'levels' => [], 'languages' => [], 'price_range' => ['min_price' => 0, 'max_price' => 0]];
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
