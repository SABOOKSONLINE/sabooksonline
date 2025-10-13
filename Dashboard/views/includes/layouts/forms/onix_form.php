<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['onix_file'])) {
    $uploadDir = __DIR__ . '/../../uploads/onix/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmpPath = $_FILES['onix_file']['tmp_name'];
    $fileName = basename($_FILES['onix_file']['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExtension !== 'xml') {
        $error = "Only ONIX XML files are allowed.";
    } else {
        $destPath = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $success = "ONIX file uploaded successfully: " . htmlspecialchars($fileName);

            // --- PARSE ONIX XML ---
            // try {
            //     $xml = simplexml_load_file($destPath);

            //     // Loop over each product
            //     foreach ($xml->Product as $product) {
            //         // ISBN (may be under different identifiers)
            //         $isbn = null;
            //         foreach ($product->ProductIdentifier as $id) {
            //             if ((string)$id->ProductIDType == '15') { // 15 = ISBN-13
            //                 $isbn = (string)$id->IDValue;
            //             }
            //         }

            //         // Title
            //         $title = (string)$product->DescriptiveDetail->TitleDetail
            //             ->TitleElement->TitleText ?? 'Untitled';

            //         // Author
            //         $author = null;
            //         foreach ($product->DescriptiveDetail->Contributor as $contributor) {
            //             if ((string)$contributor->ContributorRole == 'A01') { // A01 = Author
            //                 $author = (string)$contributor->PersonName;
            //             }
            //         }

            //         // Publisher
            //         $publisher = (string)$product->PublishingDetail->Publisher->PublisherName ?? null;

            //         // Publication Date
            //         $pubDate = (string)$product->PublishingDetail->PublishingDate->PublishingDate;

            //         // Price
            //         $price = null;
            //         if (isset($product->ProductSupply->SupplyDetail->Price->PriceAmount)) {
            //             $price = (string)$product->ProductSupply->SupplyDetail->Price->PriceAmount;
            //         }

            //         // Insert into DB
            //         if ($isbn && $title) {
            //             $stmt = $pdo->prepare("
            //                 INSERT INTO books (isbn, title, author, publisher, pub_date, price)
            //                 VALUES (:isbn, :title, :author, :publisher, :pub_date, :price)
            //                 ON DUPLICATE KEY UPDATE 
            //                     title = VALUES(title),
            //                     author = VALUES(author),
            //                     publisher = VALUES(publisher),
            //                     pub_date = VALUES(pub_date),
            //                     price = VALUES(price)
            //             ");
            //             $stmt->execute([
            //                 ':isbn'      => $isbn,
            //                 ':title'     => $title,
            //                 ':author'    => $author,
            //                 ':publisher' => $publisher,
            //                 ':pub_date'  => $pubDate,
            //                 ':price'     => $price
            //             ]);
            //         }
            //     }

            //     $success .= "<br>ONIX data imported into database.";
            // } catch (Exception $e) {
            //     $error = "Error parsing ONIX file: " . $e->getMessage();
            // }
        } else {
            $error = "File upload failed. Please try again.";
        }
    }
}
?>

<div style="margin:40px auto;padding:20px;border:1px solid #ddd;border-radius:8px;">
  <div style="background:#f9f9f9;border-left:4px solid #007BFF;padding:15px;margin:15px 0;border-radius:4px;">
    <h3 style="margin-top:0;">Why upload ONIX files here?</h3>
    <ul style="margin:10px 0 0 20px;color:#444;line-height:1.6;">
      <li>Bulk update books instead of adding them one by one.</li>
      <li>Keep your book data consistent with publisher feeds.</li>
      <li>Make your catalog easier to share across platforms (retailers, distributors, libraries).</li>
    </ul>
  </div>

  <?php if (!empty($success)): ?>
      <p style="color:green;"><?= $success ?></p>
  <?php elseif (!empty($error)): ?>
      <p style="color:red;"><?= $error ?></p>
  <?php endif; ?>

  <!-- Warning note -->
<div style="color:#b71c1c; background:#fdecea; border:1px solid #f5c2c7; padding:10px; border-radius:4px; margin-bottom:15px;">
    ⚠️ Only administrators or developers are allowed to upload ONIX files.
</div>

<form method="POST" enctype="multipart/form-data">
    <label for="onix_file">Select ONIX XML file:</label><br><br>
    <input type="file" name="onix_file" id="onix_file" accept=".xml" required disabled><br><br>

    <button type="submit" class="btn btn-success px-4" disabled>
        Upload
    </button>
</form>
</div>