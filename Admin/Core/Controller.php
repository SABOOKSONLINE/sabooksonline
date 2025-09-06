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
        extract($data);
        require __DIR__ . "/../View/pages/" . $viewPath . ".php";
    }
}
