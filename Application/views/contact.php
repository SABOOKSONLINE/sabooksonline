<?php
require_once __DIR__ . "/includes/header.php";
?>

<body>
    <?php require_once __DIR__ . "/includes/nav.php"; ?>

    <div class="jumbotron jumbotron-md">
        <div class="container h-100 d-flex flex-column justify-content-end py-5">
            <div class="jumbo-details">
                <h1 class="display-4">Contact <b>SABooksOnline</b></h1>
                <p class="lead mb-4">Start the Next Chapter – Contact Us Today.</p>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">

            <div class="col-md-6 p-5 text-center">
                <h1 class="text-secondary">
                    <i class="fas fa-location"></i>
                </h1>
                <h2>Address</h2>
                <div>13th Floor, Illovo Point, 68 Melville Rd,<br> Illovo, Sandton, 2196</div>
            </div>
            <div class="col-md-6 p-5 text-center">
                <h1 class="text-secondary">
                    <i class="fas fa-phone"></i>
                </h1>
                <h2>Enquiries</h2>
                <a href="tel:+27 67 852 3593">+27 67 852 3593</a> - <a href="mailto:admin@sabooksonline.co.za">admin@sabooksonline.co.za</a>
                <br>
                <small class="text-muted">MON to FRI 9am-6pm</small>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <h3 class="mb-3">Drop Us a Line</h3>
        <div class="row">
            <div class="col-lg-4 col-md-6 add_bottom_25">
                <div id="message-contact"></div>
                <form id="contactforms" autocomplete="on">
                    <div class="form-group mb-3">
                        <input class="form-control" name="name" type="text" placeholder="Name" id="name_contact" name="name_contact" required>
                    </div>
                    <div class="form-group mb-3">
                        <input class="form-control" name="email" type="email" placeholder="Email" id="email_contact" name="email_contact" required>
                    </div>
                    <div class="form-group mb-3">
                        <input class="form-control" name="number" type="number" placeholder="Number" id="email_contact" name="email_contact" required>
                    </div>
                    <div class="form-group mb-3">
                        <input class="form-control" name="subject" type="text" placeholder="Subject" id="email_contact" name="email_contact" required>
                    </div>
                    <div class="form-group mb-3">
                        <textarea class="form-control" name="message" style="height: 100px;" placeholder="Message" id="message_contact" name="message_contact" required></textarea>
                    </div>

                    <div class="input-group">
                        <div id="html_element"></div>
                    </div><br>

                    <div class="form-group">
                        <input class="btn btn-blue w-100 mb-3" type="submit" value="Submit">
                    </div>
                    <div class="form-group" id="status">

                    </div>
            </div>
            </form>
            <div class="col-lg-8 col-md-6 add_bottom_25">
                <iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?width=520&amp;height=400&amp;hl=en&amp;q=13th%20Floor,%20Illovo%20Point,%2068%20Melville%20Rd,%20Illovo,%20Sandton%20Johannesburg+(SA%20Books%20Online)&amp;t=&amp;z=12&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href='https://maps-generator.com/'></a>
            </div>
        </div>
        <!-- /row -->
    </div>

    <?php require_once __DIR__ . "/includes/footer.php" ?>

    <?php require_once __DIR__ .  "/includes/scripts.php" ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
</body>