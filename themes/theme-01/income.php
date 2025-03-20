<?php
    include 'includes/db.php';

    // Fetch and sum the total from the "invoices" table
    $sql = "SELECT SUM(invoice_total) AS totalSum FROM invoices";
    $result = $con->query($sql);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();  
        $totalSum = $row['totalSum'];
        echo $totalSum;
    } else {
        echo "No invoices found.";
    }

    // Close the connection
    $con->close();
?>