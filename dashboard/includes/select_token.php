<?php // DATABASE CONNECTIONS SCRIPT
                                    include '../includes/database_connections/sabooks.php'; // Connection to the first database

                                   $userid = $_SESSION['ADMIN_USERKEY'];

                                   $payfast_data_show = '<p>The is no active subscription</p>';

                                    $sql = "SELECT * FROM payments WHERE invoice_id = '$userid' ORDER BY payment_id DESC LIMIT 1";
                                    $result = mysqli_query($conn, $sql); // Use the connection to the first database
                                    if (mysqli_num_rows($result) == false) {

                                        echo '<p>The is no active subscription</p>';
                                        
                                    } else {
                                        while ($row = mysqli_fetch_assoc($result)) {

                                             $token = $row['token'];   
                                             
                                        }

                                        $payfast_data_status = true;  
      
                                    }



?>