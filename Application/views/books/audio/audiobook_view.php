<?php require_once __DIR__ . "/../../includes/header.php"; ?>

<body>
    <?php require_once __DIR__ . "/../../includes/nav.php"; ?>

    <section class="audio-book">

        <?php
        require __DIR__ . "/../../../Config/connection.php";
        require __DIR__ . "/../../../models/BookModel.php";
        require __DIR__ . "/../../../controllers/BookController.php";

        $controller = new BookController($conn);
        $controller->renderAudioBookView();
        ?>

        <div class="open-audio-details">
            <span>
                <i class="fas fa-bars"></i>
            </span>
        </div>

        <div class="audio-controllers">
            <div class="audio-ctrl-row">
                <div class="audio-ctrls">
                    <div class="audio-ctrl" id="ctrl-backward">
                        <i class="fas fa-backward"></i>
                    </div>
                    <div class="audio-ctrl" id="ctrl-play">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="audio-ctrl" id="ctrl-forward">
                        <i class="fas fa-forward"></i>
                    </div>
                </div>
                <div class="audio-trackers">
                    <div class="audio-ctrl" id="ctrl-tracker">
                        <span id="tracker-time"></span>
                        <div class="tracker"></div>
                    </div>
                    <div class="audio-ctrl" id="ctrl-volume">
                        <div class="tracker"></div>
                        <i class="fas fa-volume-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<?php require_once __DIR__ .  "/../../includes/scripts.php" ?>