<?php

    /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
    // Plesk API credentials
    $username_plesk = 'sabookso';
    $password_plesk = 'lOpF5s0cB~28&Q';
    $apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

    // Email address name to set the password for
    $emailName = $_POST['domain-name']; // Replace with the actual email address name

    $date = $current_time = date('l jS \of F Y');

    function generateRandomPassword($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-={}[]|:;"<>,.?/~';
        $password_gen = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password_gen .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $password_gen;
    }

    // Generate a random password with 8 characters
    $randomPassword = generateRandomPassword();
    //echo $randomPassword;

    // New password for the email address
    $newPassword = 'Sabo'.$randomPassword;


    // Assuming you have defined the USERKEY value you want to use for the query
    $userkey = $_SESSION['ADMIN_USERKEY'];

    // Prepare the SELECT statement
    $query = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";

    // Prepare and bind the parameter
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $userkey);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Check if any rows are found
    if ($result->num_rows > 0) {
        // Fetch and display the rows
        while ($row = $result->fetch_assoc()) {
            $domain_id = $row['DOMAIN_ID'];
            $domain_name = $row['DOMAIN'];
        }

        $domain = true;

    } else {
        $domain = false;
    }

    //Create the user first account on plesk

    include_once '../website/create_email.php';

    if($client_success == false){
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Could not create email, Email exists!',showConfirmButton: false,timer: 6000});</script>";
    } else {

            $subscriptionID = (int)$xmlResponsePassword->id;

            // Prepare the INSERT statement
            $query = "INSERT INTO plesk_emails (EMAIL_ID, EMAIL_ACCOUNT, EMAIL_PASSWORD, EMAIL_DATE, EMAIL_USERID) 
            VALUES (?, ?, ?, ?, ?)";

            $emailName_full = $emailName.'@'.$domain_name;
       
            // Prepare and bind the parameters
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssss', $subscriptionID, $emailName_full, $newPassword, $date, $userkey);
       
       
            // Execute the statement
            if ($stmt->execute()) {
                //echo "Email Created Successfully!";
       
               // mail($customerEmail, $subject, $logMessage, $headers);
       
                echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your email has been created!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-websites');},3000);</script>";

            } else {

                echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Could not create email, please try again!',showConfirmButton: false,timer: 6000});</script>";

            }
       
            // Close the statement and the connection
            $stmt->close();
            $mysqli->close();

        
    }

?>