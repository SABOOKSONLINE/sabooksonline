<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$contentId = $_GET['q'] ?? null;

function creatorViewRender($conn, $contentId)
{

    $creatorModel = new CreatorModel(connection: $conn);
    $creator = $creatorModel->getCreatorByContentId(contentId: $contentId);

    if ($contentId) {
        if ($creator) {
            include __DIR__ . '/../views/creatorpage.php';
        } else {
            echo "Creator not found.";
        }
    }
}
