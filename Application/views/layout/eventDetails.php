<?php

echo '
<div class="row py-5">
    <!-- Book Cover -->
    <div class="col-md-3">
        <img src="https://sabooksonline.co.za/cms-data/event-covers/' . $event['COVER'] . '" class="img-fluid" alt="">
    </div>

    <!-- Book Info -->
    <div class="col-md-8">
        <h2 class="fw-bold text-capitalize">' . $event['TITLE'] . '</h2>
        <div class="event-card-details">
            <small class="text-secondary">
                <i class="fas fa-location"></i>
                <a href="https://www.google.com/maps/dir//' . $event['VENUE'] . '" target="blank" class="__web-inspector-hide-shortcut__">' . $event['VENUE'] . '</a>
            </small>
            <div class="py-3 event-card-date">
                ' . $event['EVENTTIME'] . ',  ' . $event['DATEPOSTED'] . '
            </div>

            ... working on it!
        </div>
    </div>
</div>

';
