<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SA Books Online, South African Books, South African Literature, SA Literature">
    <meta name="keywords" content="SA Books Online, South African Books, South African Literature, SA Literature">
    <meta name="author" content="SA Books Online">
    <title>SA Books Online, SA Books Online, South African Books, South African Literature, SA Literature - The Gateway To South African Literature</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/favicon.png">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/favicon.png">

    <!-- GOOGLE WEB FONT -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" as="fetch" crossorigin="anonymous">
    <script type="text/javascript">
        ! function(e, n, t) {
            "use strict";
            var o = "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap",
                r = "__3perf_googleFonts_c2536";

            function c(e) {
                (n.head || n.body).appendChild(e)
            }

            function a() {
                var e = n.createElement("link");
                e.href = o, e.rel = "stylesheet", c(e)
            }

            function f(e) {
                if (!n.getElementById(r)) {
                    var t = n.createElement("style");
                    t.id = r, c(t)
                }
                n.getElementById(r).innerHTML = e
            }
            e.FontFace && e.FontFace.prototype.hasOwnProperty("display") ? (t[r] && f(t[r]), fetch(o).then(function(e) {
                return e.text()
            }).then(function(e) {
                return e.replace(/@font-face {/g, "@font-face{font-display:swap;")
            }).then(function(e) {
                return t[r] = e
            }).then(f).catch(a)) : a()
        }(window, document, localStorage);
    </script>

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/home.css" rel="stylesheet">
    <link href="css/vegas.min.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-V7MRDHEHSZ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-V7MRDHEHSZ');
    </script>

    <!-- SPECIFIC CSS -->
    <link href="css/contacts.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/listing.css" rel="stylesheet"> <!-- SPECIFIC CSS -->
    <link href="css/detail-page.css" rel="stylesheet">


    <style>
        .owl-item {
            width: fit-content !important;
        }


        * {
            box-sizing: border-box;
        }

        .autocomplete {
            /*the container must be positioned relative:*/
            position: relative;
            display: inline-block;
            width: 100%;
        }


        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            /*when hovering an item:*/
            background-color: #e9e9e9;
        }

        .autocomplete-active {
            /*when navigating through the items using the arrow keys:*/
            background-color: DodgerBlue !important;
            color: #ffffff;
        }

        /* General style for the strip card */
        .strip {
            opacity: 0;
            /* Initially hidden */
            transform: translateX(-100px);
            /* Start off-screen */
            transition: transform 0.5s ease-out, opacity 0.5s ease-out;
        }

        /* Animation for each strip to come into view */
        .strip.visible {
            opacity: 1;
            /* Make item visible */
            transform: translateX(0);
            /* Move to original position */
        }
    </style>

    <!--<script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/3f8124a8d5a5f7cd9bd16387a/ca14274717349fb3480ff8364.js");</script>-->
</head>

