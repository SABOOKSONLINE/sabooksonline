<?php
        session_start();

        if(!isset($_SESSION['ADMIN_USERKEY'])){
            echo 'User Not Logged In!';
        }else {

            
        //DATABASE CONNECTIONS SCRIPT
        include '../../dbh.inc.php';

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $brand = mysqli_real_escape_string($conn, $_POST['brand']);
        $products = mysqli_real_escape_string($conn, $_POST['products']);
        $price_current = mysqli_real_escape_string($conn, $_POST['price_current']);
        $price_old = mysqli_real_escape_string($conn, $_POST['price_old']);
        $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
        $sku = mysqli_real_escape_string($conn, $_POST['sku']);
        $contentid = substr(uniqid(), '0', '5');
        $contentid = strtolower(str_replace(" ", "-", $title.$contentid));
        $stock = 'Available';

        $sourcePath = $_FILES['eng_cover']['tmp_name'];

        $title_show = $title;
        $title = str_replace("'", "`", $title);
       
        //$date = date("l jS \of F Y h:i:s A");
        $date = date("l, jS \of F Y");

        $context = strip_tags(substr($desc, '0', '50'));

            //IMAGE UPLOAD CODE START

            $bio_sorted = str_replace('\r\n', '<br>', $desc);

            $page_overview = "../../../../".strtolower($_SESSION['ADMIN_TYPE'])."s/".$_SESSION['ADMIN_USERKEY']."/".$folder."/includes/overview.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $bio_sorted);

            $sourcePath = $_FILES['eng_cover']['tmp_name'];

                $targetPath = "../../../../".strtolower($_SESSION['ADMIN_TYPE'])."s/".$_SESSION['ADMIN_USERKEY']."/".$folder."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];

                        $page_profile = "../../../../".strtolower($_SESSION['ADMIN_TYPE'])."s/".$_SESSION['ADMIN_USERKEY']."/".$folder."/includes/profile.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                   
                }else {

                    $sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid' ORDER BY ID DESC;";
							$result = mysqli_query($conn, $sql);


							if(mysqli_num_rows($result) == false){
							echo "Page not found in the database!";

							}else{
							while($row = mysqli_fetch_assoc($result)) {
								
                                $targetPath1 = $row['COVER'];
							}
							}
                    
                }
                    
            $userkey = $_SESSION['ADMIN_USERKEY'];
                    //excluding image and password
                    $sql = "UPDATE posts SET DESCRIPTION='$desc',WEBSITE='$website',CATEGORY='$category',COVER='$targetPath1' WHERE CONTENTID='$contentid'";

                        if ($conn->query($sql) === TRUE) {
                        echo '<p class="alert alert-success">Page successfully updated!</p>';

                        } else {
                        echo '<p class="alert alert-warning">Something went wrong!</p>' . $conn->error;
                        }

                        $conn->close();

        

        }



?>