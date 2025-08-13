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
    $title = $_POST["book_title"];
    $price = htmlspecialchars($_POST["book_price"] ?? '0');
    $Eprice = htmlspecialchars($_POST["Ebook_price"] ?? '0');
    $Aprice = htmlspecialchars($_POST["Abook_price"] ?? '0');



    $category = isset($_POST["book_category"]) && is_array($_POST["book_category"])
        ? implode(", ", array_map("htmlspecialchars", $_POST["book_category"]))
        : "";

    $authors = htmlspecialchars($_POST["book_authors"] ?? '');
    $description = $_POST["book_desc"];
    $isbn = htmlspecialchars($_POST["book_isbn"]);
    $website = htmlspecialchars($_POST["book_website"]);
    $status = htmlspecialchars($_POST["book_status"]);
    $publishedDate = htmlspecialchars($_POST["book_date_published"]);
    $keywords = htmlspecialchars($_POST["book_keywords"] ?? $category);
    $publisher = htmlspecialchars($_POST["book_publisher"]);
    $language = htmlspecialchars($_POST["book_languages"] ?? '');
    $stock = htmlspecialchars($_POST["book_stock"] ?? 'instock');

    $type = htmlspecialchars($_POST["book_type"] ?? 'Book');

    if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../cms-data/book-covers/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique, safe filename
    $ext = pathinfo($_FILES['book_cover']['name'], PATHINFO_EXTENSION);
    $cover = uniqid('', true) . '.' . $ext;

    if (!move_uploaded_file($_FILES['book_cover']['tmp_name'], $uploadDir . $cover)) {
        die("Failed to upload book cover.");
    }
} else {
    $cover = htmlspecialchars($_POST['existing_cover'] ?? '');
}


    if (isset($_FILES['book_pdf']) && $_FILES['book_pdf']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../cms-data/book-pdfs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($_FILES['book_pdf']['name']);
        $safeName = preg_replace("/[^a-zA-Z0-9-_\.]/", "_", $originalName);
        $pdf = uniqid('', true) . $safeName;

        if (!move_uploaded_file($_FILES['book_pdf']['tmp_name'], $uploadDir . $pdf)) {
            die("Failed to upload book PDF.");
        }
    } else {
        $pdf = htmlspecialchars($_POST['existing_pdf'] ?? '');
    }


    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');
    $datePosted = htmlspecialchars($_POST["book_date_published"] ?? date('Y-m-d'));

    $bookData = [
        'userid' => $userId,
        'bookId' => $bookId,
        'title' => $title,
        'price' => $price,
        'Eprice' => $Eprice,
        'Aprice' => $Aprice,
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
        'stock' => $stock,
        'pdf' => $pdf
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

    echo "<pre>";
    foreach ([
        'userid', 'title', 'cover', 'category', 'authors', 'description',
        'isbn', 'languages', 'status', 'stock', 'type', 'dateposted'
    ] as $key) {
        if (empty($bookData[$key])) {
            echo "❌ '$key' is empty or missing.\n";
        } else {
            echo "✅ '$key' = " . $bookData[$key] . "\n";
        }
    }
    echo "</pre>";


    if (
        empty($bookData['userid']) ||
        empty($bookData['title']) ||
        empty($bookData['cover']) ||
        empty($bookData['category']) ||
        empty($bookData['authors']) ||
        empty($bookData['description']) ||
        empty($bookData['isbn']) ||
        empty($bookData['languages']) ||
        empty($bookData['status']) ||
        empty($bookData['stock']) ||
        empty($bookData['type']) ||
        empty($bookData['dateposted'])
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
        $bookContentId = $_GET["id"];
        $contentId = htmlspecialchars(trim($bookContentId));

            // Server-side cache file

            // Auto-create the folder if missing
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0775, true);
            }

        $cacheFile = __DIR__ . '/../../Application/cache/book_' . $contentId . '.html';
            
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

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

        $cacheFile = __DIR__ . '/../../Application/cache/book_' . $bookContentId . '.html';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

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
