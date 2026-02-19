<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../Core/Controller.php";
require_once __DIR__ . "/../Model/UserModel.php";

class BookCollectionAddressController extends Controller
{
    private UserModel $usersModel;

    public function __construct(mysqli $conn)
    {
        parent::__construct($conn);
        $this->usersModel = new UserModel($conn);
    }

    public function index(): void
    {
        $users       = $this->usersModel->getAllUsersForSelector();
        $selectedUser = [];
        $editAddress = [];

        $userId = (int) ($_GET['user_id'] ?? 0);

        if ($userId) {
            $addresses = $this->usersModel->getAddressesByUser($userId);

            if (!empty($_GET['edit'])) {
                $editAddress = $this->usersModel->getAddressById((int) $_GET['edit'], $userId);
            }

            foreach ($users as $user) {
                if ((int) $user['user_id'] === $userId) {
                    $selectedUser = $user;
                    break;
                }
            }
        } else {
            $addresses = $this->usersModel->getAllAddresses();
        }

        $this->render("book_collection_addresses", [
            "title"         => "Collection Addresses",
            "users"         => $users,
            "selected_user" => $selectedUser,
            "user_id"       => $userId,
            "addresses"     => $addresses,
            "edit_address"  => $editAddress,
        ]);
    }

    public function store(int $userId, array $data): int
    {
        return $this->usersModel->addAddress($userId, $data);
    }

    public function update(int $addressId, int $userId, array $data): int
    {
        return $this->usersModel->updateAddress($addressId, $userId, $data);
    }

    public function destroy(int $addressId, int $userId): int
    {
        return $this->usersModel->deleteAddress($addressId, $userId);
    }

    public function setDefault(int $addressId, int $userId): int
    {
        return $this->usersModel->setDefaultAddress($addressId, $userId);
    }

    public function getCourierGuyPayload(int $addressId, int $userId): array
    {
        $address = $this->usersModel->getAddressById($addressId, $userId);

        if (empty($address)) {
            throw new Exception("Address not found.");
        }

        return $this->usersModel->formatForCourierGuy($address);
    }

    public function getDefaultCourierGuyPayload(int $userId): array
    {
        $address = $this->usersModel->getDefaultAddress($userId);

        if (empty($address)) {
            throw new Exception("No default address set for this user.");
        }

        return $this->usersModel->formatForCourierGuy($address);
    }
}
