<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Audio Book</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;500;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet" />

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
        crossorigin="anonymous" />

    <!-- Icons -->
    <link href="../../../public/css/icons.css" rel="stylesheet" />
    <link href="../../../public/css/all.css" rel="stylesheet" />
    <link href="../../../public/css/all.min.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="/public/css/custom/style.css" rel="stylesheet" />
    <link href="/public/css/custom/audioBook.css" rel="stylesheet" />
</head>

<body>
    <section class="audio-book">
        <style>
            .audio-cover::before {
                background: url("/cms-data/book-covers/682d39.jpg");

                background-position: center;
                background-size: cover;
            }

            .audio-controllers::before {
                background: url("/cms-data/book-covers/682d39.jpg");

                background-position: center;
                background-size: cover;
            }
        </style>
        <div class="audio-cover">
            <div class="audio-img">
                <img src="/cms-data/book-covers/682d39.jpg" alt="" />
            </div>
        </div>
        <div class="audio-book-list">
            <div class="audio-book-title">
                <h2 class="title">I think I'm Depressed</h2>
            </div>
            <div class="audio-chapters">
                <div class="chapter">
                    chapter 01
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 02
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 03
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 04
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 05
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 06
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 07
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 08
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 09
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 10
                    <div class="chapter-time">20:00</div>
                </div>
                <div class="chapter">
                    chapter 11
                    <div class="chapter-time">20:00</div>
                </div>
            </div>
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

    <script src="/public/js/jquery-1.7.2.min.js"></script>
    <script src="/public/js/audioBook.js"></script>
</body>

</html>