<?php

// SQL to select data
$sql = "SELECT * FROM subscriptions WHERE PLAN = '$plan'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $subscription_status = true;
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $sub_id = $row["ID"];
        $sub_plan = $row["PLAN"];
        $sub_books = $row["BOOKS"];
        $sub_priced = $row["PRICED"];
        $sub_services = $row["SERVICES"];
        $sub_events = $row["EVENTS"];
        $sub_website = $row["WEBSITE"];
        $sub_emails = $row["EMAILS"];
        $sub_analytics = $row["ANALYTICS"];
        $sub_api = $row["API"];
        $sub_push = $row["PUSH"];
        $sub_price = $row["PRICE"];
        $sub_yearly = $row["YEARLY"];
    }
} else {
    $subscription_status = false;
}

?>
