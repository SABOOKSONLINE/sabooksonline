<?php
// ======================
// ONIX Importer (Debug Mode)
// ======================

// 1️⃣ Enable error reporting and live output
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_implicit_flush(true);
header('Content-Type: text/html; charset=utf-8');

// 2️⃣ Include DB connection
require_once __DIR__ . "/../../database/connection.php";

// 3️⃣ Folder paths
$onixFolder  = __DIR__ . "/../../onix_files/WitsUP/9781776147472 DONE/";
$coverFolder = __DIR__ . "/../../cms-data/book-covers/";
$pdfFolder   = __DIR__ . "/../../cms-data/book-pdfs/";

// Ensure target folders exist
if (!is_dir($coverFolder)) mkdir($coverFolder, 0777, true);
if (!is_dir($pdfFolder)) mkdir($pdfFolder, 0777, true);

// 4️⃣ Load XML file
$xmlFile = $onixFolder . "9781776147472.xml";

echo "<h3>Loading ONIX file: $xmlFile</h3>";

if (!file_exists($xmlFile)) {
    die("❌ ONIX file not found at: $xmlFile");
}

$xml = simplexml_load_file($xmlFile);
if (!$xml) {
    die("❌ Failed to load or parse XML file!");
}

// 5️⃣ Detect namespaces
$namespaces = $xml->getDocNamespaces(true);
$ns = isset($namespaces['']) ? $namespaces[''] : null;
echo "<p>Detected namespace: <b>" . ($ns ?: "None") . "</b></p>";

// Register and extract <product> nodes
if ($ns) {
    $xml->registerXPathNamespace('onix', $ns);
    $products = $xml->xpath('//onix:product');
} else {
    $products = $xml->xpath('//product');
}

if (!$products || count($products) === 0) {
    die("⚠️ No <product> elements found. Check your ONIX file format.");
}

echo "<h4>Found " . count($products) . " product(s) in the ONIX file.</h4><hr>";

// 🔹 Helper function: safely traverse XML path
function getXmlValue($node, array $path) {
    foreach ($path as $child) {
        if (isset($node->$child)) {
            $node = $node->$child;
        } else {
            return ''; // Return empty string if any node in path is missing
        }
    }
    return (string)$node;
}

// 6️⃣ Loop through products
$totalImported = 0;

foreach ($products as $i => $product) {
    echo "<h4>📘 Product #" . ($i + 1) . "</h4>";

    // Extract metadata using dynamic paths
    $isbn         = (string)$product->a001;
    $publisher    = (string)$product->a197;

    $title        = getXmlValue($product, ['descriptivedetail', 'titledetail', 'titleelement', 'b203']);
    $author       = getXmlValue($product, ['descriptivedetail', 'contributer', 'b036']);
    $category     = getXmlValue($product, ['descriptivedetail', 'subject', 'b070']);
    $description  = getXmlValue($product, ['collateraldetail', 'textcontent', 'd203']);
    $cover        = getXmlValue($product, ['collateraldetail', 'supportingresource', 'resourceversion', 'resourceversionfeature', 'x435']);
    $price        = getXmlValue($product, ['productsupply', 'supplydetail', 'price', 'j151']);
    $currency     = getXmlValue($product, ['productsupply', 'supplydetail', 'price', 'j152']);
    $publish_date = (string)$product->b067;
    $language     = (string)$product->b211;

    // Debug output — print all extracted variables
    echo "<strong>ISBN:</strong> $isbn<br>";
    echo "<strong>Publisher:</strong> $publisher<br>";
    echo "<strong>Title:</strong> $title<br>";
    echo "<strong>Author:</strong> $author<br>";
    echo "<strong>Category:</strong> $category<br>";
    echo "<strong>Description:</strong> $description<br>";
    echo "<strong>Cover path:</strong> $cover<br>";
    echo "<strong>Price:</strong> $price<br>";
    echo "<strong>Currency:</strong> $currency<br>";
    echo "<strong>Language:</strong> $language<br>";
    echo "<strong>Publish Date:</strong> $publish_date<br>";

    // 7️⃣ File handling (cover & PDF from XML tags)
    $coverName = basename((string)$product->b384);
    $fileName  = basename((string)$product->b125);

    echo "<strong>Cover file:</strong> $coverName<br>";
    echo "<strong>Ebook file:</strong> $fileName<br>";

    $coverUrl = null;
    $fileUrl  = null;

    // Copy cover
    if ($coverName) {
        $coverSource = $onixFolder . $coverName;
        $coverTarget = $coverFolder . $coverName;

        if (file_exists($coverSource)) {
            if (copy($coverSource, $coverTarget)) {
                $cover = $coverFolder . $coverName;
                echo "✅ Copied cover to: $coverTarget<br>";
            } else {
                echo "⚠️ Failed to copy cover: $coverName<br>";
            }
        } else {
            echo "❌ Cover not found: $coverSource<br>";
        }
    }

    // Copy PDF
    if ($fileName) {
        $pdfSource = $onixFolder . $fileName;
        $pdfTarget = $pdfFolder . $fileName;

        if (file_exists($pdfSource)) {
            if (copy($pdfSource, $pdfTarget)) {
                $fileUrl = $pdfFolder . $fileName;
                echo "✅ Copied ebook to: $pdfTarget<br>";
            } else {
                echo "⚠️ Failed to copy ebook: $fileName<br>";
            }
        } else {
            echo "❌ Ebook not found: $pdfSource<br>";
        }
    }

    // 8️⃣ Insert into DB
    if (!empty($title)) { // Ensure title exists
        $stmt = $conn->prepare("
            INSERT INTO posts (isbn, title, authors, languages, description, dateposted, cover, pdfurl, category, retailprice)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt) {
            $stmt->bind_param(
                "ssssssssss",
                $isbn, $title, $author, $language, $description, $publish_date, $coverUrl, $fileUrl, $category, $price
            );

            if ($stmt->execute()) {
                echo "✅ Inserted into database successfully.<br>";
                $totalImported++;
            } else {
                echo "❌ DB Insert Error: " . $stmt->error . "<br>";
            }
            $stmt->close();
        } else {
            echo "❌ Prepare failed: " . $conn->error . "<br>";
        }
    } else {
        echo "⚠️ Skipped — missing title.<br>";
    }

    echo "<hr>";
}

$conn->close();

echo "<h3>✅ Import process complete!</h3>";
echo "<p>Total imported: <b>$totalImported</b> product(s).</p>";
?>
