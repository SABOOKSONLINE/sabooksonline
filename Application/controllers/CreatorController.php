<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function creatorViewRender($conn)
{
    // Sanitize the contentId from the query parameter
    $contentId = isset($_GET['q']) ? htmlspecialchars(trim($_GET['q'])) : null;

    if (!$contentId) {
        echo "Creator ID is required.";
        return;
    }

    // Instantiate the creator model
    $creatorModel = new CreatorModel($conn);
    
    // Fetch the creator data
    $creator = $creatorModel->getCreatorByContentId($contentId);

    if ($creator) {
        // If creator found, include the view
        include __DIR__ . '/../views/creatorpage.php';
    } else {
        // If creator not found, return a message
        echo "Creator not found.";
    }
}
?>
