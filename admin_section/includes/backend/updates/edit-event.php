<?php
    
      
            //DATABASE CONNECTIONS SCRIPT
        include '../../database_connections/sabooks.php';

        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $time = mysqli_real_escape_string($conn, $_POST['time']);
        $venue = mysqli_real_escape_string($conn, $_POST['venue']);
        $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
        $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];
    
            $title_show = $title;
            $title = str_replace("'", "`", $title);
           
            //$date = date("l jS \of F Y h:i:s A");
            $date = date("l, jS \of F Y");
    
            $sourcePath = $_FILES['eng_cover']['tmp_name'];

            $folder = strtolower(str_replace(' ', '-', $title));

            //IMAGE UPLOAD CODE START

            $bio_sorted = str_replace('\r\n', '', $desc);

            $page_overview = "../../../../events/".$contentid."/includes/overview.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $bio_sorted);

                $page_overview = "../../../../events/".$contentid."/includes/title.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $title);

                $page_overview = "../../../../events/".$contentid."/includes/date.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $date);

                $page_overview = "../../../../events/".$contentid."/includes/time.php";
                $myfile = fopen($page_overview , "w");
                fwrite($myfile, $time);

            

            $sourcePath = $_FILES['eng_cover']['tmp_name'];

                $targetPath = "../../../../events/".$contentid."/".$_FILES['eng_cover']['name'];
                $targetPath1 = "../".$_FILES['eng_cover']['name'];
                if(move_uploaded_file($sourcePath,$targetPath)){
                    $targetPath1 = $_FILES['eng_cover']['name'];

                        $page_profile = "../../../../events/".$contentid."/includes/cover.php";
                        $myfile = fopen($page_profile , "w");
                        fwrite($myfile, $targetPath1);
                   
                }else {

                    $sql = "SELECT * FROM events WHERE CONTENTID = '$contentid' ORDER BY ID DESC;";
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
                    $sql = "UPDATE events SET COVER = '$targetPath1', TITLE = '$title', DESCRIPTION = '$desc', EVENTDATE = '$date', EVENTTIME = '$time', VENUE = '$venue', KEYWORDS = '$keywords' WHERE CONTENTID='$contentid'";

                        if ($conn->query($sql) === TRUE) {
                        echo '<p class="alert alert-success">Page successfully updated!</p>';

                        } else {
                        echo '<p class="alert alert-warning">Something went wrong!</p>' . $conn->error;
                        }

                        $conn->close();

 

?>