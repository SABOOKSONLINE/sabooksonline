<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/MediaModel.php";
require_once __DIR__ . "/../controllers/MediaController.php";

require_once __DIR__ . "/alert_utils.php";

$mediaController = new MediaController($conn);

function clean($data): string
{
    return htmlspecialchars(trim($data));
}

function fileProcessor(string $path, string $formFileName, array $allowedFileTypes): string
{
    if (!isset($_FILES[$formFileName]) || $_FILES[$formFileName]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload error or no file uploaded.");
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
        throw new Exception("Invalid file type.");
    }

    $uniqueName = bin2hex(random_bytes(16)) . '.' . $ext;
    $destination = rtrim($uploadDir, '/') . '/' . $uniqueName;

    if (!move_uploaded_file($_FILES[$formFileName]['tmp_name'], $destination)) {
        throw new Exception("Failed to move uploaded file.");
    }

    return $uniqueName;
}

function magazineFormDataArray(bool $isUpdate = false): array
{
    $public_key = $isUpdate ? $_POST['public_key'] : bin2hex(random_bytes(16));

    $cover_path = null;
    $pdf_path = null;

    if (!empty($_FILES['cover']['name'])) {
        $cover_path = fileProcessor("/../../cms-data/magazine/covers/", "cover", ["jpg", "jpeg", "png", "gif"]);
    } elseif ($isUpdate) {
        $cover_path = $_POST['existing_cover'] ?? null;
    } else {
        throw new Exception("Cover image is required");
    }

    if (!empty($_FILES['pdf']['name'])) {
        $pdf_path = fileProcessor("/../../cms-data/magazine/pdfs/", "pdf", ["pdf"]);
    } elseif ($isUpdate) {
        $pdf_path = $_POST['existing_pdf'] ?? null;
    } else {
        throw new Exception("PDF file is required");
    }

    return [
        'publisher_id'  => clean($_POST['publisher_id'] ?? ''),
        'title'         => clean($_POST['title'] ?? ''),
        'editor'        => clean($_POST['editor'] ?? ''),
        'category'      => clean($_POST['category'] ?? ''),
        'issn'          => clean($_POST['issn'] ?? ''),
        'price'         => (float)($_POST['price'] ?? 0.00),
        'frequency'     => clean($_POST['frequency'] ?? ''),
        'language'      => clean($_POST['language'] ?? ''),
        'country'       => clean($_POST['country'] ?? ''),
        'publish_date'  => clean($_POST['publish_date'] ?? ''),
        'description'   => clean($_POST['description'] ?? ''),
        'cover_image_path' => $cover_path,
        'pdf_path'      => $pdf_path,
        'public_key'    => $public_key
    ];
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_GET["type"] ?? '') === "magazine") {
    $action = $_GET["action"] ?? '';
    $redirect = "/dashboards/media?tab=magazines";

    if ($action === "insert") {
        try {
            $data = magazineFormDataArray();
            $success = $mediaController->insertMagazine($data);

            if ($success) {
                setAlert('success', 'Magazine inserted successfully!');
            } else {
                setAlert('error', 'Failed to insert magazine. Please check the data or try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    } elseif ($action === "update") {
        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                throw new Exception("Invalid magazine ID");
            }

            $data = magazineFormDataArray(true);
            $data['id'] = (int)$_GET['id'];

            $success = $mediaController->updateMagazine($data);

            if ($success) {
                setAlert('success', 'Magazine updated successfully!');
            } else {
                setAlert('error', 'Failed to update magazine. Please check the data or try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["type"] ?? '') === "magazine") {
    $action = $_GET["action"] ?? '';
    $redirect = "/dashboards/media?tab=magazines";

    if ($action === "delete") {
        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                throw new Exception("Invalid magazine ID");
            }

            $id = (int)$_GET['id'];
            $success = $mediaController->deleteMagazine($id);

            if ($success) {
                setAlert('success', 'Magazine deleted successfully!');
            } else {
                setAlert('error', 'Failed to delete magazine. Please try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    }
}

// NEWSPAPER 
function newspaperFormDataArray(bool $isUpdate = false): array
{
    $public_key = $isUpdate ? ($_POST['public_key'] ?? '') : bin2hex(random_bytes(16));

    $cover_path = null;
    if (!empty($_FILES['cover']['name'])) {
        $cover_path = fileProcessor("/../../cms-data/newspaper/covers/", "cover", ["jpg", "jpeg", "png", "gif"]);
    } elseif ($isUpdate) {
        $cover_path = $_POST['existing_cover'] ?? null;
    } else {
        throw new Exception("Front page image is required");
    }

    $pdf_path = null;
    if (!empty($_FILES['pdf']['name'])) {
        $pdf_path = fileProcessor("/../../cms-data/newspaper/pdfs/", "pdf", ["pdf"]);
    } elseif ($isUpdate) {
        $pdf_path = $_POST['existing_pdf'] ?? null;
    } else {
        throw new Exception("PDF file is required");
    }

    return [
        'publisher_id'  => clean($_POST['publisher_id'] ?? ''),
        'title'         => clean($_POST['title'] ?? ''),
        'description'   => clean($_POST['description'] ?? ''),
        'cover_image_path' => $cover_path,
        'pdf_path'      => $pdf_path,
        'category'      => clean($_POST['category'] ?? ''),
        'price'         => (float)($_POST['price'] ?? 0.00),
        'publish_date'  => clean($_POST['publish_date'] ?? ''),
        'public_key'    => $public_key
    ];
}

// NEWSPAPER
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_GET["type"] ?? '') === "newspaper") {
    $action = $_GET["action"] ?? '';
    $redirect = "/dashboards/media?tab=newspapers";

    if ($action === "insert") {
        try {
            $data = newspaperFormDataArray();
            $success = $mediaController->insertNewspaper($data);

            if ($success) {
                setAlert('success', 'Newspaper published successfully!');
            } else {
                setAlert('error', 'Failed to publish newspaper. Please check the data or try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    } elseif ($action === "update") {
        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                throw new Exception("Invalid newspaper ID");
            }

            $data = newspaperFormDataArray(true);
            $data['id'] = (int)$_GET['id'];

            $success = $mediaController->updateNewspaper($data);

            if ($success) {
                setAlert('success', 'Newspaper updated successfully!');
            } else {
                setAlert('error', 'Failed to update newspaper. Please check the data or try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    }
} else if ($_SERVER["REQUEST_METHOD"] === "GET" && ($_GET["type"] ?? '') === "newspaper") {
    $action = $_GET["action"] ?? '';
    $redirect = "/dashboards/media?tab=newspapers";

    if ($action === "delete") {
        try {
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                throw new Exception("Invalid newspaper ID");
            }

            $id = (int)$_GET['id'];
            $success = $mediaController->deleteNewspaper($id);

            if ($success) {
                setAlert('success', 'Newspaper deleted successfully!');
            } else {
                setAlert('error', 'Failed to delete newspaper. Please try again.');
            }
        } catch (Exception $e) {
            setAlert('error', "Error: " . $e->getMessage());
        }
        header("Location: $redirect");
        exit();
    }
}
