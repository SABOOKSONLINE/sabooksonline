<?php

                include '../includes/database_connections/sabooks.php';

                $userid = $_SESSION['ADMIN_USERKEY']; // Replace with the specific user_id you want to query

                // Select the last entry for the specified user_id
                $sql = "SELECT sa.*, p.token
                FROM suspended_accounts sa
                INNER JOIN payments p ON sa.user_id = p.invoice_id
                WHERE sa.user_id = ?
                ORDER BY sa.suspended_at DESC
                LIMIT 1";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userid); // Assuming user_id is an integer, use "i" as the type

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {   
                    // Fetch the last entry
                     $row = $result->fetch_assoc();

                     // Retrieve and display the "next_invoice_date" column from the last entry
                     $reason = $row['reason'];
                     $suspended_at = $row['suspended_at'];
                     $token = $row['token'];
   
                     // Replace 'your_token' with the actual token and 'your_return_url' with the return URL
                     //$token = '69b57482-832f-4d0a-a88c-14ad76f41d69'; 
                     $returnUrl = 'https://sabooksonline.co.za/sabo/service-plan-update';
 
                     // PayFast update card details URL
                     $updateCardUrl = "https://www.payfast.co.za/eng/recurring/update/{$token}?return={$returnUrl}";
                    
                   

                    echo '<div class="alert alert-danger w-100">
                        <h6>Your account has been suspended due to being '.$reason.', <a href="'.$updateCardUrl.'"><b>Update Your Payment Details</b> <i class="fa fa-external-link"></i></a></h6>
                        <!--<h5><b class="text-success">Suspension Date: '.$suspended_at.'</b></h5>-->
                    </div>';

                    // Perform a redirect
                   // header("Location: $updateCardUrl");

                } else {

                    /*echo '<div class="alert alert-danger w-100">
                        <h6>Your account has been De-Activated due to being Overdue, <a href="https://sabooksonline.co.za/sabo/plan"><b>Subscribe to our plans</b> <i class="fa fa-external-link"></i></a></h6>
                        <!--<h5><b class="text-success">Suspension Date: '.$suspended_at.'</b></h5>-->
                    </div>';*/
                       
                }
                // Close the database connection
                ?>