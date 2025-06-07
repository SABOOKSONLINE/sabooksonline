<?php

// rest of nav.php code
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../../Application/Config/connection.php";
require_once __DIR__ . "/../models/UserModel.php";


include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
// session_start();

if (session_status() == PHP_SESSION_NONE) {
    @session_start();
}
// // rest of nav.php code

// // User email from session
// echo '<pre>'; print_r($_SESSION); echo '</pre>';


// Fetch books
// $purchasedBooks = [];
$userModel = new UserModel($conn);
$purchasedBooks = $userModel->getPurchasedBooksByUserEmail($_SESSION['ADMIN_EMAIL']
);

?>

<body>
    <?php include __DIR__ . "/includes/nav.php"; ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("My Bookshelf", "View and manage all your saved and purchased books here.");
                    ?>

                    <?php if (empty($purchasedBooks)) : ?>
                    <div class="alert alert-info shadow-sm mb-4" role="alert">
                        <h5 class="alert-heading mb-2">Your Books</h5>
                        <p class="mb-1">You haven’t purchased any books yet.</p>
                        <hr>
                        <p class="mb-0 text-warning"><i class="fas fa-book"></i> Once you buy books, they’ll show up here.</p>
                    </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($purchasedBooks as $book): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <img src="https://sabooksonline.co.za/cms-data/book-covers/<?= htmlspecialchars($book['COVER']) ?>" class="card-img-top" alt="Book cover">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($book['TITLE']) ?></h5>
                                            <p class="card-text text-muted small">By <?= htmlspecialchars($book['PUBLISHER']) ?></p>
                                            <p class="card-text"><?= htmlspecialchars($book['DESCRIPTION']) ?></p>
                                            <p class="fw-bold">R<?= number_format($book['RETAILPRICE'], 2) ?></p>
                                            <?php if ($book['PDFURL']): ?>
                                                <a href= "/library/readBook/<?= $book['CONTENTID'] ?>" target="_blank" class="btn btn-success btn-sm">
                                                    📘 Read Now
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">📦 Pre-Booked</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php"; ?>
</body>

</html>