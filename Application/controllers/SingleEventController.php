<?php
// controllers/EventController.php

session_start();
require_once __DIR__ . '/../config/connection.php';
include_once 'models/EventModel.php';

class SingleEventController
{
    private $eventModel;

    public function __construct($conn)
    {
        $this->eventModel = new EventModel($conn);
    }

    public function showEvent()
    {
        if (!isset($_GET['event'])) {
            echo 'Not found';
            return null;
        }

        $contentId = str_replace('-', ' ', $_GET['event']);
        $event = $this->eventModel->getEventByContentId($contentId);

        if (!$event) {
            echo 'Event not found';
            return null;
        }

        // Assign and format values here
        $eventData = [
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

        return $eventData;
    }
}

// Instantiate and call controller method
$controller = new SingleEventController($conn);
$eventData = $controller->showEvent();

// Optional: Pass $eventData into a view file if needed
?>
