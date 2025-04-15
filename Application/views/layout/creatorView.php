<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo '

<div class="container pt-4 pb-5">
    <div class="row">
        <!-- Book Cover -->
        <div class="col-md-3 creator-view-cover">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $creator->getCover() . '" class="img-fluid" alt="Book Cover">
        </div>

        <!-- Book Info -->
        <div class="col-md-9">
            <h2 class="fw-bold text-capitalize">' . $creator->getName() . '</h2>
            <h6 class="mb-2">Book Synopsis:</h6>
        </div>
    </div>
</div>

';
