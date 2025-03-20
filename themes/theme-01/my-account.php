<?php 
// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('includes/api_key.txt');
include 'includes/api_fetch.php';
?>

<?php for ($i = 0; $i < count($title); $i++): ?>

<!doctype html>
<html class="no-js" lang="zxx">


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>My Account </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->

    <!-- Google font (font-family: 'Roboto', sans-serif; Poppins ; Satisfy) -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,600,600i,700,700i,800"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/plugins.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Cusom css -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Modernizer js -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>


    <style>
        header {
            background-color: #f3f3f3 !important;
            marging-bottom: 1%;
        }

        li a {
            color: #333 !important;
        }
    </style>
</head>

<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade
    your browser</a> to improve your experience and security.</p>
<![endif]-->

<!-- Main wrapper -->
<div class="wrapper" id="wrapper">
    <!-- Header -->
    <div style="background-color: #f3f3f3 !important;" class="pt-3">
    <?php include 'includes/header.php';?>
    </div>
    <!-- //Header -->
    <br><br>
    <!-- Start My Account Area -->
    <section class="my_account_area pt--80 pb--55 bg--white">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="my__account__wrapper">
                        <h3 class="account__title">Login</h3>
                        <form id="member-login">
                            <div class="account__form">
                                <div class="input__box">
                                    <label>Email address <span>*</span></label>
                                    <input type="text" name="email">
                                </div>
                                <div class="input__box">
                                    <label>Password<span>*</span></label>
                                    <input type="password" name="password">
                                </div>
                                <div class="form__btn">
                                    <button type="submit" id="log_load">Login</button>
                                    <label class="label-for-checkbox">
                                        <input id="rememberme" class="input-checkbox" name="rememberme"
                                               value="forever" type="checkbox">
                                        <span>Remember me</span>
                                    </label>
                                </div>
                                <a class="forget_pass" href="#">Lost your password?</a>

                                <div id="log_status"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="my__account__wrapper">
                        <h3 class="account__title">Register</h3>
                        <form id="membership">
                            <div class="account__form">
                                <div class="input__box">
                                    <label>First Name <span>*</span></label>
                                    <input type="text" name="f_name">
                                </div>
                                <div class="input__box">
                                    <label>Last Name<span>*</span></label>
                                    <input type="text" name="l_name">
                                </div>
                                <div class="input__box">
                                    <label>Email<span>*</span></label>
                                    <input type="email" name="email">
                                </div>
                                <div class="input__box">
                                    <label>Address<span>*</span></label>
                                    <input type="text" name="address1">
                                </div>
                                <div class="input__box">
                                    <label>Number<span>*</span></label>
                                    <input type="text" name="mobile">
                                </div>
                                <div class="input__box">
                                    <label>Password<span>*</span></label>
                                    <input type="password" name="password" id="password">
                                </div>
                                <div class="input__box">
                                    <label>Confirm Password<span>*</span></label>
                                    <input type="password" name="repassword" id="password2">
                                </div>
								
								<div class="switch-style1">
									<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="showPassword">
									<label class="form-check-label" for="flexSwitchCheckDefault2">Show Password</label>
									</div>
									 <br>
								</div>
                                
                                <div class="form__btn">
                                    <button type="submit" id="reg_load">Register</button>
                                </div>
                            </div>

                            <div id="reg_status"></div>
							
							
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                            var passwordFields = document.querySelectorAll('input[type="password"]');
                            var showPasswordCheckbox = document.getElementById('showPassword');
                            
                            showPasswordCheckbox.addEventListener('change', function() {
                                var passwordType = showPasswordCheckbox.checked ? 'text' : 'password';
                                passwordFields.forEach(function(passwordField) {
                                passwordField.type = passwordType;
                                });
                            });
                            });
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End My Account Area -->
    <!-- Footer Area -->
    <?php include 'includes/footer.php';?>
    <!-- //Footer Area -->

</div>
<!-- //Main wrapper -->

<!-- JS Files -->
<script src="js/vendor/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/vendor/bootstrap.min.js"></script>
<script src="js/plugins.js"></script>
<script src="js/active.js"></script>

<script>
      $("#membership").on('submit',(function(e) {
        e.preventDefault();
    
        $("#reg_load").html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
        
        //showSwal('success-message');
    $.ajax({
            url: "includes/register.php",
        type: "POST",
        data:  new FormData(this),
        contentType: false,
            cache: false,
        processData:false,
        success: function(data)
    {
        $("#reg_load").html('Register');
        $("#reg_status").html(data);
        },
    error: function(){}
    });
        }));


        $("#member-login").on('submit',(function(e) {
        e.preventDefault();
    
        $("#log_load").html('<div class="spinner-border text-light" role="status"> <span class="sr-only">Loading...</span> </div>');
        
        //showSwal('success-message');
    $.ajax({
            url: "includes/login.php",
        type: "POST",
        data:  new FormData(this),
        contentType: false,
            cache: false,
        processData:false,
        success: function(data)
    {
        $("#log_load").html('Login');
        $("#log_status").html(data);
        },
    error: function(){}
    });
        }));

        $('#reload').load('includes/cart/reload.php');

    setInterval(function(){
    $('#reload').load('includes/cart/reload.php');
    },5000);


                
        
    </script>

</body>

</html>

<?php endfor; ?>