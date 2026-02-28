<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

$bookController = new BookListingController($conn);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function formDataArray()
{
    /* ========= BASIC BOOK DATA ========= */
    $bookId   = htmlspecialchars($_POST["book_id"]);
    $bookPk   = $_POST["book_public_key"];
    $userId   = htmlspecialchars($_POST["user_id"]);
    $title    = $_POST["book_title"];
    $price    = htmlspecialchars($_POST["book_price"] ?? '0');
    $Eprice   = htmlspecialchars($_POST["Ebook_price"] ?? '0');
    $Aprice   = htmlspecialchars($_POST["Abook_price"] ?? '0');

    /* ========= HARDCOPY DATA ========= */
    $hcPrice            = !empty($_POST["hc_price"]) ? htmlspecialchars($_POST["hc_price"]) : null;
    $hcDiscountPercent  = !empty($_POST["hc_discount_percent"]) ? htmlspecialchars($_POST["hc_discount_percent"]) : null;
    $hcCountry          = !empty($_POST["hc_country"]) ? htmlspecialchars($_POST["hc_country"]) : null;
    $hcPages            = !empty($_POST["hc_pages"]) ? htmlspecialchars($_POST["hc_pages"]) : null;
    $hcWeight           = !empty($_POST["hc_weight_kg"]) ? htmlspecialchars($_POST["hc_weight_kg"]) : null;
    $hcHeight           = !empty($_POST["hc_height_cm"]) ? htmlspecialchars($_POST["hc_height_cm"]) : null;
    $hcWidth            = !empty($_POST["hc_width_cm"]) ? htmlspecialchars($_POST["hc_width_cm"]) : null;
    $hcDate             = !empty($_POST["hc_release_date"]) ? htmlspecialchars($_POST["hc_release_date"]) : null;
    $hcContributors     = !empty($_POST["hc_contributors"]) ? htmlspecialchars($_POST["hc_contributors"]) : null;
    $hcStockCount       = !empty($_POST["hc_stock_count"]) ? htmlspecialchars($_POST["hc_stock_count"]) : null;

    /* ========= DISCOUNTED HARDCOPY PRICE ========= */
    $hcFinalPrice = $hcPrice;

    if ($hcPrice > 0 && $hcDiscountPercent > 0) {
        $hcFinalPrice = $hcPrice - ($hcPrice * ($hcDiscountPercent / 100));
        $hcFinalPrice = number_format($hcFinalPrice, 2, '.', '');
    }

    /* ========= CATEGORY ========= */
    $category = isset($_POST["book_category"]) && is_array($_POST["book_category"])
        ? implode(", ", array_map("htmlspecialchars", $_POST["book_category"]))
        : "";

    /* ========= OTHER BOOK FIELDS ========= */
    $authors       = htmlspecialchars($_POST["book_authors"] ?? '');
    $description   = $_POST["book_desc"];
    $isbn          = htmlspecialchars($_POST["book_isbn"]);
    $website       = htmlspecialchars($_POST["book_website"]);
    $status        = htmlspecialchars($_POST["book_status"]);
    $publishedDate = htmlspecialchars($_POST["book_date_published"]);
    $keywords      = htmlspecialchars($_POST["book_keywords"] ?? $category);
    $publisher     = htmlspecialchars($_POST["book_publisher"]);
    $language      = htmlspecialchars($_POST["book_languages"] ?? '');
    $stock         = htmlspecialchars($_POST["book_stock"] ?? 'instock');
    $type          = htmlspecialchars($_POST["book_type"] ?? 'Book');

    /* ========= FILE UPLOAD: COVER ========= */
    if (isset($_FILES['book_cover']) && $_FILES['book_cover']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../cms-data/book-covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['book_cover']['name'], PATHINFO_EXTENSION);
        $cover = bin2hex(random_bytes(16)) . '.' . $ext;

        if (!move_uploaded_file($_FILES['book_cover']['tmp_name'], $uploadDir . $cover)) {
            die("Failed to upload book cover.");
        }
    } else {
        $cover = htmlspecialchars($_POST['existing_cover'] ?? '');
    }

    /* ========= FILE UPLOAD: PDF ========= */
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

    /* ========= DATES ========= */
    $created     = date('Y-m-d H:i:s');
    $modified    = date('Y-m-d H:i:s');
    $datePosted  = htmlspecialchars($_POST["book_date_published"] ?? date('Y-m-d'));

    /* ========= BUILD FINAL ARRAY ========= */
    $bookData = [
        // Book main data
        'userid'         => $userId,
        'bookId'         => $bookId,
        'bookPk'         => $bookPk,
        'title'          => $title,
        'price'          => $price,
        'Eprice'         => $Eprice,
        'Aprice'         => $Aprice,
        'category'       => $category,
        'authors'        => $authors,
        'description'    => $description,
        'isbn'           => $isbn,
        'website'        => $website,
        'status'         => $status,
        'published_date' => $publishedDate,
        'keywords'       => $keywords,
        'type'           => $type,
        'cover'          => $cover,
        'created'        => $created,
        'modified'       => $modified,
        'dateposted'     => $datePosted,
        'publisher'      => $publisher,
        'languages'      => $language,
        'stock'          => $stock,
        'pdf'            => $pdf,

        // Hardcopy fields
        'hc_price'            => $hcPrice,
        'hc_discount_percent' => $hcDiscountPercent,
        'hc_final_price'      => $hcFinalPrice,
        'hc_country'          => $hcCountry,
        'hc_pages'            => $hcPages,
        'hc_weight_kg'        => $hcWeight,
        'hc_height_cm'        => $hcHeight,
        'hc_width_cm'         => $hcWidth,
        'hc_release_date'     => $hcDate,
        'hc_contributors'     => $hcContributors,
        'hc_stock_count'      => $hcStockCount
    ];

    return $bookData;
}


