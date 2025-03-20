<?php

// Assuming you have a database connection established
$user_id = $_SESSION['ADMIN_USERKEY']; // Replace with the actual user ID
$user_subscription = $_SESSION['ADMIN_SUBSCRIPTION']; // Replace with the actual user subscription
$retailprice = 'Active';

// Fetch user's subscription plan and allowed book count
$sql_subscription = "SELECT EVENTS FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    $books_allowed = $row_subscription["EVENTS"];

    // Count the amount of books the user has uploaded
    $sql_count_books = "SELECT * FROM events WHERE USERID = ? AND STATUS = ?";
    $stmt_count_books = $conn->prepare($sql_count_books);
    $stmt_count_books->bind_param("ss", $user_id, $retailprice);
    $stmt_count_books->execute();
    $result_count_books = $stmt_count_books->get_result();
    
    $uploaded_books = $result_count_books->num_rows;

    if ($uploaded_books < $books_allowed) {
        $remainingUploads = $books_allowed - $uploaded_books;

        
        $sql = "INSERT INTO events (TITLE, DESCRIPTION, EVENTDATE, EVENTTIME, VENUE, KEYWORDS, CONTENTID, DATEPOSTED, COVER, STATUS, USERID, EVENTTYPE, EMAIL, NUMBER, LINK, DURATION, PROVINCE, LATITUDE, LONGITUDE, EVENTEND, TIMEEND) VALUES ('$event_title','$event_desc','$event_date_start','$event_start_time','$event_address','$reg_services_str','$contentid','$current_time','$targetPath1','$status', '$userkey', '$event_type_str', '$event_email', '$event_number', '$event_end_time', '$event_days', '$event_province', '$latitude', '$longitude', '$event_date_end', TIMEEND='$event_end_time');";

        if(mysqli_query($conn, $sql)){


          $amount = 10 * $event_days;

          $invoice_number = $userid.substr(uniqid(), 0, 5);

          $contentid = $invoice_number;

          $current_time = date('l jS \of F Y');

          $sub_type = $subscription;

          $payment_status = "Unpaid";

          $payment_method = "Online";

          //include 'functions/insert-invoice.php';


          $message = "A new Event has just been created for under your account. Your Event duration is ".$event_days." Days.";
              $message .= "<br><br><b>Event Title:</b> ".$event_title;
              $message .= "<br><br><b>Event Type:</b> ".$event_type_str;
              $message .= "<br><br><b>Event Venue:</b> ".$event_address;
              $message .= "<br><br><b>Email:</b> ".$event_email;
              $message .= "<br><br><b>Number:</b> ".$event_number;
              $message .= "<br><br><b>Date:</b> ".$current_time;
              $message .= "<br><br><b>Advert Duration:</b> ".$event_days." Days";

          $button_link = "https://sabooksonline.co.za/dashboard-invoices";
          $link_text = "View Invoices";
          
          $sub_type = $subscription;
          
          $subject = 'Event Details For - '.$event_title.' ';
          
          //include '../functions/invoicing/multiuse.php';
          
          include '../templates/email.php';

        if(mail($reg_email,$subject,$message2,$headers)){
              
              //$message = "A new Event has just been created for under your account. Your Event duration is ".$event_days." Days, The total cost is R".$amount.".";

              $message = "A new Event has just been created for under your account. Your Event duration is ".$event_days." Days.";
              $message .= "<br><br><b>Event Title:</b> ".$event_title;
              $message .= "<br><br><b>Event Type:</b> ".$event_type;
              $message .= "<br><br><b>Event Venue:</b> ".$event_address;
              $message .= "<br><br><b>Email:</b> ".$event_email;
              $message .= "<br><br><b>Number:</b> ".$event_number;
              $message .= "<br><br><b>Date:</b> ".$current_time;
              $message .= "<br><br><b>Advert Duration:</b> ".$event_days." Days";

              $button_link = "https://sabooksonline.co.za/admin_section";
              $link_text = "View Your Events Now";
  
              include '../templates/email.php';
  
              $subject = 'New Event Creation For '.ucwords($event_title).' ';

             // mail('emmanuel@sabooksonline.co.za',$subject,$message2,$headers);
      
             echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Event with title <b>".$event_title."</b> has been uploaded!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('events');},3000);</script>";

          } else {
            echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Could not send the email!',showConfirmButton: false,timer: 3000});";
          }

          

        }else {
            echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Your account could not be created!',showConfirmButton: false,timer: 3000});";
        }

       // echo "You are allowed to upload $remainingUploads more book(s).";
        // You can include your book uploading code here
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You have reached your event upload limit!',showConfirmButton: false,timer: 3000});";
    }
} else {
    echo "Subscription plan not found.";
}

$stmt_subscription->close();
$stmt_count_books->close();

?>