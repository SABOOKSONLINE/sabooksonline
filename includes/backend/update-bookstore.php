<?php
        session_start();
        //The registartion code begins

        ini_set('display_errors', 1);
          ini_set('display_startup_errors', 1);
          error_reporting(E_ALL);
        
        //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        //$storeID = $_POST['store_id'];
        $storeName = $_POST['store_name'];
        $storeEmail = $_POST['store_email'];
        $storeNumber = $_POST['store_number'];
        $storeAddress = $_POST['store_address'];
        $storeProvince = $_POST['store_province'];
        $storeDesc = $_POST['store_desc'];
        $storeTimes = implode(' - ', $_POST['store_times']);
        $storeAmnemities = implode(', ', $_POST['store_amnemities']);
        $storeLogo = $_FILES['store_logo']['name'];

        $userkey = $_SESSION['ADMIN_USERKEY'];
        
        // Perform validation and sanitization on user inputs here

        //TIME VARIABLE
        $d=strtotime("10:30pm April 15 2021");
        $current_time = date('l jS \of F Y');

        $logoUploadPath = '../../../../cms-data/profile-images/' . $storeLogo;

         if(move_uploaded_file($_FILES['store_logo']['tmp_name'], $logoUploadPath)){

            //$profile = $targetPath;

            $sql = "UPDATE book_stores SET STORE_LOGO ='$logoUploadPath'  WHERE USERID='$userkey'";

            mysqli_query($conn, $sql);

        }

        // Update store data in the database using prepared statements
        $sql = "UPDATE book_stores SET STORE_NAME = ?, STORE_EMAIL = ?, STORE_NUMBER = ?, STORE_ADDRESS = ?, STORE_DESC = ?, STORE_TIMES = ?, STORE_AMNEMITIES = ?, STORE_PROVINCE = ? WHERE USERID = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $storeName, $storeEmail, $storeNumber, $storeAddress, $storeDesc, $storeTimes, $storeAmnemities,$storeProvince, $userkey);
        
        if ($stmt->execute()) {
            echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book store page has been updated!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('bookstore');},3000);</script>";
        } else {
            echo "Error updating data: " . $stmt->error;

            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your Book store could not be updated.',showConfirmButton: false,timer: 3000});";
        }
    

?>