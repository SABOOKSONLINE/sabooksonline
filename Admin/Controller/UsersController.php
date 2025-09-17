<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/UserModel.php";
require_once __DIR__ . "/../Model/BookModel.php";

class UsersController extends Controller
{
    private UserModel $userModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->userModel = new UserModel($conn);
    }

    public function users()
    {
        $users = $this->userModel->getAllUsersDetails();
        $countUsers = $this->userModel->countUsers();
        $countSubscribedUsers = $this->userModel->countSubscribers();
        $grossSubscriptionIncome = $this->userModel->grossSubscriptionIncome();

        $this->render("users", [
            "countAll" => $countUsers,
            "subscribed" => $countSubscribedUsers,
            "subscribtion_gross" => $grossSubscriptionIncome,
            "details" => $users
        ]);
    }
}
