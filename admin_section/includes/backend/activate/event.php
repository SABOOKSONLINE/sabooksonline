<?php
   
             //DATABASE CONNECTIONS SCRIPT
            include '../../../includes/database_connections/sabooks.php';

            //VARIABLES DECLARED

            $contentid = mysqli_real_escape_string($conn, $_GET['contentid']);

                    $sql = "UPDATE events SET CURRENT='Active' WHERE CONTENTID='$contentid';";

                        if ($conn->query($sql) === TRUE) {

                            $page = $_GET['page'];

                            header("Location: ../../../event-pending.php?status1=success");

                        } else {
                            header("Location: ../../../event-pending.php?status1=failed");
                        }

                        $conn->close();

?>