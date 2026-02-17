<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . "/../Config/connection.php";
require __DIR__ . "/../models/CartModel.php";
require __DIR__ . "/../controllers/CartController.php";

if (session_status() === PHP_SESSION_NONE) {
    $cookieDomain = ".sabooksonline.co.za";
    session_set_cookie_params(0, '/', $cookieDomain);
    session_start();
}

$userID = $_SESSION['ADMIN_ID'] ?? null;
if (!$userID) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

$controller = new CartController($conn);
$cartItems = $controller->getCartCheckoutItems($userID);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

// --- Build payload ---
$collection_address = [
    "type" => "business",
    "company" => "www.sabooksonline.co.za",
    "street_address" => "130 Wentworth road",
    "local_area" => "Jackal Creek Golf Estate",
    "city" => "Roodepoort",
    "code" => "2169",
    "zone" => "Gauteng",
    "country" => "ZA"
];

$collection_contact = [
    "name" => "Sibusiso Manqa",
    "mobile_number" => "+27678523593",
    "email" => "manqa@sabooksonline.co.za"
];

$delivery_address = [
    "company" => $data['delivery_address']['company'] ?? "",
    "street_address" => $data['delivery_address']['street_address'] ?? "",
    "type" => $data['delivery_address']['type'] ?? "residential",
    "local_area" => $data['delivery_address']['local_area'] ?? "",
    "zone" => $data['delivery_address']['zone'] ?? "",
    "country" => $data['delivery_address']['country'] ?? "",
    "code" => $data['delivery_address']['code'] ?? ""
];

$delivery_contact = [
    "name" => $data['delivery_contact']['name'] ?? "",
    "mobile_number" => $data['delivery_contact']['mobile_number'] ?? "",
    "email" => $data['delivery_contact']['email'] ?? ""
];

$parcels = [];
if (!empty($data['items'])) {
    foreach ($data['items'] as $item) {
        $parcels[] = [
            "submitted_length_cm" => $item['hc_length_cm'] ?? 10.0,
            "submitted_width_cm"  => $item['hc_width_cm'] ?? 10.0,
            "submitted_height_cm" => $item['hc_height_cm'] ?? 10.0,
            "submitted_weight_kg" => $item['hc_weight_kg'] ?? 1.0
        ];
    }
}

$checkout_payload = [
    "collection_address" => $collection_address,
    "collection_contact" => $collection_contact,
    "delivery_address" => $delivery_address,
    "delivery_contact" => $delivery_contact,
    "parcels" => $parcels,
    "opt_in_rates" => [],
    "opt_in_time_based_rates" => [76],
    "declared_value" => 0.0,
    "collection_min_date" => date('Y-m-d') . "T00:00:00.000Z",
    "collection_after" => "08:00",
    "collection_before" => "16:00",
    "delivery_min_date" => date('Y-m-d') . "T00:00:00.000Z",
    "delivery_after" => "10:00",
    "delivery_before" => "17:00",
    "service_level_code" => "ECO",
    "notes" => "This is a test order for sandbox environment"
];

// --- cURL request to Courier Guy rates ---
$apiUrl = "https://api.portal.thecourierguy.co.za/v2/rates";
$apiKey = "bb8b36e0b0eb41aa91c292674aeaf503"; // <-- keep secret

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($checkout_payload));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(["success" => false, "error" => curl_error($ch)]);
} elseif ($httpCode >= 400) {
    echo json_encode(["success" => false, "error" => "API returned HTTP $httpCode", "raw" => $response]);
} else {
    $rates = json_decode($response, true);
    echo json_encode(["success" => true, "rates" => $rates]);
}

curl_close($ch);
