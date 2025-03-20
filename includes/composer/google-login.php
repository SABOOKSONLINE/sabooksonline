<?php
session_start();

//DATABASE CONNECTIONS SCRIPT
include '../includes/database_connections/sabooks.php';

$log_email = $reg_email; // Assuming you're retrieving log_email from a form
$log_pwd2 = '12345nola6'; // Preset password for testing

if (!filter_var($log_email, FILTER_VALIDATE_EMAIL)) {
    echo "<div class='alert alert-warning'>Your email is invalid!</div>";
} else {
    $sql = "SELECT * FROM users WHERE email = '$log_email';"; // Assuming 'email' is the column name for email in your table

    $result = mysqli_query($conn, $sql);

    if (!mysqli_num_rows($result)) {
        echo "<center class='alert alert-warning'>Email Not Found!</center>";
    } else {
        $row = mysqli_fetch_assoc($result);
        $dehash = $row['password'];

        $status = $row['status'];

        if ($status !== "Verified") {
            echo "<center class='alert alert-warning'>Your account needs to be confirmed before you can login. Please check your emails for a confirmation email with a verification link.</center>";
        } else {
            if (!password_verify($log_pwd2, $dehash)) {
                // Password Incorrect
                // Password Correct - Set session variables and redirect to dashboard
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['number'] = $row['number'];
                $_SESSION['profile_image'] = $row['profile_image'];
                $_SESSION['status'] = $row['status'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['company_reg_number'] = $row['company_reg_number'];
                $_SESSION['address'] = $row['address'];

                echo '<script>window.location.href="https://miningpartnership.co.za/dashboard/";</script>';
            } else {
                // Password Correct - Set session variables and redirect to dashboard
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['number'] = $row['number'];
                $_SESSION['profile_image'] = $row['profile_image'];
                $_SESSION['status'] = $row['status'];
				$_SESSION['password'] = $row['password'];
				$_SESSION['company_reg_number'] = $row['company_reg_number'];
                $_SESSION['address'] = $row['address'];

                echo '<script>window.location.href="https://miningpartnership.co.za/dashboard/";</script>';
            }
        }
    }
}
?>
