<?php echo '
<div class="container py-5">
    <div class="row align-items-center">
        <!-- Book Cover -->
        <div class="col-md-4 text-center mb-4 mb-md-0">
            <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" class="img-fluid rounded shadow" alt="Book Cover" style="max-height: 400px;">
        </div>

        <!-- Book Info -->
        <div class="col-md-8">
            <h2 class="fw-bold text-capitalize">' . $book['TITLE'] . '</h2>
            <p class="mb-1 text-capitalize"><strong>Published by:</strong> <span class="fw-semibold">' . $book['PUBLISHER'] . '</span></p>
            <p class="mb-3 text-capitalize"><strong>Author:</strong> <span class="fw-semibold">' . $book['PUBLISHER'] . '</span></p>

            <h6 class="mb-2">Book Synopsis:</h6>
            <p>
                ' . $book['DESCRIPTION'] . '
            </p>

            <!-- Ratings -->
            <div class="mb-3">
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