function insertBookHandler($bookController)
{
    $bookData = formDataArray();

    // Get the current tab from POST data (default to book-details)
    $currentTab = isset($_POST['current_tab']) ? htmlspecialchars($_POST['current_tab']) : 'book-details';

    /* ===========================================================
        VALIDATION
    ============================================================ */
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
        $_SESSION['alert_type'] = 'danger';
        $_SESSION['alert_message'] = 'Validation failed: Missing required fields.';
        header("Location: /dashboards/add/listings?tab=" . $currentTab);
        exit;
    }

    try {
        /* ===========================================================
            1. INSERT MAIN BOOK
        ============================================================ */
        $bookDetails = $bookController->insertBookData($bookData);
        $bookId = $bookDetails["id"];
        $contentId = $bookDetails["content_id"];

        $bookData['bookId'] = $bookId;

        /* ===========================================================
            2. INSERT HARDCOPY IF ANY FIELD PROVIDED
        ============================================================ */
        $hasHardcopyData = false;

        $hardcopyKeys = [
            "hc_price",
            "hc_discount_percent",
            "hc_country",
            "hc_pages",
            "hc_weight_kg",
            "hc_height_cm",
            "hc_width_cm",
            "hc_release_date",
            "hc_contributors",
            "hc_stock_count"
        ];

        foreach ($hardcopyKeys as $key) {
            if (isset($bookData[$key]) && $bookData[$key] !== "" && $bookData[$key] !== null) {
                $hasHardcopyData = true;
                break;
            }
        }

        if ($hasHardcopyData) {
            $hardcopyPayload = [
                'book_id'             => $bookId,
                'hc_price'            => $bookData['hc_price'],
                'hc_discount_percent' => $bookData['hc_discount_percent'],
                'hc_country'          => $bookData['hc_country'],
                'hc_pages'            => $bookData['hc_pages'],
                'hc_weight_kg'        => $bookData['hc_weight_kg'],
                'hc_height_cm'        => $bookData['hc_height_cm'],
                'hc_width_cm'         => $bookData['hc_width_cm'],
                'hc_release_date'     => $bookData['hc_release_date'],
                'hc_contributors'     => $bookData['hc_contributors'],
                'hc_stock_count'      => $bookData['hc_stock_count']
            ];

            $bookController->insertHardcopy($hardcopyPayload);
        }

        /* ===========================================================
            3. SEND NOTIFICATION ABOUT NEW BOOK
        ============================================================ */
        try {
            require_once __DIR__ . "/../../Application/helpers/NotificationHelper.php";
            NotificationHelper::notifyNewBook($bookData['title'], $contentId, $conn);
        } catch (Exception $e) {
            error_log("Failed to send new book notification: " . $e->getMessage());
        }
        
        /* ===========================================================
            4. SET SUCCESS MESSAGE AND REDIRECT
        ============================================================ */
        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_message'] = 'Book created successfully! You can now add additional details.';

        // After successful insert, move to ebook-details tab
        $nextTab = "ebook-details";

        header("Location: /dashboards/listings/" . $contentId . "?tab=" . $nextTab);
        exit;
    } catch (Exception $e) {
        error_log("Insert failed: " . $e->getMessage());
        $_SESSION['alert_type'] = 'danger';
        $_SESSION['alert_message'] = 'Failed to create book: ' . $e->getMessage();
        header("Location: /dashboards/add/listings?tab=" . $currentTab);
        exit;
    }
}


