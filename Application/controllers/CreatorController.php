<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CreatorController
{

    private $creatorModel;

    public function __construct($conn)
    {
        $this->creatorModel = new CreatorModel($conn);
    }

    public function renderCreatorView()
    {

        $contentId = $_GET['q'] ?? null;

        if (!$contentId) {
            header("Location: /404");
            exit;
        }

        $contentId = htmlspecialchars(trim($contentId));

        $creator = $this->creatorModel->getCreatorByContentId($contentId);

        if ($creator) {
            include __DIR__ . '/../views/layout/creatorView.php';
        } else {
            echo "Creator not found.";
        }
    }
}
