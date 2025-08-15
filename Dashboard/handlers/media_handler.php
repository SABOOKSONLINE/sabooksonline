<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

function magazineFormDataArray(): array
{
    $publisher_id   = clean($_POST['publisher_id'] ?? '');
    $title          = clean($_POST['title'] ?? '');
    $editor         = clean($_POST['editor'] ?? '');
    $category       = clean($_POST['category'] ?? '');
    $issn           = clean($_POST['issn'] ?? '');
    $price          = clean($_POST['price'] ?? '');
    $frequency      = clean($_POST['frequency'] ?? '');
    $language       = clean($_POST['language'] ?? '');
    $country        = clean($_POST['country'] ?? '');
    $publish_date   = clean($_POST['publish_date'] ?? '');
    $description    = clean($_POST['description'] ?? '');

    $cover = fileProcessor("/../../cms-data/magazine/covers/", "cover", ["jpg", "png", "gif"]);
    $pdf_upload = fileProcessor("/../../cms-data/magazine/pdfs/", "pdf", ["pdf"]);

    return [
        'publisher_id'  => $publisher_id,
        'title'         => $title,
        'editor'        => $editor,
        'category'      => $category,
        'issn'          => $issn,
        'price'         => $price,
        'frequency'     => $frequency,
        'language'      => $language,
        'country'       => $country,
        'publish_date'  => $publish_date,
        'description'   => $description,
        'cover'         => $cover,
        'pdf_upload'    => $pdf_upload
    ];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r(magazineFormDataArray());
    echo "</pre>";
}
