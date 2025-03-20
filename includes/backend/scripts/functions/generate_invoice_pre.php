
<?php

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

    $plan = $subscriptionType;
    //$duration = intval($_GET['duration']);
    $duration = 1;

    // Calculate the pro-rata factor based on the user's current date and month
    $currentDay = date('j'); // Day of the month (1-31)
    $daysInMonth = date('t'); // Total days in the current month
    $proRataFactor = $currentDay / $daysInMonth; // Pro-rata factor based on current day and month

    $proRataFactor = 1 - $proRataFactor;

    //include '../../../database_connections/sabooks.php';

    $toEmail = "emmanuel@oner.co.za";
    $subject = "Invoice Generated";
    $message = "An invoice has been generated for the subscription plan: $plan. Duration: $duration months.";
    $headers = "From: info@sabooksonline.co.za";

    $subscriptionPrices = [];
    $fetchPricesSql = "SELECT plan, price FROM subscriptions";
    $result = $conn->query($fetchPricesSql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subscriptionPrices[$row['plan']] = $row['price'];
        }
    }

    $result->close();

    if (isset($subscriptionPrices[$plan])) {
        $pricePerMonth = $subscriptionPrices[$plan];
        
        // Calculate the pro-rata total price based on the user's current date and month
        //$totalPrice = ($pricePerMonth * $duration) * $proRataFactor;
        $totalPrice = $pricePerMonth * $proRataFactor;

        function generateInvoiceNumber($conn) {
            // Retrieve the last used invoice number from the database
            $getLastInvoiceSql = "SELECT last_invoice_number FROM invoice_sequence";
            $result = $conn->query($getLastInvoiceSql);
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $lastInvoiceNumber = $row['last_invoice_number'];
            } else {
                $lastInvoiceNumber = 0; // Initial value if no record is found
            }
        
            $newInvoiceNumber = $lastInvoiceNumber + 1;
        
            // Update the last used invoice number in the database
            $updateLastInvoiceSql = "UPDATE invoice_sequence SET last_invoice_number = ?";
            $updateStmt = $conn->prepare($updateLastInvoiceSql);
            $updateStmt->bind_param("i", $newInvoiceNumber);
            $updateStmt->execute();
            $updateStmt->close();
        
            return $newInvoiceNumber;
        }

        // Generate an invoice number
        $invoiceNumber = generateInvoiceNumber($conn);
        $invoiceDate = date('Y-m-d H:i:s');
        $invoiceUser = $_SESSION['ADMIN_USERKEY'];
        $invoiceMethod = 'Online Payment';
        $invoiceStatus = 'Pending';
        $invoiceDueDate = date('Y-m-d', strtotime('+7 days')); // Due date 7 days from now

        /*$insertSql = "INSERT INTO invoices (INVOICE_NUMBER, INVOICE_DATE, INVOICE_DUE, INVOICE_USER, INVOICE_SUBSCRIPTION, INVOICE_AMOUNT, INVOICE_METHOD, INVOICE_STATUS, INVOICE_DATEFOR) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("sssssisss", $invoiceNumber, $invoiceDate, $invoiceDueDate, $invoiceUser, $plan, $totalPrice, $invoiceMethod, $invoiceStatus, $invoiceDueDate);

        if ($stmt->execute()) {
            
        } else {
            echo "Error inserting invoice: " . $conn->error;
        }*/

        //$stmt->close();
    } else {
        echo "Invalid subscription plan.";
    }

?>

