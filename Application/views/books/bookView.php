<?php echo '
<div class="container pt-4 pb-5">
    <div class="row">
        <!-- Book Cover -->
        <div class="col-md-3 book-view-cover">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" class="img-fluid" alt="Book Cover">
        </div>

        <!-- Book Info -->
        <div class="col-md-9">
            <h2 class="fw-bold text-capitalize">' . $book['TITLE'] . '</h2>
            <p class="mb-1 text-capitalize"><span class="muted">Published by:</span> <a href="/creators/creator/' . strtolower($book['USERID']) . '" class="fw-semibold">' . $book['PUBLISHER'] . '</a></p>
            <p class="mb-3 text-capitalize"><span class="muted">Author/s:</span> <span class="fw-semibold">' . $book['AUTHORS'] . '</span></p>

            <h6 class="mb-2">Book Synopsis:</h6>
            <p>
                ' . $book['DESCRIPTION'] . '
            </p>

            <!-- Ratings -->
            <div class="mb-5">
                Ratings:
                <span class="text-warning">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </span>
                <span class="ms-2 text-muted">4.0</span>
            </div>

            <!-- Action Buttons & Price -->
            <div class="row g-2">
                <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
                    <a href="" class="btn btn-green">READ NOW</a>
                    <span class="ms-2 fw-bold">COMING SOON</span>
                </div>
                <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
                    <a href="" class="btn btn-yellow"><i class="bi bi-headphones"></i> LISTEN TO AUDIOBOOK</a>
                    <span class="ms-2 fw-bold">COMING SOON</span>
                </div>
                <div class="col-md-7 d-flex justify-content-between align-items-center p-2">
                    <a href="" class="btn btn-blue">BUY COPY</a>
                    <span class="ms-2 fw-bold">R' . $book['RETAILPRICE'] . '.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
';
