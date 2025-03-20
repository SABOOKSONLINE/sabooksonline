<?php
        session_start();
        //The registartion code begins
        
        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $userkey = $_SESSION['ADMIN_USERKEY'];
        $usertype = strtolower($_SESSION['ADMIN_TYPE']);
        $name = strtolower($_SESSION['ADMIN_NAME']);        

		$reg_name = strtolower($_SESSION['ADMIN_NAME']);
		$reg_email = strtolower($_SESSION['ADMIN_EMAIL']);

        $title = mysqli_real_escape_string($conn, $_POST['book_title']);
        $category = mysqli_real_escape_string($conn, $_POST['book_category']);
        $website = mysqli_real_escape_string($conn, $_POST['book_website']);
        $desc = mysqli_real_escape_string($conn, $_POST['book_desc']);
        $price = mysqli_real_escape_string($conn, $_POST['book_price']);
        $isbn = mysqli_real_escape_string($conn, $_POST['book_isbn']);
        $profile = mysqli_real_escape_string($conn, $_POST['book_cover']);
        $contentid = mysqli_real_escape_string($conn, $_POST['book_id']);
        $stock = mysqli_real_escape_string($conn, $_POST['stock']);
        $authors = mysqli_real_escape_string($conn, $_POST['author']);  

         //TIME VARIABLE
        $d=strtotime("10:30pm April 15 2021");
        $current_time = date('l jS \of F Y');



        $title_show = $title;
       
        //$title = preg_replace("'", "`", $title);

        $desc = str_replace("\\","", $desc);
        $title = str_replace("\\","", $title);
        $title = str_replace("'","`", $title);

       // include '../../templates/pages/user-page.php';

        $folder = strtolower(str_replace(' ', '-', $title));

        $desc_database = substr(strip_tags($desc), '0', '100');

        $desc = str_replace("\\","", $desc);
        $title = str_replace("\\","", $title);
        $title = str_replace("'","`", $title);
        $desc = str_replace("'","`", $desc);        
		
		$category = str_replace("'","`", $category);
 		$website = str_replace("'","`", $website);        
		$price = str_replace("'","`", $price);
 		$isbn = str_replace("'","`", $isbn);        


        $type = $_SESSION["ADMIN_TYPE"];

         //IMAGE UPLOAD CODE START

        $sourcePath = $_FILES['book_cover']['tmp_name'];

        $extension = pathinfo($_FILES["book_cover"]["name"], PATHINFO_EXTENSION);

        $targetPath = "../../cms-data/book-covers/".$contentid.".".$extension;

        $targetPath1 = $contentid.".".$extension;

         //IMAGE UPLOAD CODE END

         if(move_uploaded_file($sourcePath,$targetPath)){
            $profile = $targetPath;

            $sql = "UPDATE posts SET COVER='$profile'  WHERE USERID='$userkey' AND CONTENTID='$contentid'";

            mysqli_query($conn, $sql);

        } 
		

         $sql = "UPDATE posts SET TITLE='$title',CATEGORY='$category',WEBSITE='$website',DESCRIPTION='$desc',COVER='$profile' ,DATEPOSTED='$current_time',ISBN='$isbn',RETAILPRICE='$price',STOCK='$stock',AUTHORS='$authors',PUBLISHER='$name' WHERE USERID='$userkey' AND CONTENTID='$contentid'";

    
        if(mysqli_query($conn, $sql)){

            include '../database_connections/sabooks_plesk.php';

            /*include_once 'scripts/select/select_website_data.php';

            $customerUsername = $customer_username;
            $customerPassword = $customer_password;

            include_once 'functions/book_transfer.php';*/


            include '../database_connections/sabooks_plesk.php';

            // Prepare and execute the SELECT query
            $query = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";
            $stmt = $mysqli->prepare($query);

            if ($stmt) {
                // Bind the user key parameter
                $stmt->bind_param("s", $userkey);

                // Execute the query
                $stmt->execute();

                // Get the result set
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Data is found, execute your code here
                    include_once 'scripts/select/select_website_data.php';

                    $customerUsername = $customer_username;
                    $customerPassword = $customer_password;
        
                    include_once 'functions/book_transfer.php';

                } 

            } 
                            
            echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book has been updated!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('listings');},3000);</script>";

         }else {

            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Could not update the book!',showConfirmButton: false,timer: 6000});</script>";

        }
                       

?>