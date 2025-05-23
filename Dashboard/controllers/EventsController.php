<?php
class EventsController
{
    private $eventsModel;

    public function __construct($conn)
    {
        $this->eventsModel = new EventsModel($conn);
    }

    public function renderEvents($userId)
    {
        $events = $this->eventsModel->selectEventsByUserId($userId);

        if ($events) {
            include __DIR__ . "/../views/includes/layouts/tables/events_table.php";
        }
    }

    public function renderEventByContentId($userId, $eventId)
    {
        $event = $this->eventsModel->selectEventByContentId($userId, $eventId);

        include __DIR__ . "/../views/includes/layouts/forms/event_form.php";
    }

    public function insertEventData($data)
    {
        $this->eventsModel->insertEvent($data);
    }

    public function updateEventData($id, $data)
    {
        $this->eventsModel->updateEvent($id, $data);
    }

    public function deleteEvent($id)
    {
        $this->eventsModel->deleteEvent($id);
    }
}
