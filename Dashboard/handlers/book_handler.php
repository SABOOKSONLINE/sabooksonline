<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

$bookController = new BookListingController($conn);

function formDataArray()
{
    $bookId = htmlspecialchars($_POST["book_id"]);
    $userId = htmlspecialchars($_POST["user_id"]);
    $title = htmlspecialchars($_POST["book_title"]);
    $price = htmlspecialchars($_POST["book_price"]);

    $category = isset($_POST["book_category"]) && is_array($_POST["book_category"])
        ? implode(", ", array_map("htmlspecialchars", $_POST["book_category"]))
        : "";

    $authors = htmlspecialchars($_POST["book_authors"] ?? '');
    $description = htmlspecialchars($_POST["book_desc"]);
    $isbn = htmlspecialchars($_POST["book_isbn"]);
    $website = htmlspecialchars($_POST["book_website"]);
    $status = htmlspecialchars($_POST["book_status"]);
    $publishedDate = htmlspecialchars($_POST["book_date_published"]);
    $keywords = htmlspecialchars($_POST["book_keywords"] ?? $category);
    $publisher = htmlspecialchars($_POST["book_publisher"]);
    $language = htmlspecialchars($_POST["book_languages"] ?? '');
    $stock = htmlspecialchars($_POST["book_stock"]);

    $type = htmlspecialchars($_POST["book_type"] ?? 'Book');

    if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../cms-data/book-covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $cover = uniqid('', true) . basename($_FILES['book_cover']['name']);
        if (!move_uploaded_file($_FILES['book_cover']['tmp_name'], $uploadDir . basename($_FILES['book_cover']['name']))) {
            die("Failed to upload book cover.");
        }
    } else {
        $cover = htmlspecialchars($_POST['existing_cover'] ?? null);
    }

    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');
    $datePosted = htmlspecialchars($_POST["book_date_published"] ?? date('Y-m-d'));

    $bookData = [
        'userid' => $userId,
        'bookId' => $bookId,
        'title' => $title,
        'price' => $price,
        'category' => $category,
        'authors' => $authors,
        'description' => $description,
        'isbn' => $isbn,
        'website' => $website,
        'status' => $status,
        'published_date' => $publishedDate,
        'keywords' => $keywords,
        'type' => $type,
        'cover' => $cover,
        'created' => $created,
        'modified' => $modified,
        'dateposted' => $datePosted,
        'publisher' => $publisher,
        'languages' => $language,
        'stock' => $stock
    ];

    // echo "<pre>";
    // print_r($bookData);
    // echo "</pre>";
    // die();

    return $bookData;
}

function insertBookHandler($bookController)
{
    $bookData = formDataArray();

    if (
        empty($bookData['userid']) ||
        empty($bookData['title']) ||
        empty($bookData['cover']) ||
        empty($bookData['category']) ||
        empty($bookData['publisher']) ||
        empty($bookData['authors']) ||
        empty($bookData['description']) ||
        empty($bookData['isbn']) ||
        empty($bookData['website']) ||
        empty($bookData['languages']) ||
        empty($bookData['status']) ||
        empty($bookData['stock']) ||
        empty($bookData['type']) ||
        empty($bookData['dateposted']) ||
        empty($bookData['keywords'])
    ) {
        die("Validation failed: Missing required fields.");
    }

    try {
        echo $bookController->insertBookData($bookData);
        header("Location: /dashboards/listings?status=success");
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

function updateBookHandler($bookController)
{
    try {
        $bookData = formDataArray();
        $bookId = $bookData['bookId'];

        $bookController->updateBookData($bookId, $bookData);
        header("Location: /dashboards/listings?update=success");
    } catch (Exception $e) {
        error_log("Update failed: " . $e->getMessage());
        header("Location: /dashboards/listings?update=fail&error=" . urlencode($e->getMessage()));
        exit;
    }
}

function deleteBookHandler($bookController)
{
    try {
        $bookContentId = $_GET["id"];

        $bookController->deleteBookListing($bookContentId);
        header("Location: /dashboards/listings?delete=success");
    } catch (Exception $e) {
        header("Location: /dashboards/listings?delete=fail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["action"] == "insert") {
    insertBookHandler($bookController);
}

if ($_GET["id"] && $_GET['action'] == "update") {
    updateBookHandler($bookController);
}

if ($_GET["id"] && $_GET["action"] == "delete") {
    deleteBookHandler($bookController);
}
