<?php
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";

require_once __DIR__ . "/../../database/connection.php";
require_once __DIR__ . "/../../models/BookListingsModel.php";
require_once __DIR__ . "/../../controllers/BookListingsController.php";

$bookListingController = new BookListingController($conn);
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">

                    <div id="bookDetailsSection">
                        <?php
                        $contentId = $_GET["id"] ?? null;
                        $userKey = $_SESSION["ADMIN_USERKEY"];

                        if (isset($_GET["id"]) && !empty($_GET["id"])) {
                            renderHeading("Manage Book", "You can manage, add or delete your book listings.");
                        } else {
                            renderHeading("Publish New Book", "You can manage, add or delete your book listings.");
                        }


                        $bookListingController->renderBookByContentId($userKey, $contentId);

                        // "/dashboards/add/audiobook/$contentId", "Add Audiobook"
                        ?>
                    </div>
                    <div id="audioDetailsSection" style="display:none;">
                        <?php
                        $contentId = $_GET["id"] ?? null;
                        $bookId = $bookListingController->getAudiobookByContentId($contentId)['ID'];

                        if ($contentId) {
                            renderHeading("Update Audiobook", "You can update your audiobook details.");
                        } else {
                            renderHeading("Add Audiobook", "Fill in details to add a new audiobook.");
                        }


                        $deleteBook = $_GET["delete"] ?? null;
                        $addBookStatus = $_GET["status"] ?? null;
                        $updateBook = $_GET["update"] ?? null;

                        if ($deleteBook === 'success') {
                            echo '
                        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                            Audiobook successfully deleted!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        } else if ($deleteBook === 'fail') {
                            echo '
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            Something went wrong while deleting Audiobook!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        }

                        if ($addBookStatus === 'success') {
                            echo '
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            Audiobook successfully added!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        } else if ($addBookStatus === 'fail') {
                            echo '
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            Something went wrong while adding Audiobook!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        }

                        if ($updateBook === 'success') {
                            echo '
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            Audiobook successfully updated!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        } else if ($updateBook === 'fail') {
                            echo '
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            Something went wrong while updating Audiobook!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        ';
                        }

                        $bookListingController->getAudiobookByBookId($bookId, $contentId);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>

    <script>
        const showBookBtn = document.getElementById('showBookDetails');
        const showAudioBtn = document.getElementById('showAudioDetails');
        const bookSection = document.getElementById('bookDetailsSection');
        const audioSection = document.getElementById('audioDetailsSection');

        showBookBtn.addEventListener('click', () => {
            bookSection.style.display = 'block';
            audioSection.style.display = 'none';
            showBookBtn.classList.add('btn-dark');
            showBookBtn.classList.remove('btn-outline-dark');
            showAudioBtn.classList.remove('btn-dark');
            showAudioBtn.classList.add('btn-outline-dark');
        });

        showAudioBtn.addEventListener('click', () => {
            bookSection.style.display = 'none';
            audioSection.style.display = 'block';
            showAudioBtn.classList.add('btn-dark');
            showAudioBtn.classList.remove('btn-outline-dark');
            showBookBtn.classList.remove('btn-dark');
            showBookBtn.classList.add('btn-outline-dark');
        });
    </script>


</body>

</html>