<body>

    <?php include "includes/header-internal.php" ?>

    <main class="breaker index-main">
        <!-- /page_header -->
        <div id="carouselExampleIndicators" class="carousel slide py-3" data-bs-ride="carousel" style="background-color: #f3f3f3;">

            <div class="carousel-inner container">

                <?php
                //DATABASE CONNECTIONS SCRIPT
                include 'includes/database_connections/sabooks.php';
                $sql = "SELECT * FROM banners WHERE TYPE = 'Home' ORDER BY ID DESC LIMIT 1;";
                //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) == false) {
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {

                        echo '<div class="carousel-item active">
                                                        <a href="' . ucwords($row['UPLOADED']) . '" target="_blank"><img src="https://admin-dashboard.sabooksonline.co.za/banners/' . ucwords($row['IMAGE']) . '" class="d-block w-100" alt="' . ucwords($row['SLIDE']) . '" style="border-radius: 10px">
                                                        </div></a>';
                    }
                }
                ?>

                <?php
                include 'includes/database_connections/sabooks.php';
                $sql = "SELECT * FROM banners WHERE TYPE = 'Home' ORDER BY ID DESC;";
                $result = mysqli_query($conn, $sql);

                $counter = 0; // Initialize a counter to track results

                if (mysqli_num_rows($result) == false) {
                    // No results to display
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($counter > 0) { // Skip the first result
                            echo '<div class="carousel-item">
                                                            <a href="' . ucwords($row['UPLOADED']) . '" target="_blank"><img src="https://admin-dashboard.sabooksonline.co.za/banners/' . ucwords($row['IMAGE']) . '" class="d-block w-100" alt="' . ucwords($row['SLIDE']) . '" style="border-radius: 10px">
                                                            </div></a>';
                        }
                        $counter++; // Increment the counter
                    }
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: #222;border-radius:50%;"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: #222;border-radius:50%;"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="py-3 page_header d-none bg--black" style="border-radius: 0 0px 30px 30px;">
            <div class="container col-12">
                <!--<h2 class="title_small">Top Categories</h2>-->
                <div class="owl-carousel owl-theme categories_carousel">
                    <?php
                    //DATABASE CONNECTIONS SCRIPT
                    include 'includes/database_connections/sabooks.php';
                    $sql = "SELECT * FROM category ORDER BY category;";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) == false) {
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {

                            $contentid = $row['CATEGORY'];
                            echo '<a href="library?k=' . $row['CATEGORY'] . '" class="badge bg-white text-dark p-3 m-0 w-100 cat-switch ';
                            $rows_query = mysqli_query($conn, "SELECT COUNT(*) as number_rows FROM posts WHERE CATEGORY = '$contentid' AND STATUS = 'active' ORDER BY number_rows DESC;");
                            $rows =                                                                             mysqli_fetch_assoc($rows_query);
                            echo $rows["number_rows"];
                            echo '" style="background: #f3f3f3;" data-src="' . $row['CATEGORY'] . '">' . $row['CATEGORY'] . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- /container -->

        <div class="five" id="books">
            <div class="container margin_60">
                <div class="main_title">
                    <h5>Editor's Choice</h5>
                    <br>
                    <span><em></em></span>
                    <a href="library">View All Books &rarr;</a>
                </div>
                <div class="owl-carousel owl-theme" id="choice">

                    <?php
                    // Assuming you have a valid database connection in 'includes/database_connections/sabooks.php'
                    include 'includes/database_connections/sabooks.php';

                    // Create an SQL query to select books based on CONTENTID and CATEGORY
                    $sql = "SELECT p.* FROM posts AS p
                        JOIN listings AS l ON p.CONTENTID = l.CONTENTID
                        WHERE l.CATEGORY = 'Editors Choice' AND p.STATUS = 'active'
                        ORDER BY RAND() LIMIT 20 ;";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        // No results to display
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $username = ucwords(substr($row['PUBLISHER'], 0, 20));

                            echo '<div class="item">
                                <div class="strip">
                                    <a href="book.php?q=' . strtolower($row['CONTENTID']) . '"><figure>
                                        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $row['COVER'] . '" class="owl-lazy" alt="" width="460" height="310">
                                    </figure></a>
                                    <div class="bottom-text">
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords($row['PUBLISHER']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a><br>
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords(substr($row['AUTHORS'], '0', '20')) . '</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>



                </div>
                <!-- /carousel -->
            </div>
        </div>
        <!-- /bg_gray -->

        <!-- <div class="container mb-3"> 
            <a href="https://ourapp.is/SA-Books-Online"><img src="img/banners/banner-download-mobile-app-new.jpg" width="100%" style="border-radius: 5px;"></a> 
        </div> -->

        <div class="bg_gray container" id="books" style="border-radius: 20px">
            <div class="container margin_60">
                <div class="main_title">
                    <h5>Latest Collections</h5><br>
                    <span><em></em></span>

                    <a href="library">View All Books &rarr;</a>
                </div>
                <div class="owl-carousel owl-theme">
                    <?php
                    // Assuming you have a valid database connection in 'includes/database_connections/sabooks.php'
                    include 'includes/database_connections/sabooks.php';

                    // Create an SQL query to select books based on CONTENTID and CATEGORY
                    $sql = "SELECT p.* FROM posts AS p
                        JOIN listings AS l ON p.CONTENTID = l.CONTENTID
                        WHERE l.CATEGORY = 'Latest Collections' AND p.STATUS = 'active'
                        ORDER BY RAND() LIMIT 20;";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        // No results to display
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $username = ucwords(substr($row['PUBLISHER'], 0, 20));

                            echo '<div class="item">
                                <div class="strip">
                                    <a href="book.php?q=' . strtolower($row['CONTENTID']) . '"><figure>
                                        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $row['COVER'] . '" class="owl-lazy" alt="" width="460" height="310">
                                    </figure></a>
                                    <div class="bottom-text">
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords($row['PUBLISHER']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a><br>
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords(substr($row['AUTHORS'], '0', '20')) . '</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>


                </div>
                <!-- /carousel -->
            </div>
        </div>
        <!-- /bg_gray -->



        <section class="d-none">
            <div class="container mt-3">
                <a href="pricing-plans"><img src="img/banners/banner8.jpg" width="100%" style="border-radius: 10px;"></a>
            </div>
        </section>



        <div class="" id="books">
            <div class="container margin_60">
                <div class="main_title">
                    <h5>Fiction Collections</h5><br>
                    <span><em></em></span>

                    <a href="library">View All Books &rarr;</a>
                </div>
                <div class="owl-carousel owl-theme">

                    <?php
                    // Assuming you have a valid database connection in 'includes/database_connections/sabooks.php'
                    include 'includes/database_connections/sabooks.php';

                    // Create an SQL query to select books based on CONTENTID and CATEGORY
                    $sql = "SELECT p.* FROM posts AS p
                        JOIN listings AS l ON p.CONTENTID = l.CONTENTID
                        WHERE l.CATEGORY = 'Fiction Collections' AND p.STATUS = 'active'
                        ORDER BY RAND() LIMIT 20;";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        // No results to display
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $username = ucwords(substr($row['PUBLISHER'], 0, 20));

                            echo '<div class="item">
                                <div class="strip">
                                    <a href="book.php?q=' . strtolower($row['CONTENTID']) . '"><figure>
                                        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $row['COVER'] . '" class="owl-lazy" alt="" width="460" height="310">
                                    </figure></a>
                                    <div class="bottom-text">
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords($row['PUBLISHER']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a><br>
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords(substr($row['AUTHORS'], '0', '20')) . '</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>


                </div>
                <!-- /carousel -->
            </div>
        </div>
        <!-- /bg_gray -->


        <div class="bg_gray container" id="books" style="border-radius: 20px">
            <div class="container margin_60">
                <div class="main_title">
                    <h5>Children's Collections</h5><br>
                    <span><em></em></span>

                    <a href="library">View All Books &rarr;</a>
                </div>
                <div class="owl-carousel owl-theme">

                    <?php
                    // Assuming you have a valid database connection in 'includes/database_connections/sabooks.php'
                    include 'includes/database_connections/sabooks.php';

                    // Create an SQL query to select books based on CONTENTID and CATEGORY
                    $sql = "SELECT p.* FROM posts AS p
                        JOIN listings AS l ON p.CONTENTID = l.CONTENTID
                        WHERE l.CATEGORY = 'Childrens Collection' AND p.STATUS = 'active'
                        ORDER BY RAND() LIMIT 20;";

                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        // No results to display
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $username = ucwords(substr($row['PUBLISHER'], 0, 20));

                            echo '<div class="item">
                                <div class="strip">
                                    <a href="book.php?q=' . strtolower($row['CONTENTID']) . '"><figure>
                                        <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $row['COVER'] . '" class="owl-lazy" alt="" width="460" height="310">
                                    </figure></a>
                                    <div class="bottom-text">
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords($row['PUBLISHER']) . ' <small class="icon_check_alt text-success" style="font-size:12px"></small></a><br>
                                        <a href="creator?q=' . strtolower($row['USERID']) . '" class="text-dark">' . ucwords(substr($row['AUTHORS'], '0', '20')) . '</a>
                                    </div>
                                </div>
                            </div>';
                        }
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>









                </div>
                <!-- /carousel -->
            </div>
        </div>
        <!-- /bg_gray -->

    </main>
    <!-- /main -->

    <?php include 'includes/footer.php'; ?>

    <!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.min.js"></script>
    <script src="js/common_func.js"></script>
    <script src="assets/validate.js"></script>
    <script src="js/custom.js"></script>

    <!-- SPECIFIC SCRIPTS -->
    <script src="js/vegas.min.js"></script>
    <script src="js/specific_listing.js"></script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const strips = document.querySelectorAll('.strip'); // Select all .strip elements

    strips.forEach((strip, index) => {
        setTimeout(() => {
            strip.classList.add('visible'); // Add 'visible' class with delay
        }, index * 500); // Each item appears 3 seconds apart
    });
});



</script>
<script>
    $(document).ready(function() {
         $('.owl-carousel').owlCarousel({
loop:true,
margin:10,
autoPlay:true,
nav:true,
rewindNav:false,
responsive:{
    0:{
        items:1,
        loop: true
    },
    600:{
        items:3,
        loop: true
        
    },
    1000:{
        items:4,
        loop: true
    }
}
  })
    });
</script>
    <script>
        $(document).ready(function() {

            $('#choic').html('<div class="d-flex justify-content-center"><div class="spinner-border text-secondary"  style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>')

            $('#choic').load('includes/editors-choice.php');
        });
    </script>



    <script>
        var countries = [<?php
                            //DATABASE CONNECTIONS SCRIPT
                            include 'includes/database_connections/sabooks.php';
                            $sql = "SELECT * FROM posts WHERE STATUS = 'active';";
                            //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
                            $result = mysqli_query($conn, $sql);
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) == false) {
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {

                                    echo '"' . $row["TITLE"] . '",';
                                }
                            }
                            ?> " "];
    </script>

    <script>
        $('#myForm input').keypress(function(event) {
            // Check if Enter key is pressed (key code 13)
            if (event.which === 13) {
                event.preventDefault(); // Prevent form submission

                // Submit the form
                $('#myForm').submit();
            }
        });
    </script>

    <script>
        $('.owl-carousel').owlCarousel({
            stagePadding: 50,
            loop: true,
            margin: 10,
            autoWidth: true,
            loop: false,
            items: 6,
            center: false,
            dots: false,
            merge: true,
            nav: true,
            navText: ["<i class='arrow_left'></i>", "<i class='arrow_right'></i>"],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        })
    </script>

    <script>
        var countries = [<?php
                            //DATABASE CONNECTIONS SCRIPT
                            include 'includes/database_connections/sabooks.php';
                            $sql = "SELECT * FROM posts WHERE STATUS = 'active';";
                            //$sql = "SELECT * FROM posts WHERE TYPE = '$name' AND CATEGORY LIKE '%$name%' OR DESCRIPTION LIKE '%$name%' ORDER BY ID DESC;";
                            $result = mysqli_query($conn, $sql);
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) == false) {
                            } else {
                                while ($row = mysqli_fetch_assoc($result)) {

                                    echo '"' . $row["TITLE"] . '",';
                                }
                            }
                            ?> " "];
    </script>

    <script src="js/custom.js"></script>


</body>

</html>