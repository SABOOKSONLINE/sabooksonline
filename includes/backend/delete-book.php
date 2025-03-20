
<?php
session_start();

//DATABASE CONNECTIONS SCRIPT
include '../database_connections/sabooks.php';

if(!isset($_SESSION['ADMIN_USERKEY'])){
    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You are not logged in!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-listings');},3000);</script>";
    
} else {

    $userkey = $_SESSION['ADMIN_USERKEY'];

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $contentid = mysqli_real_escape_string($conn, $_POST['contentid']);
    $contentlocate = mysqli_real_escape_string($conn, $_POST['locate']);

    $sql = "DELETE FROM posts WHERE CONTENTID ='$contentid' AND USERID = '$userkey';";

    if(mysqli_query($conn, $sql)){

        unlink('../../cms-data/book-covers/'.$contentlocate); 

        /*include '../database_connections/sabooks_plesk.php';

        include_once 'scripts/select/select_website_data.php';

        $customerUsername = $customer_username;
        $customerPassword = $customer_password;

        include 'functions/book_delete.php';*/

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
        
                    include 'functions/book_delete.php';

                } 

            } 

        echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book has been Deleted!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('listings');},3000);</script>";
    }

}


?>
