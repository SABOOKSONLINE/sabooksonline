<?php
        
        //DATABASE CONNECTIONS SCRIPT
        include '../../dbh.inc.php';

       
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $link = mysqli_real_escape_string($conn, $_POST['title']);

        $sourcePath = $_FILES['eng_cover']['tmp_name'];

        
        $targetPath = "../../../../ads/".$_FILES['eng_cover']['name'];

        $targetPath1 = $_FILES['eng_cover']['name'];
        if(move_uploaded_file($sourcePath,$targetPath)){
            $targetPath1 = $_FILES['eng_cover']['name'];

            $page_profile = "../../../../ads/".$category."-link.php";
            $myfile = fopen($page_profile , "w");
            fwrite($myfile, $link);

            $page_profile = "../../../../ads/".$category.".php";
            $myfile = fopen($page_profile , "w");
            fwrite($myfile, $targetPath1);

            echo "Product Has Been Published!";

           
         } 



      


        //image upload CODESET
        /*$targetPath = "../../../../posts/".$_SESSION['ADMIN_USERKEY']."/".$_FILES['eng_cover']['name'];
        $targetPath1 = "../".$_FILES['eng_cover']['name'];
        if(move_uploaded_file($sourcePath,$targetPath)){



        }else{
            echo 'Image not uploaded';
        }*/




     
?>