<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/ReviewsModel.php";
require_once __DIR__ . "/../controllers/ReviewsController.php";

$reviewsController = new ReviewsController($conn);

function deleteReview($reviewsController)
{
    try {
        $reviewId = $_GET["id"];
        $reviewsController->deleteReview($reviewId);
        header("Location: /views/manage_reviews.php?delete=success");
    } catch (Exception $e) {
        header("Location: /views/manage_reviews.php?delete=fail");
    }
}

if ($_GET["id"] && $_GET["action"] == "delete") {
    deleteReview($reviewsController);
}
