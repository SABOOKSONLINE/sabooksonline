<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

$audiobookController = new BookListingController($conn);

function formAudiobookDataArray()
{
    $book_id = htmlspecialchars($_POST['book_id'] ?? '');
    $narrator = htmlspecialchars($_POST['audiobook_narrator'] ?? '');
    $release_date = htmlspecialchars($_POST['release_date'] ?? '');
    $created = date('Y-m-d H:i:s');

    return [
        'book_id'      => $book_id,
        'narrator'     => $narrator,
        'release_date' => $release_date,
        'created'      => $created
    ];
}

function insertAudiobookHandler($audiobookController)
{
    $data = formAudiobookDataArray();

    if (empty($data['narrator']) || empty($data['release_date']) || empty($data['book_id'])) {
        die("Validation failed: Missing required fields.");
    }

    try {
        $audiobookController->insertAudiobook($data);
        header("Location: /dashboards/listings/updateAudio/{$data['book_id']}?status=success");
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

function updateAudiobookHandler($audiobookController)
{
    $data = formAudiobookDataArray();
    $bookId = $_GET["q"] ?? '';

    if (!$bookId) {
        die("Invalid audiobook ID.");
    }

    try {
        $audiobookController->updateAudiobook($bookId, $data);
        header("Location: /dashboards/listings?update=success");
    } catch (Exception $e) {
        error_log("Update failed: " . $e->getMessage());
        header("Location: /dashboards/listings?update=fail&error=" . urlencode($e->getMessage()));
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET["action"]) && $_GET["action"] == "updateAudio") {
        updateAudiobookHandler($audiobookController);
    } else {
        insertAudiobookHandler($audiobookController);
    }
}
