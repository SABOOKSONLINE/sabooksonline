<?php
require_once __DIR__ . '/../config/connection.php';
include_once 'models/EventModel.php';

class EventController {
    private $model;

    public function __construct($conn) {
        $this->model = new EventModel($conn);
    }

    // Load and render filtered event cards
    public function showEvents() {
        $selectedServices = $_GET['service'] ?? [];
        $selectedProvinces = $_GET['province'] ?? [];

        $result = $this->model->getFilteredEvents($selectedServices, $selectedProvinces);
        $userDetails = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $username = $row['TITLE'];
                $address = $row['VENUE'];
                $time = $row['EVENTTIME'];
                $date = $row['EVENTDATE'];
                $id = $row['CONTENTID'];
                $logo = $row['COVER'];

                if (!isset($userDetails[$username])) {
                    $userDetails[$username] = [
                        'address' => $address,
                        'id' => $id,
                        'logo' => $logo,
                        'time' => $time,
                        'date' => $date
                    ];
                }
            }

            $result->free();
        }

        include 'views/eventCardsView.php';
    }
}

$controller = new EventController($conn);
$controller->showEvents();
$conn->close();
