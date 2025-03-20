<?php
        session_start();

        if(!isset($_SESSION['ADMIN_USERKEY'])){
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Session timed-out, please login.',showConfirmButton: false,timer: 6000});</script>";
        }else {

            include '../database_connections/sabooks.php';
            include '../database_connections/sabooks_plesk.php';

            //VARIABLES DECLARED

            $name = mysqli_real_escape_string($conn, $_POST['reg_name']);
            $email = mysqli_real_escape_string($conn, $_POST['reg_email']);
            $number = mysqli_real_escape_string($conn, $_POST['reg_number']);
            $website = mysqli_real_escape_string($conn, $_POST['reg_website']);
            $bio = mysqli_real_escape_string($conn, $_POST['reg_bio']);
            $facebook = mysqli_real_escape_string($conn, $_POST['reg_facebook']);
            $twitter = mysqli_real_escape_string($conn, $_POST['reg_twitter']);
            $linkedin = mysqli_real_escape_string($conn, $_POST['reg_linkedin']);
            $instagram = mysqli_real_escape_string($conn, $_POST['reg_instagram']);
            $password = mysqli_real_escape_string($conn, $_POST['reg_password']);
            $password1 = mysqli_real_escape_string($conn, $_POST['reg_confirm_password']);
            $reg_address = mysqli_real_escape_string($conn, $_POST['reg_address']);
            $province = mysqli_real_escape_string($conn, $_POST['reg_province']);

            //IMAGE UPLOAD CODE START

            include 'google/coordinates.php';

            $sourcePath = $_FILES['reg_profile']['tmp_name'];

            $targetPath = "../../cms-data/profile-images/".$_FILES['reg_profile']['name'];

            $profile = $_SESSION['ADMIN_PROFILE_IMAGE'];

            if(move_uploaded_file($sourcePath,$targetPath)){
                $profile = $_FILES['reg_profile']['name'];
            }
            //IMAGE UPLOAD CODE END


                    if($password != $password1){
                        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your passwords do not match',showConfirmButton: false,timer: 6000});</script>";
                    } else {

                        if (empty($password)) {
                            //PASSWORD UPLOAD UPDATE PROCESS
                            $password = $_SESSION['ADMIN_PASSWORD'];
                        }else{
                            $password = password_hash($password, PASSWORD_DEFAULT);
                        }
        
                        $userkey = $_SESSION['ADMIN_USERKEY'];
        
                        if(empty($latitude) || empty($latitude)){
        
                            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'We could not find the address you typed in on <b>Google</b>, Please select the suggested address from the input.',showConfirmButton: false,timer: 6000});</script>";
                        } else {
        
                            $update = "UPDATE posts SET PUBLISHER = '$name' WHERE USERID = '$userkey'";
        
                            mysqli_query($conn, $update);
        
                            //excluding image and password
                            $sql = "UPDATE users SET ADMIN_NAME='$name',ADMIN_EMAIL='$email',ADMIN_NUMBER='$number',ADMIN_WEBSITE='$website',ADMIN_BIO='$bio' ,ADMIN_PROFILE_IMAGE='$profile',ADMIN_FACEBOOK='$facebook',ADMIN_LINKEDIN='$linkedin',ADMIN_INSTAGRAM='$instagram',ADMIN_TWITTER='$twitter',ADMIN_GOOGLE='$address',ADMIN_PASSWORD='$password' ,ADMIN_LATITUDE='$latitude' ,ADMIN_LONGITUDE='$longitude' WHERE ADMIN_USERKEY='$userkey'";
        
                            if ($conn->query($sql) === TRUE) {
        
                                    $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userkey';";
        
                                    $row = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        
                                    $_SESSION['ADMIN_NAME'] = $row['ADMIN_NAME'];
                                    $_SESSION['ADMIN_EMAIL'] = $row['ADMIN_EMAIL'];
                                    $_SESSION['ADMIN_NUMBER'] = $row['ADMIN_NUMBER'];
                                    $_SESSION['ADMIN_WEBSITE'] = $row['ADMIN_WEBSITE'];
                                    $_SESSION['ADMIN_BIO'] = $row['ADMIN_BIO'];
                                    $_SESSION['ADMIN_TYPE'] = $row['ADMIN_TYPE'];
                                    $_SESSION['ADMIN_FACEBOOK'] = $row['ADMIN_FACEBOOK'];
                                    $_SESSION['ADMIN_TWITTER'] = $row['ADMIN_TWITTER'];
                                    $_SESSION['ADMIN_LINKEDIN'] = $row['ADMIN_LINKEDIN'];
                                    $_SESSION['ADMIN_INSTAGRAM'] = $row['ADMIN_INSTAGRAM'];
                                    $_SESSION['ADMIN_GOOGLE'] = $row['ADMIN_GOOGLE'];
                                    $_SESSION['ADMIN_PINTEREST'] = $row['ADMIN_PINTEREST'];
                                    $_SESSION['ADMIN_PASSWORD'] = $row['ADMIN_PASSWORD'];
                                    $_SESSION['ADMIN_PROFILE_IMAGE'] = $row['ADMIN_PROFILE_IMAGE'];
                                    $_SESSION['ADMIN_USER_STATUS'] = $row['ADMIN_USER_STATUS'];

                                    //excluding image and password
                                    $sql_accounts = "UPDATE plesk_accounts SET ACCOUNT_NAME='$name',SITE_TITLE='$name',SITE_EMAIL='$email',DESCRIPTION='$bio',LOGO='$profile',SITE_ADDRESS='$address' WHERE USERKEY='$userkey'";

                                    mysqli_query($mysqli, $sql_accounts);
                                                        
                                    echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Profile has been updated!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('profile?result=success');},3000);</script>";
        
                            } else {
                            // echo "Error updating record: " . $conn->error;
                                                
                            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Something went wrong!',showConfirmButton: false,timer: 6000});</script>";
                            }
                        }

                    }

            } 

             $conn->close();




?>