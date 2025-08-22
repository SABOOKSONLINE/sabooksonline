<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/ReviewsModel.php';

class ReviewsController
{
    private $reviewsModel;

    public function __construct($conn)
    {
        $this->reviewsModel = new ReviewsModel($conn);
    }

    public function insertReview($data): void
    {
        $this->reviewsModel->insertReview($data);
    }

    public function renderReviews(): void
    {
        $bookId = $_GET['q'];
        $reviews = $this->reviewsModel->getReviewsByBookId($bookId);
        include_once __DIR__ . '/../views/layout/reviews.php';
    }
}
