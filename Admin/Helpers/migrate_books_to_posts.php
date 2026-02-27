<?php
/**
 * One-off migration script:
 *  - Reads from legacy `books` table
 *  - Inserts equivalent rows into `posts` (as Book + Hardcopy)
 *  - Creates matching `book_hardcopy` rows
 *  - All new posts are owned by the specified USERID (publisher key)
 *
 * SAFETY:
 *  - Skips any book whose ISBN already exists in `posts`
 *  - Uses STATUS = 'draft' so migrated books are NOT visible until you activate them
 *
 * USAGE:
 *  - Make sure this file is NOT publicly accessible forever. Run it once, then delete or protect it.
 *  - From CLI (recommended):
 *      php Admin/Helpers/migrate_books_to_posts.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../Dashboard/database/connection.php';

// All migrated posts will be linked to this USERID (publisher key)
$TARGET_USER_KEY = 'c68c051b6bd9e84.71932950';

function logLine(string $message): void
{
    echo $message . PHP_EOL;
}

logLine("=== Starting books -> posts + book_hardcopy migration ===");

// 1) Fetch all rows from legacy `books` table
$sqlBooks = "SELECT * FROM books ORDER BY pub_date DESC, title ASC";

if (!$resultBooks = $conn->query($sqlBooks)) {
    logLine("ERROR: Failed to query books table: " . $conn->error);
    exit(1);
}

$totalBooks   = $resultBooks->num_rows;
$migrated     = 0;
$skippedIsbn  = 0;
$errors       = 0;

logLine("Found {$totalBooks} rows in `books`.");

// Prepared statements for re-use

// Check if ISBN already exists in posts
$stmtCheckIsbn = $conn->prepare("SELECT ID FROM posts WHERE ISBN = ? LIMIT 1");
if (!$stmtCheckIsbn) {
    logLine("ERROR: Failed to prepare ISBN check: " . $conn->error);
    exit(1);
}

// Insert into posts (CONTENTID via UUID())
$sqlInsertPost = "INSERT INTO posts (
    TITLE, CATEGORY, WEBSITE, DESCRIPTION, COVER, CONTENTID, USERID, TYPE, DATEPOSTED,
    STATUS, ISBN, RETAILPRICE, KEYWORDS, PUBLISHER, LANGUAGES, STOCK, AUTHORS,
    PDFURL, EBOOKPRICE, ABOOKPRICE
) VALUES (?, ?, ?, ?, ?, UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmtInsertPost = $conn->prepare($sqlInsertPost);
if (!$stmtInsertPost) {
    logLine("ERROR: Failed to prepare posts insert: " . $conn->error);
    exit(1);
}

// Insert into book_hardcopy
$sqlInsertHardcopy = "INSERT INTO book_hardcopy (
    book_id,
    hc_price,
    hc_discount_percent,
    hc_country,
    hc_pages,
    hc_weight_kg,
    hc_height_cm,
    hc_width_cm,
    hc_release_date,
    hc_contributors,
    hc_stock_count
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmtInsertHardcopy = $conn->prepare($sqlInsertHardcopy);
if (!$stmtInsertHardcopy) {
    logLine("ERROR: Failed to prepare book_hardcopy insert: " . $conn->error);
    exit(1);
}

while ($book = $resultBooks->fetch_assoc()) {
    $isbn = trim((string)($book['isbn'] ?? ''));

    if ($isbn === '') {
        logLine("Skipping book with empty ISBN (ID: " . ($book['id'] ?? 'N/A') . ")");
        $skippedIsbn++;
        continue;
    }

    // 2) Skip if ISBN already exists in posts
    $stmtCheckIsbn->bind_param('s', $isbn);
    if (!$stmtCheckIsbn->execute()) {
        logLine("ERROR: ISBN check failed for {$isbn}: " . $stmtCheckIsbn->error);
        $errors++;
        continue;
    }

    $resCheck = $stmtCheckIsbn->get_result();
    if ($resCheck && $resCheck->num_rows > 0) {
        logLine("Skipping ISBN {$isbn} - already exists in posts.");
        $skippedIsbn++;
        continue;
    }

    // 3) Build fields for posts
    $title       = (string)($book['title'] ?? '');
    $authors     = (string)($book['author'] ?? '');
    $publisher   = (string)($book['publisher'] ?? '');
    $price       = (float)($book['price'] ?? 0);
    $quantity    = (int)($book['quantity'] ?? 0);
    $stockStatus = strtolower((string)($book['stock_status'] ?? ''));
    $itemStatus  = (string)($book['item_status'] ?? '');
    $pubDateRaw  = (string)($book['pub_date'] ?? '');
    $weightKg    = isset($book['weight']) ? (float)$book['weight'] : null;

    // CATEGORY = empty string (per requirements)
    $category    = '';

    // WEBSITE, DESCRIPTION, KEYWORDS empty for now
    $website     = '';
    $description = '';
    $keywords    = '';

    // COVER empty (no cover in books table)
    $cover       = '';

    // USERID = given publisher key
    $userId      = $TARGET_USER_KEY;

    // TYPE always "Book"
    $type        = 'Book';

    // STATUS not active for now, use 'draft'
    $status      = 'draft';

    // LANGUAGES default "English"
    $languages   = 'English';

    // STOCK derived from stock_status
    // For publishers_books we saw C vs others, for books_table we saw "in stock"/other.
    if ($stockStatus === 'c' || $stockStatus === 'in stock') {
        $stock = 'instock';
    } else {
        $stock = 'outofstock';
    }

    // DATEPOSTED from pub_date if valid, else today
    $datePosted = date('Y-m-d');
    if (!empty($pubDateRaw) && $pubDateRaw !== '0000-00-00' && $pubDateRaw !== '0001-01-01') {
        $datePosted = $pubDateRaw;
    }

    // No digital prices or PDFs for these migrated books
    $pdfUrl     = '';
    $ebookPrice = 0.0;
    $aBookPrice = 0.0;

    // Bind and insert into posts
    if (
        !$stmtInsertPost->bind_param(
            'sssssssssssssssssss',
            $title,
            $category,
            $website,
            $description,
            $cover,
            $userId,
            $type,
            $datePosted,
            $status,
            $isbn,
            $price,
            $keywords,
            $publisher,
            $languages,
            $stock,
            $authors,
            $pdfUrl,
            $ebookPrice,
            $aBookPrice
        )
    ) {
        logLine("ERROR: bind_param failed for ISBN {$isbn}: " . $stmtInsertPost->error);
        $errors++;
        continue;
    }

    if (!$stmtInsertPost->execute()) {
        logLine("ERROR: insert into posts failed for ISBN {$isbn}: " . $stmtInsertPost->error);
        $errors++;
        continue;
    }

    $postId = (int)$conn->insert_id;

    // 4) Insert into book_hardcopy using weight, quantity, price
    $hcPrice            = $price;
    $hcDiscountPercent  = 0.0;
    $hcCountry          = 'ZA';
    $hcPages            = null;
    $hcWeightKg         = $weightKg;
    $hcHeightCm         = null;
    $hcWidthCm          = null;
    $hcReleaseDate      = $datePosted;
    $hcContributors     = $authors;
    $hcStockCount       = $quantity;

    if (
        !$stmtInsertHardcopy->bind_param(
            'idissdddssi',
            $postId,
            $hcPrice,
            $hcDiscountPercent,
            $hcCountry,
            $hcPages,
            $hcWeightKg,
            $hcHeightCm,
            $hcWidthCm,
            $hcReleaseDate,
            $hcContributors,
            $hcStockCount
        )
    ) {
        logLine("ERROR: bind_param failed for book_hardcopy (ISBN {$isbn}): " . $stmtInsertHardcopy->error);
        $errors++;
        continue;
    }

    if (!$stmtInsertHardcopy->execute()) {
        logLine("ERROR: insert into book_hardcopy failed for ISBN {$isbn}: " . $stmtInsertHardcopy->error);
        $errors++;
        continue;
    }

    $migrated++;
    logLine("Migrated ISBN {$isbn} -> posts.ID {$postId}");
}

$stmtCheckIsbn->close();
$stmtInsertPost->close();
$stmtInsertHardcopy->close();
$resultBooks->free();

logLine("=== Migration complete ===");
logLine("Total books:      {$totalBooks}");
logLine("Migrated:         {$migrated}");
logLine("Skipped (ISBN):   {$skippedIsbn}");
logLine("Errors:           {$errors}");

exit(0);

