<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
error_reporting(0);
ini_set('display_errors', 0);

// Ensure the user is logged in and the ADMIN_USERKEY is set in the session
if (!isset($_SESSION['ADMIN_USERKEY'])) {
    echo "User is not logged in.";
    exit; // or handle the situation as appropriate
}


$specificUserID = $_SESSION['ADMIN_USERKEY']; // Replace with the specific user ID
//$newStatus = 'Service Locked'; // Replace with the new status value

// Update status for all rows of a specific user except the first few based on the ID column
$newStatus = ($downgrade == true) ? 'Service Locked' : 'Active';

// Tables to update
$tablesToUpdate = array('posts', 'events', 'services');

foreach ($tablesToUpdate as $table) {
    $sql = ($downgrade == true)
        ? "UPDATE $table AS t1
            LEFT JOIN (
                SELECT ID FROM $table
                WHERE USERID = ?
                ORDER BY ID ASC
                LIMIT ?
            ) AS t2
            ON t1.ID = t2.ID
            SET status = ?
            WHERE t1.USERID = ?
            AND t2.ID IS NULL"
        : "UPDATE $table AS t1
            LEFT JOIN (
                SELECT ID FROM $table
                WHERE USERID = ?
                ORDER BY ID ASC
                LIMIT ?
            ) AS t2
            ON t1.ID = t2.ID
            SET status = ?
            WHERE t1.USERID = ?
            AND t2.ID IS NOT NULL";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        
        // Use "siss" for the parameter types
        $stmt->bind_param("siss", $specificUserID, $user_books, $newStatus, $specificUserID);

        if ($stmt->execute()) {


            $activeStatus = 'Active';

            try {
                // Update status for books with non-zero price to 'Service Locked'
                $sql = "UPDATE posts SET STATUS = ? WHERE USERID = ? AND RETAILPRICE = 0";
            
                $stmts = $conn->prepare($sql);
            
                if ($stmts) {
                    // Use "ss" for the parameter types
                    $stmts->bind_param("ss", $activeStatus, $specificUserID);
            
                    if ($stmts->execute()) {
                        //echo 'Books updated successfully!';

                        

                        if($_SESSION['ADMIN_SUBSCRIPTION'] != 'Deluxe'){
                            // Update status for books with non-zero price to 'Service Locked'
                            $activeDisable = 'Service Locked';

                            $sqls = "UPDATE book_stores SET STATUS = ? WHERE USERID = ? ";
                                        
                            $stmtss = $conn->prepare($sqls);

                            $stmtss->bind_param("ss", $activeDisable, $specificUserID);

                            $stmtss->execute();
                        } elseif($_SESSION['ADMIN_SUBSCRIPTION'] == 'Deluxe'){

                            $sqls = "UPDATE book_stores SET STATUS = ? WHERE USERID = ? ";
                                        
                            $stmtss = $conn->prepare($sqls);

                            $stmtss->bind_param("ss", $activeStatus, $specificUserID);

                            $stmtss->execute();
                        }

                    } else {
                        //echo "Error updating books: " . $stmt->error;
                        
                    }
            
                    $stmts->close();
                } else {
                   // echo "Error preparing statement: " . $conn->error;
                   
                }
            } catch (Exception $e) {
                //echo "An error occurred: " . $e->getMessage();
            }

            // Now we can generate an invoice for the new subscription
           // include_once 'generate_invoice.php';
           
                // Update the existing session for the Subscription plan
                $_SESSION['ADMIN_SUBSCRIPTION'] = $subscriptionType;

                

                if($plan == 'Free'){
                    header("Location: ../../../dashboard/service-plan");
                } else {
                    header("Location: ../../../dashboard/service-plan");

                }


        } else {
            echo "Error updating status in $table: " . $stmt->error . "<br>";
        }
    } else {
        echo "Error preparing statement for $table: " . $conn->error . "<br>";
    }
}

?>
