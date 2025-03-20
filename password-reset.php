<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The Gateway To South African Literature">
    <meta name="author" content="SA Books Online">
    <title>Password Reset</title>

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
				<a href="index"><img src="img/social.png" width="140" height="55" alt=""></a>
			</figure>
			<div class="access_social">
					<h4 class="text-center">Create A New Password</h4>
				</div>
                
                <?php 

                $code = $_GET['code'];
                include 'includes/database_connections/sabooks.php';

                if(empty($code)){
                    $code = "none";
                }

                $sql = "SELECT * FROM users WHERE RESETLINK = '$code';";

                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) == false){
                    
                    echo "<div class='alert alert-warning my-3' style='border: none !important;'>Mmh Looks like your confirmation key was not found.</div>";
                    
                } else {
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['ADMIN_NAME'];
                    $email = $row['ADMIN_EMAIL'];
                    }
                    echo '<div class="text-center mt-2"><small>This is the new password for your approved account of <b>'.$name.'</b></small></div>';

                }
                    

                    
                ?>

                <br>

			<form autocomplete="off" id="login">
				<div id="reg_status"></div>
				<div class="form-group">
					<input class="form-control" type="password" placeholder="Password" name="new_password" required>
					<i class="icon_lock_alt"></i>
				</div>

                <div class="form-group">
					<input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password" required>
					<input class="form-control" type="hidden" placeholder="Confirm Password" name="code" value="<?php echo $_GET['code']?>" required>
					<i class="icon_lock_alt"></i>
				</div>
				
				<button type="submit" class="btn_1 gradient full-width" id="reg_load">Reset Password!</button>
				<div class="text-center mt-2"><small>Want to login instead? <strong><a href="login">Sign In</a></strong></small></div>
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

	<script>
		 //publiish story upload code
		 $("#login").on('submit',(function(e) {
        e.preventDefault();

            $("#reg_load").html('<div class="d-flex justify-content-center align-content-center align-items-center" style="width: 100%;height:100%;position:relative;"><div class="spinner-border text-white" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            //showSwal('success-message');
        $.ajax({
                url: "includes/backend/password-change.php",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
                cache: false,
            processData:false,
            success: function(data)
        {
            $("#reg_load").html('Reset Password');
            $("#reg_status").html(data);
            },
        error: function(){}
        });

        
        }));
	</script>
  
</body>

</html>