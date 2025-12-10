<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

$bookController = new BookListingController($conn);

// echo $hcPrice = htmlspecialchars($_POST["hc_price"]);
// echo $hcDiscountPercent = htmlspecialchars($_POST["hc_discount_percent"]);
// echo $hcCountry = htmlspecialchars($_POST["hc_country"]);
// echo $hcPages = htmlspecialchars($_POST["hc_pages"]);
// echo $hcWeight = htmlspecialchars($_POST["hc_weight_kg"]);
// echo $hcHeight = htmlspecialchars($_POST["hc_height_cm"]);
// echo $hcwidth = htmlspecialchars($_POST["hc_width_cm"]);
// echo $hcDate = htmlspecialchars($_POST["hc_release_date"]);
// echo $hcContributors = htmlspecialchars($_POST["hc_contributors"]);
// echo $hcStockCount = htmlspecialchars($_POST["hc_stock_count"]);
// die;

function formDataArray()
{
    /* ========= BASIC BOOK DATA ========= */
    $bookId   = htmlspecialchars($_POST["book_id"]);
    $userId   = htmlspecialchars($_POST["user_id"]);
    $title    = $_POST["book_title"];
    $price    = htmlspecialchars($_POST["book_price"] ?? '0');
    $Eprice   = htmlspecialchars($_POST["Ebook_price"] ?? '0');
    $Aprice   = htmlspecialchars($_POST["Abook_price"] ?? '0');

    /* ========= HARDCOPY DATA ========= */
    $hcPrice            = htmlspecialchars($_POST["hc_price"] ?? 0);
    $hcDiscountPercent  = htmlspecialchars($_POST["hc_discount_percent"] ?? 0);
    $hcCountry          = htmlspecialchars($_POST["hc_country"] ?? '');
    $hcPages            = htmlspecialchars($_POST["hc_pages"] ?? 0);
    $hcWeight           = htmlspecialchars($_POST["hc_weight_kg"] ?? 0);
    $hcHeight           = htmlspecialchars($_POST["hc_height_cm"] ?? 0);
    $hcWidth            = htmlspecialchars($_POST["hc_width_cm"] ?? 0);
    $hcDate             = htmlspecialchars($_POST["hc_release_date"] ?? null);
    $hcContributors     = htmlspecialchars($_POST["hc_contributors"] ?? '');
    $hcStockCount       = htmlspecialchars($_POST["hc_stock_count"] ?? 0);

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

    /* ===========================================================
        DEBUG CHECK
    ============================================================ */
    echo "<pre>";
    foreach (
        [
            'userid',
            'title',
            'cover',
            'category',
            'authors',
            'description',
            'isbn',
            'languages',
            'status',
            'stock',
            'type',
            'dateposted'
        ] as $key
    ) {
        if (empty($bookData[$key])) {
            echo "❌ '$key' is empty or missing.\n";
        } else {
            echo "✅ '$key' = " . $bookData[$key] . "\n";
        }
    }
    echo "</pre>";

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
        die("Validation failed: Missing required fields.");
    }

    try {
        /* ===========================================================
            1. INSERT MAIN BOOK
        ============================================================ */
        $bookId = $bookController->insertBookData($bookData);
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
            3. REDIRECT
        ============================================================ */
        header("Location: /dashboards/listings?status=success");
        exit;
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}



function updateBookHandler($bookController)
{
    try {
        $bookData = formDataArray();

        if (empty($bookData['bookId'])) {
            throw new Exception("Missing bookId in update request.");
        }

        $bookId = $bookData['bookId'];
        $contentId = htmlspecialchars(trim($_GET["id"] ?? ''));

        if (!$contentId) {
            throw new Exception("Invalid content ID.");
        }

        // Clear cache
        $cacheDir = __DIR__ . '/../../Application/cache/';

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0775, true);
        }

        $cacheFile = $cacheDir . 'book_' . $contentId . '.html';

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        // Update Main Book
        $bookController->updateBookData($bookId, $bookData);

        /*
        |--------------------------------------------------------------------------
        | Hardcopy Insert or Update
        |--------------------------------------------------------------------------
        | If the hardcopy fields exist AND contain values,
        | then update or insert depending on DB presence.
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

            // Attach book_id
            $hardcopyData = ['book_id' => $bookId];

            // Only include present fields
            foreach ($hardcopyFields as $field) {
                $hardcopyData[$field] = $bookData[$field] ?? null;
            }

            // Check if this book already has hardcopy details
            $existing = $bookController->getHardcopyByBookId($bookId);

            if ($existing) {
                // Update existing
                $bookController->updateHardcopy($hardcopyData);
            } else {
                // Insert new
                $bookController->insertHardcopy($hardcopyData);
            }
        }

        header("Location: /dashboards/listings?update=success");
        exit;
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
