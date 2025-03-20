<?php

       /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/

          //DATABASE CONNECTIONS SCRIPT
        include '../database_connections/sabooks.php';

        $reg_name = mysqli_real_escape_string($conn, $_POST['reg_name']);
        $reg_email = mysqli_real_escape_string($conn, $_POST['reg_email']);
        $reg_number = mysqli_real_escape_string($conn, $_POST['reg_number']);
        $reg_gender = mysqli_real_escape_string($conn, $_POST['reg_gender']);
        $reg_age = mysqli_real_escape_string($conn, $_POST['reg_age']);
        $reg_referal = mysqli_real_escape_string($conn, $_POST['reg_ref']);
        $reg_event = mysqli_real_escape_string($conn, $_POST['reg_event']);
        $id = mysqli_real_escape_string($conn, $_POST['reg_id']);

        //§$userid = substr('0','8', uniqid());


        //IMAGE UPLOAD CODE END

        if(!preg_match('/^[a-zA-Z0-9 ]*$/', $reg_name)){

          echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>You used some invalid characters in your form! Check your <b>Name</b> input</div>";  

        } else {
        
        //TIME VARIABLE
        $current_time = date('l jS \of F Y');

        //INSERT REGISTRATION DATA INTO DATABASE
                    
                if(!filter_var($reg_email, FILTER_VALIDATE_EMAIL)){
                  echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your email address is invalid!</div>";
                }else{

                    $sql = "INSERT INTO rsvp (NAME, USERNUMBER, EMAIL, GENDER, AGE, REGDATE, REFERAL, EVENT) VALUES ('$reg_name','$reg_number','$reg_email','$reg_gender','$reg_age','$current_time','$reg_referal','$reg_event');";

                    if(mysqli_query($conn, $sql)){

                        $sql = "SELECT * FROM events WHERE ID = '$id'";
                        $result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($result) == false){
                        }else{
                            while($row = mysqli_fetch_assoc($result)) {
            
                                $title = ucfirst($row['TITLE']);
                                $id = $row['ID'];
                                $desc = $row['DESCRIPTION'];
                                $date = $row['EVENTDATE'];
                                $time = $row['EVENTTIME'];
                                $address = $row['VENUE'];
                                $contentid = $row['CONTENTID'];
                                $dateposted = $row['DATEPOSTED'];
                                $cover = $row['COVER'];
                                $status = $row['CURRENT'];
                                $userkey = $row['USERKEY'];
                                $type = $row['EVENTTYPE'];
                                $email = $row['EMAIL'];
                                $number = $row['NUMBER'];
                                $link = $row['LINK'];
            
                                }
                            }
                    
                    $message = "Thank you for registering for Event Attendance on our website. See the event details below.";

                    $message .= "<br><br><b>Event Title:</b> ".$title;
                    $message .= "<br><br><b>Event Type:</b> ".$type;
                    $message .= "<br><br><b>Event Venue:</b> ".$address;
                    $message .= "<br><br><b>Email:</b> ".$email;
                    $message .= "<br><br><b>Number:</b> ".$number;
                    $message .= "<br><br><b>Date:</b> ".$time;

                    $button_link = 'https://sabooksonline.co.za/event-details?event='.str_replace(' ','-', $title);
                    $link_text = "See Event Details";

                    include '../templates/email.php';

                    $subject = 'Event Registration For '.ucwords($reg_name).' ';


                    if(mail($reg_email,$subject,$message2,$headers)){

                        $message = "A new event RSVP has been made by <b>".$reg_name."</b> See the details below:";

                        $message .= "<br><br><b>Event Title:</b> ".$title;
                        $message .= "<br><br><b>Event Type:</b> ".$type;
                        $message .= "<br><br><b>Event Venue:</b> ".$address;
                        $message .= "<br><br><b>Email:</b> ".$reg_email;
                        $message .= "<br><br><b>Number:</b> ".$reg_number;
                        $message .= "<br><br><b>Name:</b> ".$reg_name;
                        $message .= "<br><br><b>Gender:</b> ".$reg_gender;
                        $message .= "<br><br><b>Age:</b> ".$reg_age;
                        $message .= "<br><br><b>How did you hear about this event:</b> ".$reg_referal;
                        $message .= "<br><br><b>Date Registered:</b> ".$current_time;
              
                          include '../templates/email.php';
              
                          $subject = 'New Event RSVP By '.ucwords($reg_name).' ';

                          mail($email,$subject,$message2,$headers);
                  
                          echo '<script>window.location.href="../confirm.php?email='.$reg_email.'&message=Your RSVP has been successfully been received";</script>';

                      }
                      
                    }else {
                          echo "<p class='alert alert-warning p-2 mb-3'>Your account could not be created!</p>";
                    }

                }
        }
          

?>