function updateBookHandler($bookController)
{
    try {
        $bookData = formDataArray();

        if (empty($bookData['bookId'])) {
            throw new Exception("Missing bookId in update request.");
            die;
        }

        $bookId = $bookData['bookId'];

        if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
            throw new Exception("Invalid or missing content ID.");
        }

        // Get current tab from POST data (preserve the tab user was on)
        $currentTab = isset($_POST['current_tab']) ? htmlspecialchars($_POST['current_tab']) : 'book-details';

        // Update Main Book
        $bookController->updateBookData($bookId, $bookData);
        $contentId = $bookData['bookPk'];

        /*
        |--------------------------------------------------------------------------
        | Hardcopy Insert or Update
        |--------------------------------------------------------------------------
        */
        $hardcopyFields = [
            'hc_price',
            'hc_discount_percent',
            'hc_country',
            'hc_pages',
            'hc_weight_kg',
            'hc_height_cm',
            'hc_width_cm',
            'hc_release_date',
            'hc_contributors',
            'hc_stock_count'
        ];

        $hasHardcopy = false;

        foreach ($hardcopyFields as $field) {
            if (!empty($bookData[$field])) {
                $hasHardcopy = true;
                break;
            }
        }

        if ($hasHardcopy) {
            $hardcopyData = ['book_id' => $bookId];

            foreach ($hardcopyFields as $field) {
                $hardcopyData[$field] = $bookData[$field] ?? null;
            }

            $existing = $bookController->getHardcopyByBookId($bookId);

            if ($existing) {
                $bookController->updateHardcopy($hardcopyData);
            } else {
                $bookController->insertHardcopy($hardcopyData);
            }
        }

        $_SESSION['alert_type'] = 'success';
        $_SESSION['alert_message'] = 'Your changes have been saved successfully.';

        // Redirect back to the same tab they were on
        header("Location: /dashboards/listings/" . $contentId . "?tab=" . $currentTab);
        exit;
    } catch (Exception $e) {
        error_log("Update failed: " . $e->getMessage());

        $_SESSION['alert_type'] = 'danger';
        $_SESSION['alert_message'] = 'Failed to save changes: ' . $e->getMessage();

        // Get current tab or default
        $currentTab = isset($_POST['current_tab']) ? htmlspecialchars($_POST['current_tab']) : 'book-details';
        $contentId = $bookData['bookPk'] ?? '';

        if (!empty($contentId)) {
            header("Location: /dashboards/listings/" . $contentId . "?tab=" . $currentTab);
        } else {
            header("Location: /dashboards/listings?tab=" . $currentTab);
        }
        exit;
    }
}


function deleteBookHandler($bookController)
{
    try {
        if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
            throw new Exception("Invalid or missing book ID.");
        }

        $bookContentId = htmlspecialchars(trim($_GET["id"]));

        if ($bookController->deleteBookListing($bookContentId)) {
            $_SESSION['alert_type'] = 'success';
            $_SESSION['alert_message'] = 'Book deleted successfully.';
            header("Location: /dashboards/listings");
            exit;
        }
    } catch (Exception $e) {
        error_log("Delete book failed: " . $e->getMessage());

        $_SESSION['alert_type'] = 'danger';
        $_SESSION['alert_message'] = 'Failed to delete book: ' . $e->getMessage();

        header("Location: /dashboards/listings");
        exit;
    }
}

// Handle POST request for inserting a book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["action"]) && $_GET["action"] == "insert") {
    insertBookHandler($bookController);
}

// Handle POST request for updating a book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET["id"]) && isset($_GET['action']) && $_GET['action'] == "update") {
    updateBookHandler($bookController);
}

// Handle GET request for deleting a book
if (isset($_GET["id"]) && isset($_GET["action"]) && $_GET["action"] == "delete") {
    deleteBookHandler($bookController);
}
