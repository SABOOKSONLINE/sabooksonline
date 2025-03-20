<?php
        session_start();

        if(!isset($_SESSION['ADMIN_USERKEY'])){
            echo 'User Not Logged In!';
        }else {

            include '../../database_connections/sabooks.php';

            //VARIABLES DECLARED

            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $number = mysqli_real_escape_string($conn, $_POST['number']);
            $website = mysqli_real_escape_string($conn, $_POST['website']);
            $bio = mysqli_real_escape_string($conn, $_POST['bio']);
            $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
            $twitter = mysqli_real_escape_string($conn, $_POST['twitter']);
            $linkedin = mysqli_real_escape_string($conn, $_POST['linkedin']);
            $google = mysqli_real_escape_string($conn, $_POST['google']);
            $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
            $pinterest = mysqli_real_escape_string($conn, $_POST['pinterest']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            //IMAGE UPLOAD CODE START

            $bio_sorted = str_replace('\r\n', '<br>', $bio);
            

            $page_overview = "../../../../authors/".$_SESSION['ADMIN_USERKEY']."/includes/overview.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $bio_sorted);

            $sourcePath = $_FILES['eng_cover']['tmp_name'];

            if($_SESSION['ADMIN_TYPE'] == 'Author'){

                $targetPath = "../../../../authors/".$_SESSION['ADMIN_USERKEY']."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];

                        $page_profile = "../../../../authors/".$_SESSION['ADMIN_USERKEY']."/includes/profile.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                   
                }else {
                    $targetPath1 = $_SESSION['ADMIN_PROFILE_IMAGE'];
                }
                    
            }else if($_SESSION['ADMIN_TYPE'] == 'Publisher'){
                $targetPath = "../../../publishers/".$_SESSION['ADMIN_USERKEY']."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];
    
                   
                }else {
                    $targetPath1 = $_SESSION['ADMIN_PROFILE_IMAGE'];
                }
            }

          

            //IMAGE UPLOAD CODE END
          
                if ($password == '') {
                    //PASSWORD UPLOAD UPDATE PROCESS
                    $password = $_SESSION['ADMIN_PASSWORD'];
                }else{
                    $password = password_hash($password, PASSWORD_DEFAULT);
                }

            $userkey = $_SESSION['ADMIN_USERKEY'];
                    //excluding image and password
                    $sql = "UPDATE users SET ADMIN_NAME='$name',ADMIN_EMAIL='$email',ADMIN_NUMBER='$number',ADMIN_WEBSITE='$website',ADMIN_BIO='$bio' ,ADMIN_PROFILE_IMAGE='$targetPath1',ADMIN_FACEBOOK='$facebook',ADMIN_GOOGLE='$google',ADMIN_PINTEREST='$pinterest',ADMIN_LINKEDIN='$linkedin',ADMIN_INSTAGRAM='$instagram',ADMIN_TWITTER='$twitter',ADMIN_PASSWORD='$password' WHERE ADMIN_USERKEY='$userkey'";

                        if ($conn->query($sql) === TRUE) {




                       /*start the sessions*/
                      
                            header("Location: ../login-update.php?log_email=$email&log_password=1");



                        } else {
                        echo "Error updating record: " . $conn->error;
                        }

                        $conn->close();

        

        }



?>