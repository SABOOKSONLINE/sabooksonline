<?php
include '../includes/database_connections/sabooks.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contentId = mysqli_real_escape_string($conn, $_POST['contentid']);
    $pdfUrl = mysqli_real_escape_string($conn, $_POST['pdf_url']);

    if (empty($contentId) || empty($pdfUrl)) {
        echo "Missing content ID or PDF URL.";
        exit;
    }

    // Update the corresponding book row with the new PDF URL
    $sql = "UPDATE posts SET PDFURL = '$pdfUrl' WHERE CONTENTID = '$contentId'";

    if (mysqli_query($conn, $sql)) {
        echo "✅ PDF uploaded and saved successfully.";
    } else {
        echo "❌ Failed to update the database: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}
