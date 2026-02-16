<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";
require_once __DIR__ . "/../models/UserModel.php";

include __DIR__ . "/includes/header.php";
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">
                    <?php
                    renderHeading("Your Book Catalogue", "Easily view, edit, or remove books you’ve published.", "/dashboards/add/listings", "Publish New Book");
                    ?>

                    <?php
                    // Display session-based alerts
                    if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])): ?>
                        <div class="alert alert-<?= $_SESSION['alert_type'] ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?= $_SESSION['alert_type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                            <strong><?= $_SESSION['alert_type'] === 'success' ? 'Success!' : 'Error!' ?></strong>
                            <?= htmlspecialchars($_SESSION['alert_message']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php
                        // Clear the session variables after displaying
                        unset($_SESSION['alert_type']);
                        unset($_SESSION['alert_message']);
                        ?>
                    <?php endif; ?>

                    <?php
                    $userKey = $_SESSION["ADMIN_USERKEY"];
                    $bookListingController = new BookListingController($conn);
                    $bookListingController->renderBookListing($userKey);
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
    <!-- <script>
        const widget = cloudinary.createUploadWidget({
            cloudName: 'dapufnac8',
            uploadPreset: 'bookContent',
            resourceType: 'raw',
            clientAllowedFormats: ['pdf'],
            folder: 'books',
            context: {
                access: "public"
            }, // 👈 Add this if your preset supports it
            public_id: `book_${contentId}` // Optional: set filename
        }, (error, result) => {
            if (!error && result && result.event === "success") {
                const pdfUrl = result.info.secure_url;

                fetch('/includes/save-pdf-url', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `contentid=${contentId}&pdf_url=${encodeURIComponent(pdfUrl)}`
                    })
                    .then(res => res.text())
                    .then(response => {
                        alert(response);
                        location.reload();
                    })
                    .catch(err => alert("Failed to save PDF URL"));
            }
        });


        function uploadPdf(contentId) {
            const widget = cloudinary.createUploadWidget({

                cloudName: 'dapufnac8',
                uploadPreset: 'bookContent',
                resourceType: 'raw',
                clientAllowedFormats: ['pdf'],
                folder: 'books',


            }, (error, result) => {
                if (!error && result && result.event === "success") {
                    const pdfUrl = result.info.secure_url;

                    fetch('/includes/save-pdf-url', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `contentid=${contentId}&pdf_url=${encodeURIComponent(pdfUrl)}`
                        })
                        .then(res => res.text())
                        .then(response => {
                            alert(response);
                            location.reload();
                        })
                        .catch(err => alert("Failed to save PDF URL"));
                }
            });

            widget.open();
        }
    </script> -->
    <script>
        // On page load, restore the previously active tab if available
        window.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss success alerts after 5 seconds
            const successAlerts = document.querySelectorAll('.alert-success');
            successAlerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

</body>

</html>