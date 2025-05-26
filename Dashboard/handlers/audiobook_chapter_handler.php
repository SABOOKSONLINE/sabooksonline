<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

$audiobookController = new BookListingController($conn);

function formAudiobookChapterDataArray()
{
    $chapter_id = htmlspecialchars($_POST['chapter_id'] ?? '');
    $audiobook_id = htmlspecialchars($_POST['audiobook_id'] ?? '');
    $chapter_number = htmlspecialchars($_POST['chapter_number'] ?? '');
    $chapter_title = htmlspecialchars($_POST['chapter_title'] ?? '');
    $created = date('Y-m-d H:i:s');

    // Handle file upload
    if (isset($_FILES['audio_url']) && $_FILES['audio_url']['error'] === UPLOAD_ERR_OK) {
        $fileInfo = $_FILES['audio_url'];

        // Check MIME type and file extension
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileInfo['tmp_name']);
        finfo_close($finfo);

        $extension = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));

        if ($mimeType !== 'audio/mpeg' || $extension !== 'mp3') {
            throw new Exception("Only MP3 files are allowed.");
        }

        // Sanitize and create a unique filename
        $baseName = preg_replace("/[^a-zA-Z0-9-_\.]/", "_", pathinfo($fileInfo['name'], PATHINFO_FILENAME));
        $uniqueName = $baseName . '_' . uniqid() . '.mp3';

        // Define upload path
        $uploadDir = __DIR__ . "/../../cms-data/audiobooks/";
        $uploadPath = $uploadDir . $uniqueName;

        // Ensure directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move file to target location
        if (!move_uploaded_file($fileInfo['tmp_name'], $uploadPath)) {
            throw new Exception("Failed to move uploaded file.");
        }

        $audioFileName = $uniqueName;
    } else {
        $audioFileName = htmlspecialchars($_POST['audio_url']); // No file uploaded or an error occurred
    }


    return [
        'chapter_id'     => $chapter_id,
        'audiobook_id'   => $audiobook_id,
        'chapter_number' => $chapter_number,
        'chapter_title'  => $chapter_title,
        'audio_url'      => $audioFileName,
        'created'        => $created
    ];
}

function insertAudiobookChapterHandler($audiobookController)
{
    $data = formAudiobookChapterDataArray();

    if (empty($data['audiobook_id']) || empty($data['chapter_number']) || empty($data['chapter_title']) || empty($data['audio_url'])) {
        die("Validation failed: Missing required fields.");
    }

    try {
        $audiobookController->insertAudiobookChapter($data);
        header("Location: /dashboards/listings/updateAudio/{$data['audiobook_id']}?status=success");
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

function updateAudiobookChapterHandler($audiobookController)
{
    $data = formAudiobookChapterDataArray();

    if (empty($data['audiobook_id']) || empty($data['chapter_number']) || empty($data['chapter_title']) || empty($data['audio_url'])) {
        die("Validation failed: Missing required fields.");
    }

    try {
        $audiobookController->updateAudiobookChapter($data['chapter_id'], $data);
        header("Location: /dashboards/listings/updateAudio/{$data['audiobook_id']}?update=success");
    } catch (Exception $e) {
        error_log("Update failed: " . $e->getMessage());
        header("Location: /dashboards/listings?delete=fail");
        exit;
    }
}

function deleteAudiobookChapterHandler($audiobookController)
{
    $data = formAudiobookChapterDataArray();
    $chapterId = $_GET["id"] ?? '';

    if (!$chapterId) {
        die("Invalid chapter ID.");
    }

    try {
        $audiobookController->deleteAudiobookChapter($chapterId);
        header("Location: /dashboards/listings/updateAudio/{$data['audiobook_id']}?update=success");
    } catch (Exception $e) {
        error_log("Delete failed: " . $e->getMessage());
        header("Location: /dashboards/listings?delete=fail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET["action"]) && $_GET["action"] == "updateAudioChapter") {
        updateAudiobookChapterHandler($audiobookController);
    } else {
        insertAudiobookChapterHandler($audiobookController);
    }
}

if ($_GET["id"] && $_GET['action'] == "deleteAudioChapter") {
    deleteAudiobookChapterHandler($audiobookController);
}
