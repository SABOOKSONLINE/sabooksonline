<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Config/connection.php';
require_once __DIR__ . '/../controllers/ReviewsController.php';

$reviewController = new ReviewsController($conn);

function validValues(array $data): void
{
    if (
        empty(trim($data['name'])) ||
        empty(trim($data['rating'])) ||
        empty(trim($data['comment'])) ||
        empty(trim($data['user_id'])) ||
        empty(trim($data['book_id']))
    ) {
        die("All fields are required.");
    }

    if (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
        die("Invalid rating. Rating must be between 1 and 5.");
    }

    if (!is_numeric($data['user_id'])) {
        die("Invalid user or post ID.");
    }
}

function formDataArray(): array
{
    return [
        'name'         => trim($_POST['name'] ?? ''),
        'user_img_url' => trim($_POST['user_img_url'] ?? ''),
        'rating'       => (int) ($_POST['rating_value'] ?? 0),
        'comment'      => trim($_POST['comment'] ?? ''),
        'user_id'      => (int) ($_POST['user_id'] ?? 0),
        'book_id'      => trim($_POST['book_id'] ?? ''),
    ];
}

function insertReviewData($reviewController): void
{
    $data = formDataArray();
    validValues($data);

    try {
        $reviewController->insertReview($data);
        header("Location: /library/book/" . $data['book_id']);
    } catch (Exception $e) {
        echo "Failed to submit review: " . $e->getMessage();
    }
}

insertReviewData($reviewController);
