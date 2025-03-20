<?php

        session_start();

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/

          //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $reviews_rating = mysqli_real_escape_string($conn, $_POST['reviews_rating']);
        $reviews_title = mysqli_real_escape_string($conn, $_POST['reviews_title']);
        $reviews_review = mysqli_real_escape_string($conn, $_POST['reviews_review']);
        $reviews_contentid = mysqli_real_escape_string($conn, $_POST['reviews_contentid']);
        $userkey = mysqli_real_escape_string($conn, $_POST['reviews_userkey']);
        $reviews_contentid = mysqli_real_escape_string($conn, $_POST['reviews_id']);
        $reg_email = mysqli_real_escape_string($conn, $_POST['reviews_email']);
        $username = $_SESSION['ADMIN_NAME'];

        //Logged in variables

        //§$userid = substr('0','8', uniqid());

        if(isset($_SESSION['ADMIN_ID'])){

                //IMAGE UPLOAD CODE END

                if(!preg_match('/^[a-zA-Z0-9 ]*$/', $reg_name)){

                    echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>You used some invalid characters in your form! Check your <b>Title or Review</b> inputs</div>";  
        
                } else {
                
                //TIME VARIABLE
                $current_time = date('l jS \of F Y');
        
                //INSERT REGISTRATION DATA INTO DATABASE
                    
                            $sql = "INSERT INTO reviews (REVIEW, CONTENTID,	USERKEY, DATEPOSTED, STATUS,	RATING,	USERNAME, TITLE) VALUES ('$reviews_review','$reviews_contentid','$userkey','$current_time','active','$reviews_rating','$username','$reviews_title');";
        
                            if(mysqli_query($conn, $sql)){
                            
                            $message = "You just got a review! on your SA Books Online profile.";
                            $button_link = $veri_link;
                            $link_text = "See Reviews";
        
                            include '../templates/email.php';
        
                            $subject = 'New Review By '.ucwords($username).' on SA Books Online!';
        
                            //$sqlss = "UPDATE users SET RESETLINK = '$combined' WHERE ADMIN_USERKEY = '$userkey'";
                                            
                            //mysqli_query($conn, $sqlss);
        
                            if(mail($reg_email,$subject,$message2,$headers)){
        
                                    echo '<script>window.location.href="https://sabooksonline.co.za/confirm.php?email='.$_SESSION['ADMIN_EMAIL'].'&message=Review Successfully Added";</script>';
        
                                }
                                
                            }else {
                                    echo "<p class='alert alert-warning p-2 mb-3'>Your review could not be posted!</p>";
                            }
        
                }
  

        }else{
            echo '<div class="alert text-center bg-warning p-3 mb-3 mt-4">You need to Sign in to submit reviews! You may login <a href="login?provider='.$userid.'">here</a></div>'; 
        }


        
?>