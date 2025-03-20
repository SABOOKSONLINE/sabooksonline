<?php
session_start();

// Include the database connection script
include '../includes/database_connections/sabooks.php';

// Get user data from the registration form or any other source
$reg_name = $user->getName();
$reg_email = $user->getEmail();
$reg_password = '01234nola'; // Set a preset password for testing
$reg_confirm_password = '01234nola'; // Set a preset password for testing
$reg_type = 'Free'; // Assuming you have a default user type

// Assuming you're getting the user's profile image using $user->getPicture() method
$profileimage = $user->getPicture();

// Validate and sanitize user input
if (!preg_match('/^[a-zA-Z0-9 ]*$/', $reg_name)) {
    echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Invalid characters in your name!</div>";
} else {
    if (!filter_var($reg_email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your email address is invalid!</div>";
    } else {
        if ($reg_password != $reg_confirm_password) {
            echo "<div class='alert text-center bg-warning p-3 mb-3 mt-4'>Your passwords don't match!</div>";
        } else {
            // Check if the email already exists in the database
            $sql = "SELECT * FROM users WHERE email = '$reg_email';";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Email already exists, handle accordingly (redirect or display an error)
                // Here, I'm assuming you have a separate script for handling Google login, so I included it here
                include 'google-login.php';
            } else {
                // Email does not exist, proceed with user registration
                $reg_password = password_hash($reg_password, PASSWORD_DEFAULT);
                $userkey = substr(uniqid(), '0', '9') . time(); // Generating a unique user key

                // Insert user data into the database
				
				$sql = "INSERT INTO users (name, email, number, password, profile_image, status, verify_code, id_number, address, company_reg_number) VALUES ('$reg_name', '$reg_email', '$reg_number', '$reg_password', '$profileimage', 'Verified', '', '', '', '');";
                if (mysqli_query($conn, $sql)) {
                    // Registration successful, send confirmation email
                    $current_time = date('l jS \of F Y');
                    $veri_link = "https://miningpartnership.co.za/dashboard/verify?verifyid=$userkey"; // Assuming this is the verification link
                    
                    // Compose and send email
                    $message = "A new account has just been created. Review the details below:\n\n";
                    $message .= "Name: $reg_name\n";
                    $message .= "Email: $reg_email\n";
                    $message .= "Date: $current_time\n\n";
                    
                    $subject = 'New Account Creation for ' . ucwords($reg_name);
                    mail($reg_email, $subject, $message);

                    // Redirect or include the Google login script based on your requirements
                    include 'google-login.php';
                } else {
                    // Registration failed, display an error message
                    echo "<p class='alert alert-warning p-2 mb-3'>Your account could not be created!</p>";
                }
            }
        }
    }
}
?>
