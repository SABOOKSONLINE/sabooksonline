<?php
session_start();
require_once __DIR__ . "/sessionAlerts.php";
require_once __DIR__ . "/../Core/Conn.php";

if (!isset($_GET['type'], $_GET['return'])) {
    setAlert("warning", "Invalid request parameters!");
    header("Location: /admin");
    exit;
}

require_once __DIR__ . "/../Model/UserModel.php";
require_once __DIR__ . "/../Controller/BookCollectionAddressController.php";

$controller = new BookCollectionAddressController($conn);
$type       = $_GET['type'];
$returnUrl  = $_GET['return'];

if ($type === "add-address") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userId = (int) trim($_POST['user_id'] ?? 0);

        if (!$userId) {
            setAlert("danger", "User ID is required!");
            header("Location: $returnUrl");
            exit;
        }

        $data = [
            'nickname'             => trim($_POST['nickname']             ?? ''),
            'contact_name'         => trim($_POST['contact_name']         ?? ''),
            'contact_phone'        => trim($_POST['contact_phone']        ?? ''),
            'contact_email'        => trim($_POST['contact_email']        ?? ''),
            'unit_number'          => trim($_POST['unit_number']          ?? ''),
            'complex_name'         => trim($_POST['complex_name']         ?? ''),
            'street_number'        => trim($_POST['street_number']        ?? ''),
            'street_name'          => trim($_POST['street_name']          ?? ''),
            'suburb'               => trim($_POST['suburb']               ?? ''),
            'city'                 => trim($_POST['city']                 ?? ''),
            'province'             => trim($_POST['province']             ?? ''),
            'postal_code'          => trim($_POST['postal_code']          ?? ''),
            'country_code'         => trim($_POST['country_code']         ?? 'ZA'),
            'special_instructions' => trim($_POST['special_instructions'] ?? ''),
            'is_default'           => isset($_POST['is_default']) ? 1 : 0,
        ];

        $required = ['nickname', 'contact_name', 'contact_phone', 'contact_email', 'street_number', 'street_name', 'suburb', 'city', 'province', 'postal_code'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                setAlert("danger", "Missing required field: " . ucwords(str_replace('_', ' ', $field)));
                header("Location: $returnUrl");
                exit;
            }
        }

        if ($controller->store($userId, $data)) {
            setAlert("success", "Address added successfully!");
        } else {
            setAlert("danger", "Failed to add address.");
        }
    }
} elseif ($type === "update-address") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $addressId = (int) trim($_POST['address_id'] ?? 0);
        $userId    = (int) trim($_POST['user_id']    ?? 0);

        if (!$addressId || !$userId) {
            setAlert("danger", "Address ID and User ID are required!");
            header("Location: $returnUrl");
            exit;
        }

        $data = [
            'nickname'             => trim($_POST['nickname']             ?? ''),
            'contact_name'         => trim($_POST['contact_name']         ?? ''),
            'contact_phone'        => trim($_POST['contact_phone']        ?? ''),
            'contact_email'        => trim($_POST['contact_email']        ?? ''),
            'unit_number'          => trim($_POST['unit_number']          ?? ''),
            'complex_name'         => trim($_POST['complex_name']         ?? ''),
            'street_number'        => trim($_POST['street_number']        ?? ''),
            'street_name'          => trim($_POST['street_name']          ?? ''),
            'suburb'               => trim($_POST['suburb']               ?? ''),
            'city'                 => trim($_POST['city']                 ?? ''),
            'province'             => trim($_POST['province']             ?? ''),
            'postal_code'          => trim($_POST['postal_code']          ?? ''),
            'country_code'         => trim($_POST['country_code']         ?? 'ZA'),
            'special_instructions' => trim($_POST['special_instructions'] ?? ''),
            'is_default'           => isset($_POST['is_default']) ? 1 : 0,
        ];

        $required = ['nickname', 'contact_name', 'contact_phone', 'contact_email', 'street_number', 'street_name', 'suburb', 'city', 'province', 'postal_code'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                setAlert("danger", "Missing required field: " . ucwords(str_replace('_', ' ', $field)));
                header("Location: $returnUrl");
                exit;
            }
        }

        if ($controller->update($addressId, $userId, $data)) {
            setAlert("success", "Address updated successfully!");
        } else {
            setAlert("danger", "Failed to update address. No changes were made.");
        }
    }
} elseif ($type === "delete-address") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $addressId = (int) trim($_POST['address_id'] ?? 0);
        $userId    = (int) trim($_POST['user_id']    ?? 0);

        if (!$addressId || !$userId) {
            setAlert("danger", "Address ID and User ID are required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->destroy($addressId, $userId)) {
            setAlert("success", "Address deleted successfully!");
        } else {
            setAlert("danger", "Failed to delete address. It may not exist.");
        }
    }
} elseif ($type === "set-default-address") {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $addressId = (int) trim($_POST['address_id'] ?? 0);
        $userId    = (int) trim($_POST['user_id']    ?? 0);

        if (!$addressId || !$userId) {
            setAlert("danger", "Address ID and User ID are required!");
            header("Location: $returnUrl");
            exit;
        }

        if ($controller->setDefault($addressId, $userId)) {
            setAlert("success", "Default address updated!");
        } else {
            setAlert("danger", "Failed to update default address.");
        }
    }
}

header("Location: $returnUrl");
exit;
