<?php
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/dashboard_heading.php";
?>

<body>
    <?php include __DIR__ . "/../includes/nav.php"
    ?>

    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . "/../includes/layouts/side-bar.php"; ?>

                <div class="col offset-lg-3 offset-xl-2 p-2 p-lg-5 overflow-y-scroll mt-5">

                    <div>
                        <?php

                            renderHeading("Upload Onix Files Books", "ONIX (ONline Information eXchange) is the international standard format for sharing book information in XML.
It’s the publishing industry’s way of packaging all the important metadata about a book in a single file that computers can read and exchange.");
                        include __DIR__ . "/../includes/layouts/forms/onix_form.php";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . "/../includes/scripts.php" ?>
</body>

</html>