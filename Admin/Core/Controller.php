<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Controller
{
    protected mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    protected function render(string $viewPath, array $data = [])
    {
        // Extract array keys as variables (e.g., $data["books"] becomes $books)
        // Store original $data before extract so views can use $data["key"] syntax
        $viewData = $data;
        extract($data);
        // Make $data available to views (extract may have overwritten it)
        $data = $viewData;
        require __DIR__ . "/../View/pages/" . $viewPath . ".php";
    }
}
