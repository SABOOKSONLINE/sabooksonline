<?php
// Database config
require_once __DIR__ . "/../../database/connection.php";


// Folder paths
$onixFolder = __DIR__ . "/../../onix_files/WitsUP/9781776147472 DONE/";
$coverFolder = __DIR__ . "/../../cms-data/book-covers/";
$pdfFolder = __DIR__ . "/../../cms-data/book-pdfs/"; // fixed typo (was book-pfds)

// Ensure target folders exist
if (!is_dir($coverFolder)) mkdir($coverFolder, 0777, true);
if (!is_dir($pdfFolder)) mkdir($pdfFolder, 0777, true);

// Load ONIX XML
$xmlFile = $onixFolder . "onix_upload.xml";
$xml = simplexml_load_file($xmlFile, null, 0, 'http://ns.editeur.org/onix/3.0/short');

if (!$xml) {
    die("❌ Failed to load ONIX XML.");
}

foreach ($xml->product as $product) {
    $isbn = (string)$product->a001;
    $title = (string)$product->b203;
    $author = (string)$product->b014;
    $language = (string)$product->b211;
    $description = (string)$product->b244;
    $publish_date = (string)$product->b067;

    // File names (you can adjust depending on your ONIX structure)
    $coverName = basename((string)$product->b384); // Cover image name (e.g., cover123.jpg)
    $fileName = basename((string)$product->b125);  // Ebook file name (e.g., book123.pdf)

    // Default URLs (if not found)
    $coverUrl = null;
    $fileUrl = null;

    // Handle cover image
    if ($coverName) {
        $coverSource = $onixFolder . $coverName;
        $coverTarget = $coverFolder . $coverName;

        if (file_exists($coverSource)) {
            if (copy($coverSource, $coverTarget)) {
                $coverUrl = $coverFolder . $coverName;
                echo "📕 Copied cover: $coverName<br>";
            } else {
                echo "⚠️ Failed to copy cover: $coverName<br>";
            }
        } else {
            echo "❌ Cover not found: $coverSource<br>";
        }
    }

    // Handle ebook/pdf file
    if ($fileName) {
        $pdfSource = $onixFolder . $fileName;
        $pdfTarget = $pdfFolder . $fileName;

        if (file_exists($pdfSource)) {
            if (copy($pdfSource, $pdfTarget)) {
                $fileUrl = $pdfFolder . $fileName;
                echo "📘 Copied ebook: $fileName<br>";
            } else {
                echo "⚠️ Failed to copy ebook: $fileName<br>";
            }
        } else {
            echo "❌ Ebook not found: $pdfSource<br>";
        }
    }

    // Insert into DB (make sure your table 'posts' has matching columns)
    $stmt = $conn->prepare("
        INSERT INTO posts (isbn, title, authors, languages, description, date_posted, cover, pdfurl)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssssss", $isbn, $title, $author, $language, $description, $publish_date, $coverUrl, $fileUrl);

    if ($stmt->execute()) {
        echo "✅ Imported: $title<br>";
    } else {
        echo "❌ DB error inserting $title: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

$conn->close();
echo "<hr>✅ Import process complete!";
?>
