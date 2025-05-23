<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/ServicesModel.php";
require_once __DIR__ . "/../controllers/ServicesController.php";

$serviceController = new ServicesController($conn);

function formDataArray()
{
    $userId = htmlspecialchars(($_POST["user_id"]));
    $service = htmlspecialchars(($_POST["service"]));
    $serviceStatus = htmlspecialchars(($_POST["service_status"]));
    $minimum = htmlspecialchars(($_POST["minimum_amount"]));
    $maximum = htmlspecialchars(($_POST["maximum_amount"]));
    $created = date('d-m-y');
    $modified = date('d-m-y');

    $serviceData = [
        'service' => $service,
        'userid' => $userId,
        'status' => $serviceStatus,
        'created' => $created,
        'modified' => $modified,
        'minimum' => $minimum,
        'maximum' => $maximum
    ];

    return $serviceData;
}

function insertServiceHandler($serviceController)
{
    $serviceData = formDataArray();

    if (
        empty($serviceData['userid'])
        || empty($serviceData['service'])
        || empty($serviceData['status'])
        || empty($serviceData['minimum'])
        || empty($serviceData['maximum'])
    ) {
        header("Location: /dashboards/services?status=fail");
        exit;
    }

    try {
        $serviceController->insertServiceData($serviceData);
        header("Location: /dashboards/services?status=success");
    } catch (Exception $e) {
        header("Location: /dashboards/services?status=fail");
        exit;
    }
}

function updateService($serviceController)
{
    try {
        $serviceData = formDataArray();
        $serviceId = $_GET["id"];

        $serviceController->updateServiceData($serviceId, $serviceData);
        header("Location: /dashboards/services?update=success");
    } catch (Exception $e) {
        header("Location: /dashboards/services?update=fail");
        exit;
    }
}

function deleteService($serviceController)
{
    try {
        $serviceId = $_GET["id"];

        $serviceController->deleteService($serviceId);
        header("Location: /dashboards/services?delete=success");
    } catch (Exception $e) {
        header("Location: /dashboards/services?delete=fail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["action"] == "insert") {
    insertServiceHandler($serviceController);
}

if ($_GET["id"] && $_GET["action"] == "delete") {
    deleteService($serviceController);
}

if ($_GET["id"] && $_GET['action'] == "update") {
    updateService($serviceController);
}
