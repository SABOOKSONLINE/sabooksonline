<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/BookModel.php";
require_once __DIR__ . "/../Model/UserModel.php";

class UploadManagementController extends Controller
{
    private BookModel $booksModel;
    private UserModel $usersModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->booksModel = new BookModel($conn);
        $this->usersModel = new UserModel($conn);

        $this->initializeTables();
    }

    private function initializeTables(): void
    {
        try {
            $this->booksModel->createHardcopyPublishersTable();
        } catch (Exception $e) {
        }
    }

    public function index(): void
    {
        $publishers = $this->booksModel->getAllHardcopyPublishers();
        $availableUsers = $this->usersModel->getUsersForPublisherSelection();

        $this->render("upload_management", [
            "title" => "Upload Management",
            "publishers" => $publishers,
            "users" => $availableUsers
        ]);
    }

    public function addPublisher(string $userId, string $email, string $name = ''): int
    {
        return $this->booksModel->addHardcopyPublisher($userId, $email, $name);
    }

    public function removePublisher(string $email): int
    {
        return $this->booksModel->removeHardcopyPublisher($email);
    }

    public function togglePublisher(string $email, bool $canPublish): int
    {
        return $this->booksModel->toggleHardcopyPublisher($email, $canPublish);
    }
}
