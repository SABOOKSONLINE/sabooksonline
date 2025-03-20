<?php
    session_start();
      
            //DATABASE CONNECTIONS SCRIPT
        include '../../database_connections/sabooks.php';

        $userkey = $_SESSION['ADMIN_USERKEY'];
        $usertype = strtolower($_SESSION['ADMIN_TYPE']);
        

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $website = mysqli_real_escape_string($conn, $_POST['website']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
        $date_published = mysqli_real_escape_string($conn, $_POST['date_published']);
        $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];
    
            $title_show = $title;
            $title = str_replace("'", "`", $title);
           
            //$date = date("l jS \of F Y h:i:s A");
            $date = date("l, jS \of F Y");
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];

            $folder = strtolower(str_replace(' ', '-', $title));

            $desc_database = substr(strip_tags($desc), '0', '100');

            $desc = str_replace("\\","", $desc);
            $title = str_replace("\\","", $title);
            $title = str_replace("'","`", $title);
            $desc = str_replace("'","`", $desc);

            //IMAGE UPLOAD CODE START

            $bio_sorted = str_replace('\r\n', '', $desc);
            $page_overview = "../../../../books/".$contentid."/includes/overview.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $desc);

            $page_overview = "../../../../books/".$contentid."/includes/keywords.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $keywords);

            $page_overview = "../../../../books/".$contentid."/includes/website.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $website);

            $page_overview = "../../../../books/".$contentid."/includes/date-published.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $date_published);

            $page_overview = "../../../../books/".$contentid."/includes/category.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $category);

            $page_overview = "../../../../books/".$contentid."/includes/title.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $title);

            $page_overview = "../../../../books/".$contentid."/includes/isbn.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $isbn);

            $page_overview = "../../../../books/".$contentid."/includes/price.php";
            $myfile = fopen($page_overview , "w");
            fwrite($myfile, $price);

            

            $sourcePath = $_FILES['eng_cover']['tmp_name'];

                $targetPath = "../../../../books/".$contentid."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];

                        $page_profile = "../../../../books/".$contentid."/includes/cover.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                   
                }else {

                    $sql = "SELECT * FROM posts WHERE CONTENTID = '$contentid';";
							$result = mysqli_query($conn, $sql);


							if(mysqli_num_rows($result) == false){
							echo "Page not found in the database!";

							}else{
							while($row = mysqli_fetch_assoc($result)) {
								
                                $targetPath1 = $row['COVER'];
							}
							}
                    
                }
                    
                    //excluding image and password
                    $sql = "UPDATE posts SET COVER = '$targetPath1', TITLE = '$title', DESCRIPTION = '$desc', CATEGORY = '$category', WEBSITE = '$website', ISBN = '$isbn', KEYWORDS = '$keywords', RETAILPRICE = '$price' WHERE CONTENTID='$contentid'";

                        if ($conn->query($sql) === TRUE) {
                        echo '<p class="alert alert-success">Page successfully updated!</p>';

                        } else {
                        echo '<p class="alert alert-warning">Something went wrong!</p>' . $conn->error;
                        }

                        $conn->close();

 

?>