<?php
// Include necessary files for database connection and Event model
require_once __DIR__ . '/../config/connection.php';
include_once 'models/EventModel.php';

// EventController class to handle event-related actions
class EventController {
    private $model;

    // Constructor to initialize the EventModel with the database connection
    public function __construct($conn) {
        $this->model = new EventModel($conn);
    }

    /**
     * Method to load and render filtered event cards
     * Filters events based on selected services and provinces, then displays the results
     */
    public function showEvents() {
        // Get selected services and provinces from query parameters (defaults to empty arrays if not set)
        $selectedServices = $_GET['service'] ?? [];
        $selectedProvinces = $_GET['province'] ?? [];

        // Call the model method to fetch filtered events based on selected criteria
        $result = $this->model->getFilteredEvents($selectedServices, $selectedProvinces);

        // Initialize an empty array to hold the user details
        $userDetails = [];

        // Check if the query result has returned events
        if ($result) {
            // Loop through the result set and organize event data by username (title)
            while ($row = $result->fetch_assoc()) {
                $username = $row['TITLE'];          // Event title (used as username)
                $address = $row['VENUE'];           // Event venue
                $time = $row['EVENTTIME'];          // Event time
                $date = $row['EVENTDATE'];          // Event date
                $id = $row['CONTENTID'];            // Event unique identifier
                $logo = $row['COVER'];              // Event cover/logo image

                // Check if the event is not already in the userDetails array (prevent duplicates)
                if (!isset($userDetails[$username])) {
                    // Store event details in the userDetails array with the username as the key
                    $userDetails[$username] = [
                        'address' => $address,
                        'id' => $id,
                        'logo' => $logo,
                        'time' => $time,
                        'date' => $date
                    ];
                }
            }

            // Free the result set to release memory
            $result->free();
        }

        // Include the view to display the event cards with the collected data
        include 'views/eventCardsView.php';
    }
}

// Instantiate the EventController and call the showEvents method to display events
$controller = new EventController($conn);
$controller->showEvents();

// Close the database connection
$conn->close();
