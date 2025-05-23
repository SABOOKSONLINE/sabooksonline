<?php include __DIR__ . "/includes/header.php" ?>

<?php
include __DIR__ . "/includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/includes/nav.php" ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-xl-2">
                    <?php include __DIR__ . "/includes/layouts/side-bar.php" ?>
                </div>


                <div class="col py-5">
                    <?php renderHeading("Reviews", "You can manage, add or delete your reviews based on listings.") ?>

                    <div class="row p-3">
                        <div class="alert alert-success" role="alert">
                            Submit your PDF Manuscript and MP3 Audio Book File to our Mobile App! Submissions can take up-to 5 working days.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/includes/scripts.php" ?>
</body>

</html>