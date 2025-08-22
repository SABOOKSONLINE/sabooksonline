<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/EventModel.php";
require_once __DIR__ . "/../controllers/EventsController.php";

$eventController = new EventsController($conn);

function formDataArray()
{
    $userId = htmlspecialchars($_POST["user_id"]);
    $title = htmlspecialchars($_POST["title"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["number"]);
    $venue = htmlspecialchars($_POST["venue"]);
    $eventStartDate = htmlspecialchars($_POST["event_start_date"]);
    $eventTime = htmlspecialchars($_POST["event_time"]);
    $eventEndDate = htmlspecialchars($_POST["event_end_date"]);
    $endTime = htmlspecialchars($_POST["end_time"]);
    $description = htmlspecialchars($_POST["description"]);
    $eventType = htmlspecialchars($_POST["eventType"]);
    $attendance = htmlspecialchars($_POST["attendence"]) ?? "In-Person";
    $duration = htmlspecialchars($_POST["duration"]);
    $link = htmlspecialchars($_POST["link"]);

    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../cms-data/event-covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $cover = uniqid('', true) . basename($_FILES['cover']['name']);
        if (!move_uploaded_file($_FILES['cover']['tmp_name'], $uploadDir . basename($_FILES['cover']['name']))) {
            die("Failed to upload cover image.");
        }
    } else {
        $cover = htmlspecialchars($_POST['existing_cover']);
    }

    $created = date('Y-m-d H:i:s');
    $modified = date('Y-m-d H:i:s');

    $eventData = [
        'userid' => $userId,
        'title' => $title,
        'email' => $email,
        'phone' => $phone,
        'venue' => $venue,
        'event_start_date' => $eventStartDate,
        'event_time' => $eventTime,
        'event_end_date' => $eventEndDate,
        'end_time' => $endTime,
        'description' => $description,
        'event_type' => $eventType,
        'attendance' => $attendance,
        'duration' => $duration,
        'link' => $link,
        'cover' => $cover,
        'status' => 'Active',
        'created' => $created,
        'modified' => $modified
    ];

    return $eventData;
}

function insertEventHandler($eventController)
{
    $eventData = formDataArray();

    if (
        empty($eventData['userid']) ||
        empty($eventData['title']) ||
        empty($eventData['email']) ||
        empty($eventData['phone']) ||
        empty($eventData['venue']) ||
        empty($eventData['event_start_date']) ||
        empty($eventData['event_end_date']) ||
        empty($eventData['description']) ||
        empty($eventData['event_type']) ||
        empty($eventData['attendance']) ||
        empty($eventData['duration'])
    ) {
        die("Validation failed: Missing required fields.");
    }

    try {
        $eventController->insertEventData($eventData);
        header("Location: /dashboards/events?status=success");
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}

function updateEvent($eventController)
{
    try {
        $eventData = formDataArray();
        $eventId = $_GET["id"];

        $eventController->updateEventData($eventId, $eventData);
        header("Location: /dashboards/events?update=success");
    } catch (Exception $e) {
        header("Location: /dashboards/events?update=fail");
        exit;
    }
}

function deleteEvent($eventController)
{
    try {
        $eventId = $_GET["id"];

        $eventController->deleteEvent($eventId);
        header("Location: /dashboards/events?delete=success");
    } catch (Exception $e) {
        header("Location: /dashboards/events?delete=fail");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_GET["action"] == "insert") {
    insertEventHandler($eventController);
}

if ($_GET["id"] && $_GET["action"] == "delete") {
    deleteEvent($eventController);
}

if ($_GET["id"] && $_GET['action'] == "update") {
    updateEvent($eventController);
}
