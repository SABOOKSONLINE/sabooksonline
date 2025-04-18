<?php
// controllers/EventController.php

session_start();
require_once __DIR__ . '/../config/connection.php';
include_once 'models/EventModel.php';

/**
 * Class SingleEventController
 * Responsible for fetching a single event's data based on a content ID.
 */
class SingleEventController
{
    private EventModel $eventModel;

    /**
     * SingleEventController constructor.
     *
     * @param mysqli $conn Database connection
     */
    public function __construct($conn)
    {
        $this->eventModel = new EventModel($conn);
    }

    /**
     * Fetch and return a single event's details.
     *
     * @return array|null Event data array or null if not found
     */
    public function showEvent(): ?array
    {
        // If no event slug provided in URL
        if (empty($_GET['event'])) {
            echo 'Not found';
            return null;
        }

        // Convert slug to content ID (e.g., "open-day-2024" → "open day 2024")
        $contentId = str_replace('-', ' ', $_GET['event']);
        $event = $this->eventModel->getEventByContentId($contentId);

        if (!$event) {
            echo 'Event not found';
            return null;
        }

        // Map database fields to clean output
        return [
            'title'      => ucfirst($event['TITLE']),
            'id'         => $event['ID'],
            'desc'       => $event['DESCRIPTION'],
            'date'       => $event['EVENTDATE'],
            'time'       => $event['EVENTTIME'],
            'address'    => $event['VENUE'],
            'contentid'  => $event['CONTENTID'],
            'dateposted' => $event['DATEPOSTED'],
            'cover'      => $event['COVER'],
            'status'     => $event['CURRENT'],
            'userkey'    => $event['USERKEY'],
            'type'       => $event['EVENTTYPE'],
            'email'      => $event['EMAIL'],
            'number'     => $event['NUMBER'],
            'link'       => $event['LINK'],
            'latitude'   => $event['LATITUDE'],
            'longitude'  => $event['LONGITUDE']
        ];
    }
}

// Instantiate controller and fetch data
$controller = new SingleEventController($conn);
$eventData = $controller->showEvent();

// Optional: Load a view file and pass $eventData
// include 'views/events/show.php';
