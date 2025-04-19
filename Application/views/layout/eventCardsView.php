<?php
foreach ($events as $event) {
    $eventYear = (new DateTime($event['EVENTDATE']))->format("Y");
    $currentYear = (new DateTime())->format("Y");

    if ((int)$eventYear >= (int)$currentYear) {
        echo '
    <div class="col-md-4">
        <div class="event-card">
            <div class="event-cover">
                <a class="" href="">
                    <img class="img-fluid" src="https://sabooksonline.co.za/cms-data/event-covers/' . $event['COVER'] . '">
                </a>
            </div>
            <div class="event-card-details">
                <a class="event-card-title fw-bold" href="">
                    ' . $event['TITLE'] . '
                </a>
                <small class="text-secondary">
                    <i class="fas fa-location"></i> ' . $event['VENUE'] . '
                </small>
                <div class="py-3 event-card-date">
                    ' . $event['EVENTTIME'] . ',  ' . $event['DATEPOSTED'] . '
                </div>
            </div>
            <a href="/events/event/' . $event['CONTENTID'] . '" class="btn btn-red">Event Details</a>
        </div>
    </div>
    ';
    }
}
