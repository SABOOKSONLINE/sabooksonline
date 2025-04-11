<?php
session_start();

require_once 'vendor/autoload.php';

use Google\Client as Google_Client;

$client = new Google_Client();
$client->setClientId('881101796322-kpqdbda7rse6thp07sfbd8fo1solaiij.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://sabooksonline.co.za/google/callback.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    header('Location: https://11-july-2023.sabooksonline.co.za/https://11-july-2023.sabooksonline.co.za/login');  
}

$authUrl = $client->createAuthUrl();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature | Become a Member of SA Books Online">
    <meta name="author" content="SA Books Online">
    <title>Register | SA Books Online</title>

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
    !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap",r="__3perf_googleFonts_c2536";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
    </script>

    <!-- BASE CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/contacts.css" rel="stylesheet">

    <!-- SPECIFIC CSS -->
    <link href="css/order-sign_up.css" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="css/custom.css" rel="stylesheet">
<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-V7MRDHEHSZ"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'G-V7MRDHEHSZ');
		gtag('config', 'AW-11379832900');
	</script>
    
</head>

<body id="register_bg">
	
	<div id="register">
		<aside>
			<figure>
				<a href="index"><img src="img/social.png" width="140" height="65" alt=""></a>
			</figure>
				<div class="access_social">
					<a href="<?php echo $authUrl; ?>" class="social_bt google" style="background: #fff !important;border: #ddd 1px solid;color: #444;"><img src="img/Google__G__Logo.svg" width="20px" class="mr-5"> Register With Google</a>
				</div>
            <div class="divider"><span>Or</span></div>
			<form autocomplete="off" id="membership">
				<div class="form-group">
					<input class="form-control" type="text" placeholder="Name" name="reg_name">
					<i class="icon_pencil-edit"></i>
				</div>
				<div class="form-group">
					<input class="form-control" type="text" placeholder="Phone number" name="reg_number">
					<i class="icon_pencil-edit"></i>
				</div>
				<div class="form-group">
					<input class="form-control" type="email" placeholder="Email" name="reg_email">
					<i class="icon_mail_alt"></i>
				</div>
				<div class="form-group">
					<input class="form-control" type="password" id="password1" placeholder="Password" name="reg_password">
					<i class="icon_lock_alt"></i>
				</div>
				<div class="form-group">
					<input class="form-control" type="password" id="password2" placeholder="Confirm Password" name="reg_confirm_password">
					<i class="icon_lock_alt"></i>
				</div>
				<div id="pass-info" class="clearfix"></div>
				<button class="btn_1 gradient full-width" id="reg_load" type="submit">Register Now!</button>
				<div class="text-center mt-2"><small>Already have an acccount? <strong><a href="login.php">Sign In</a></strong></small></div>
                <div id="reg_status"></div>
            
            </form>
			<div class="copy">© 2023 SA Books Online</div>
		</aside>
	</div>
	<!-- /login -->
	
	<!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.min.js"></script>
	<script src="js/custom.js"></script>
    <script src="js/common_func.js"></script>
    <script src="assets/validate.js"></script>
	
	<!-- SPECIFIC SCRIPTS -->
	<script src="js/pw_strenght.js"></script>	

	 
	<script>
		 //publiish story upload code
		 $("#membership").on('submit',(function(e) {
        e.preventDefault();

            $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-center" style="width: 100%;height:100%;position:relative;"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            //showSwal('success-message');
        $.ajax({
                url: "includes/backend/register.php",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
                cache: false,
            processData:false,
            success: function(data)
        {
            $("#reg_load").html('Submit Application');
            $("#reg_status").html(data);
            },
        error: function(){}
        });

        
        }));
	</script>

<script src="https://accounts.google.com/gsi/client" async defer></script>

<script>
  function handleCredentialResponse(response) {
    const responsePayload = response.credential;
    const idToken = responsePayload.id_token;

    // Send the idToken to the server for account creation
    $.ajax({
      url: 'process_account_creation.php',
      type: 'POST',
      data: { id_token: idToken },
      success: function (data) {
        // Handle the server response
        console.log(data);
      }
    });
  }
</script>

  
</body>

</html>