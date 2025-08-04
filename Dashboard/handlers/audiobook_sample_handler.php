<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

$audiobookController = new BookListingController($conn);

function formAudioSampleDataArray()
{
    $book_id = htmlspecialchars($_POST['book_id'] ?? '');

    $sample_file = '';

    if (isset($_FILES['sample_file']) && $_FILES['sample_file']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = $_FILES['sample_file'];

        $maxSize = 5 * 1024 * 1024;
        if ($fileInfo['size'] > $maxSize) {
            throw new Exception("File size must be less than 5MB.");
            die();
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileInfo['tmp_name']);
        finfo_close($finfo);

        $fileName = basename($fileInfo['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['mp3'];

        if ($mimeType !== 'audio/mpeg' || !in_array($fileExtension, $allowedExtensions)) {
            throw new Exception("Only MP3 files are allowed. Uploaded file is of type: $mimeType");
        }

        $newFileName = uniqid('sample_', true) . '.' . $fileExtension;
        $uploadDir = __DIR__ . '/../../cms-data/audiobooks/samples/';
        $destinationPath = $uploadDir . $newFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($fileInfo['tmp_name'], $destinationPath)) {
            throw new Exception("Failed to move uploaded file.");
        }

        $sample_file = $newFileName;
    }

    return [
        'book_id'     => $book_id,
        'sample_file' => $sample_file,
    ];
}

// echo "<pre>";
// echo $_POST['content_id'];
// echo "<br>";
// print_r(formAudioSampleDataArray());
// echo "</pre>";
// die();


function insertAudiobookSample($audiobookController)
{
    $data = formAudioSampleDataArray();

    if (empty($data['book_id']) || empty($data['sample_file'])) {
        die("Validation failed: Missing required fields.");
    }

    try {
        $audiobookController->insertAudiobookSample($data);
        header("Location: /dashboards/listings/{$_POST['content_id']}?status=success#audiobook_info");
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

function updateAudiobookSample($audiobookController)
{
    $data = formAudioSampleDataArray();
    $bookId = $_GET["id"] ?? '';

    if (!$bookId) {
        die("Invalid audiobook ID $bookId.");
    }

    try {
        $audiobookController->updateAudiobookSample($data);
        header("Location: /dashboards/listings/{$_POST['content_id']}?update=success#audiobook_info");
    } catch (Exception $e) {
        error_log("Update failed: " . $e->getMessage());
        die();
    }
}

function deleteAudioBookHandler($audiobookController)
{
    try {
        $bookId = $_GET["id"];
        $bookContentId = $_GET["content_id"];

        $audiobookController->deleteAudiobookSample($bookId);
        header("Location: /dashboards/listings/{$bookContentId}?delete=success");
    } catch (Exception $e) {
        error_log("Delete failed: " . $e->getMessage());
        die();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET["action"]) && $_GET["action"] == "update") {
        updateAudiobookSample($audiobookController);
    } else {
        insertAudiobookSample($audiobookController);
    }
}

if ($_GET["id"] && $_GET['action'] == "delete") {
    deleteAudioBookHandler($audiobookController);
}
