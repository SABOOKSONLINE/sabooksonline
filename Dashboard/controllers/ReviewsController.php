<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class ReviewsController
{
    private $reviewsModel;

    public function __construct($conn)
    {
        $this->reviewsModel = new ReviewsModel($conn);
    }

    public function renderReviewsByContentId($contentId)
    {
        $reviews = $this->reviewsModel->getReviewsByContentId($contentId);
        include __DIR__ . "/../views/includes/layouts/tables/reviews_table.php";
    }

    public function renderReviewsByUserKey($userkey)
    {
        $reviews = $this->reviewsModel->getReviewsByUserKey($userkey);
        include __DIR__ . "/../views/includes/layouts/tables/reviews_table.php";
    }

    public function updateReview($id, $data)
    {
        return $this->reviewsModel->updateReview($id, $data);
    }

    public function deleteReview($id)
    {
        return $this->reviewsModel->deleteReview($id);
    }
}
