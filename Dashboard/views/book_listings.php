<?php
require_once __DIR__ . "/../database/connection.php";
require_once __DIR__ . "/../models/BookListingsModel.php";
require_once __DIR__ . "/../controllers/BookListingsController.php";

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

                    $deleteBook = $_GET["delete"] ?? null;
                    $addBookStatus = $_GET["status"] ?? null;
                    $updateBook = $_GET["update"] ?? null;

                    if ($deleteBook === 'success') {
                        echo '
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            Book successfully deleted!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                    } else if ($deleteBook === 'fail') {
                        echo '
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            Something went wrong while deleting book!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                    }

                    if ($addBookStatus === 'success') {
                        echo '
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            Book successfully added!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                    } else if ($addBookStatus === 'fail') {
                        echo '
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            Something went wrong while adding book!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                    }

                    if ($updateBook === 'success') {
                        echo '
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            Book successfully updated!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                    } else if ($updateBook === 'fail') {
                        echo '
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            Something went wrong while updating book!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                    }

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

</body>

</html>