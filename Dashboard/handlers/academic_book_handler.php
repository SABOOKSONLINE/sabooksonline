<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/AcademicBookModel.php";
require_once __DIR__ . "/../controllers/AcademicBookController.php";

require_once __DIR__ . "/alert_utils.php";

$academicController = new AcademicBookController($conn);

function clean($data): string
{
    return htmlspecialchars(trim($data));
}


function fileProcessor(string $path, string $formFileName, array $allowedFileTypes): ?string
{
    if (!isset($_FILES[$formFileName]) || $_FILES[$formFileName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$formFileName]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Error uploading file: " . $_FILES[$formFileName]['name']);
    }

    $uploadDir = __DIR__ . $path;
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create directory.");
        }
    }

    $fileName = $_FILES[$formFileName]['name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedFileTypes)) {
        throw new Exception("Invalid file type: $ext");
    }

    $uniqueName = bin2hex(random_bytes(16)) . '.' . $ext;
    $destination = rtrim($uploadDir, '/') . '/' . $uniqueName;

    if (!move_uploaded_file($_FILES[$formFileName]['tmp_name'], $destination)) {
        throw new Exception("Failed to move uploaded file.");
    }

    return $uniqueName;
}

function academicBookFormDataArray(bool $isUpdate = false): array
{
    $public_key = $isUpdate ? ($_POST['public_key'] ?? '') : bin2hex(random_bytes(16));

    $cover_path = null;
    if (!empty($_FILES['cover']['name'])) {
        $cover_path = fileProcessor("/../../cms-data/academic/covers/", "cover", ["jpg", "jpeg", "png"]);
    } elseif ($isUpdate) {
        $cover_path = $_POST['existing_cover'] ?? null;
    } else {
        throw new Exception("Cover image is required.");
    }

    $pdf_path = null;
    if (!empty($_FILES['pdf']['name'])) {
        $pdf_path = fileProcessor("/../../cms-data/academic/pdfs/", "pdf", ["pdf"]);
    } elseif ($isUpdate) {
        $pdf_path = $_POST['existing_pdf'] ?? null;
    }

    return [
        'publisher_id' => clean($_POST['publisher_id'] ?? ''),
        'public_key' => $public_key,
        'title' => clean($_POST['title'] ?? ''),
        'author' => clean($_POST['author'] ?? ''),
        'editor' => clean($_POST['editor'] ?? ''),
        'description' => clean($_POST['description'] ?? ''),
        'subject' => clean($_POST['subject'] ?? ''),
        'level' => clean($_POST['level'] ?? ''),
        'language' => clean($_POST['language'] ?? ''),
        'edition' => clean($_POST['edition'] ?? ''),
        'pages' => clean($_POST['pages'] ?? ''),
        'isbn' => clean($_POST['isbn'] ?? ''),
        'cover_image_path' => $cover_path,
        'publish_date' => clean($_POST['publish_date'] ?? ''),
        'ebook_price'        => (float)($_POST['ebook_price'] ?? 0.00),
        'pdf_path' => $pdf_path,
        'link' => clean($_POST['link'] ?? ''),
        'physical_book_price' => (float)($_POST['physical_book_price'] ?? 0.00),
    ];
}

// echo "<pre>";
// print_r(academicBookFormDataArray(true));
// echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_GET["action"] ?? '';
    $redirect = "/dashboards/academic/books";

    if ($action === "insert") {
        try {
            $data = academicBookFormDataArray();
            $success = $academicController->insertBook($data);

            if ($success) {
                setAlert('success', 'Academic book published successfully!');
            } else {
                setAlert('error', 'Failed to publish book. Please check your data.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    } elseif ($action === "update") {
        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                throw new Exception("Invalid academic book ID.");
            }

            $data = academicBookFormDataArray(true);
            $data['id'] = (int)$_GET['id'];

            $success = $academicController->updateBook($data);

            if ($success) {
                setAlert('success', 'Academic book updated successfully!');
            } else {
                setAlert('error', 'Failed to update book. Please check your data.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = $_GET["action"] ?? '';
    $redirect = "/dashboards/academic/books";

    if ($action === "delete") {
        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                throw new Exception("Invalid academic book ID.");
            }

            $id = (int)$_GET['id'];
            $success = $academicController->deleteBook($id);

            if ($success) {
                setAlert('success', 'Academic book deleted successfully!');
            } else {
                setAlert('error', 'Failed to delete academic book. Please try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    }
}
