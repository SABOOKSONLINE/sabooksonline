<?php
    
      
            //DATABASE CONNECTIONS SCRIPT
        include '../../database_connections/sabooks.php';

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
        $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
        $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
        $twitter = mysqli_real_escape_string($conn, $_POST['twitter']);
        $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
        $website = mysqli_real_escape_string($conn, $_POST['website']);
        $type = mysqli_real_escape_string($conn, $_POST['type']);
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];
           
            //$date = date("l jS \of F Y h:i:s A");
            $date = date("l, jS \of F Y");
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];

            //IMAGE UPLOAD CODE START

            $bio_sorted = str_replace('\r\n', '', $desc);
            $page_overview = "../../../../".$type."/".$contentid."/includes/overview.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $bio_sorted);

            $page_overview = "../../../../".$type."/".$contentid."/includes/title.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $name);

            $page_overview = "../../../../".$type."/".$contentid."/includes/title.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $name);

            $page_overview = "../../../../".$type."/".$contentid."/includes/number.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $number);

            $page_overview = "../../../../".$type."/".$contentid."/includes/facebook.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $facebook);

            $page_overview = "../../../../".$type."/".$contentid."/includes/twitter.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $twitter);

            $page_overview = "../../../../".$type."/".$contentid."/includes/instagram.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $instagram);

            $page_overview = "../../../../".$type."/".$contentid."/includes/address.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $address);

            $page_overview = "../../../../".$type."/".$contentid."/includes/website.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $website);

            $page_overview = "../../../../".$type."/".$contentid."/includes/keywords.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $keywords);

            $page_overview = "../../../../".$type."/".$contentid."/includes/email.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $email);

            

            $sourcePath = $_FILES['eng_cover']['tmp_name'];

                $targetPath = "../../../../".$type."/".$contentid."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];

                        $page_profile = "../../../../".$type."/".$contentid."/includes/cover.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                   
                }else {

                    $sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$contentid' ORDER BY ADMIN_ID DESC;";
							$result = mysqli_query($conn, $sql);


							if(mysqli_num_rows($result) == false){
							echo "Page not found in the database!";

							}else{
							while($row = mysqli_fetch_assoc($result)) {
								
                                $targetPath1 = $row['ADMIN_PROFILE_IMAGE'];
							}
							}
                    
                }
                    
                    //excluding image and password
                    $sql = "UPDATE users SET ADMIN_NAME='$name',ADMIN_EMAIL='$email',ADMIN_NUMBER='$number',ADMIN_WEBSITE='$website',ADMIN_BIO='$desc' ,ADMIN_PROFILE_IMAGE='$targetPath1',ADMIN_FACEBOOK='$facebook',ADMIN_INSTAGRAM='$instagram',ADMIN_TWITTER='$twitter' WHERE ADMIN_USERKEY='$contentid'";

                        if ($conn->query($sql) === TRUE) {
                        echo '<p class="alert alert-success">Page successfully updated!</p>';

                        } else {
                        echo '<p class="alert alert-warning">Something went wrong!</p>' . $conn->error;
                        }

                        $conn->close();

 

?>