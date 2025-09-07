<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/UserModel.php";
require_once __DIR__ . "/../Model/BookModel.php";

class HomeController extends Controller
{
    private UserModel $userModel;
    private BookModel $bookModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->userModel = new UserModel($conn);
        $this->bookModel = new BookModel($conn);
    }

    public function index()
    {
        $admins = $this->userModel->getAllAdmins();
        $users = $this->userModel->getAllUsers();
        $countUsers = $this->userModel->countUsers();
        $countSubscribedUsers = $this->userModel->countSubscribers();
        $grossSubscriptionIncome = $this->userModel->grossSubscriptionIncome();
        $countPublishedContent = $this->bookModel->countPublishedContent();
        $bookPurchaseIncome = $this->bookModel->bookPurchase();
        $bookPurchaseCount = $this->bookModel->countBookPurchase();
        $bookViews = $this->bookModel->countBookViews();
        $mediaViews = $this->bookModel->countMediaViews();

        $this->render("home", [
            "admins" => $admins,
            "users" => [
                "countAll" => $countUsers,
                "subscribed" => $countSubscribedUsers,
                "subscribtion_gross" => $grossSubscriptionIncome,
                "details" => $users
            ],
            "books" => [
                "countAll" => $countPublishedContent,
                "book_purchase_count" => $bookPurchaseCount,
                "book_purchase_gross" => $bookPurchaseIncome,
                "book_views" => $bookViews,
                "media_views" => $mediaViews
            ]
        ]);
    }

    public function show(string $email)
    {
        $user = $this->userModel->getAdminUser($email);
        $this->render("admin/pages/users", ["admins" => $user]);
    }
}
