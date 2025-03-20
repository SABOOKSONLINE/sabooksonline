<?php

session_start();

if ($_SESSION['payment'] == 'true') {

    $type = 'Free';
    // You can perform additional actions here if needed
    header("Location: ../backend/scripts/subscription-activate-free");

} else {
    $_SESSION['payment'] = 'false';
    header("Location: https://sabooksonline.co.za/dashboard/service-plan?payment_invalid_plan");
}

$conn->close();

?>
   