<?php
foreach ($providers as $provider) {
    echo '
    <div class="col-md-4">
        <div class="event-card">
            <div class="event-cover">
                <a class="" href="">
                    <img class="img-fluid" src="https://sabooksonline.co.za/cms-data/profile-images/' . $provider['ADMIN_PROFILE_IMAGE'] . '">
                </a>
            </div>
            <div class="event-card-details">
                <a class="event-card-title fw-bold" href="">
                    ' . $provider['ADMIN_NAME'] . '
                </a>
                <small class="text-secondary">
                    <i class="fas fa-location"></i> ' . $provider['ADMIN_GOOGLE'] . '
                </small>
                <div class="py-3 event-card-date">
                    ' . $provider['SERVICE'] . '
                </div>
            </div>
            <a href="/events/event/' . $provider['ADMIN_NAME'] . '" class="btn btn-red">About Provider</a>
        </div>
    </div>
    ';
}
