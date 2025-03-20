<?php

session_start();

// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('api_key.txt');
include 'api_fetch.php';

for ($i = 0; $i < count($title); $i++):

$fileContent = file_get_contents('api_key.txt');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include "db.php";

	$f_name = $_POST["f_name"];
	$l_name = $_POST["l_name"];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$repassword = $_POST['repassword'];
	$mobile = $_POST['mobile'];
	$address1 = $_POST['address1'];
	$address2 = '';
	$name = "/^[a-zA-Z ]+$/";
	$emailValidation = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$/";
	$number = "/^[0-9]+$/";

if(empty($f_name) || empty($l_name) || empty($email) || empty($password) || empty($repassword) ||
	empty($mobile) || empty($address1)){
		
		echo "
			<div class='alert alert-warning'>
				PLease Fill all fields..!
			</div>
		";
		exit();
	} else {
		if(!preg_match($name,$f_name)){
		echo "
			<div class='alert alert-warning'>
				You used invalid characters, $f_name is not valid...!
			</div>
		";
		exit();
	}
	if(!preg_match($name,$l_name)){
		echo "
            <div class='alert alert-warning'>
                You used invalid characters, $l_name is not valid...!
            </div>
		";
		exit();
	}
	
	if(strlen($password) < 9 ){
		echo "
			<div class='alert alert-warning'>
				Your Password is weak, Try adding special characters.
			</div>
		";
		exit();
	}
	if(strlen($repassword) < 9 ){
		echo "
            <div class='alert alert-warning'>
                Your Password is weak, Try adding special characters.
            </div>
		";
		exit();
	}
	if($password != $repassword){
		echo "
			<div class='alert alert-warning'>
				Passwords do not match!
			</div>
		";
	}
	if(!preg_match($number,$mobile)){
		echo "
			<div class='alert alert-warning'>
				Mobile number $mobile is not valid
			</div>
		";
		exit();
	}
	if(!(strlen($mobile) == 10)){
		echo "
			<div class='alert alert-warning'>
				Mobile number must be 10 digits.
			</div>
		";
		exit();
	}
	//existing email address in our database
	$sql = "SELECT user_id FROM user_info WHERE email = '$email' LIMIT 1" ;
	$check_query = mysqli_query($con,$sql);
	$count_email = mysqli_num_rows($check_query);
	if($count_email > 0){
		echo "
			<div class='alert alert-danger'>
				Email Address is already available Try Another email address.
			</div>
		";
		exit();
	} else {

        $password = password_hash($password, PASSWORD_DEFAULT);
		
		$sql = "INSERT INTO `user_info` 
		(`first_name`, `last_name`, `email`, 
		`password`, `mobile`, `address1`, `address2`) 
		VALUES ('$f_name', '$l_name', '$email', 
		'$password', '$mobile', '$address1', '$address2')";


		//$run_query = mysqli_query($con,$sql);
		//$_SESSION["uid"] = mysqli_insert_id($con);
		//$_SESSION["name"] = $f_name;
		//$ip_add = getenv("REMOTE_ADDR");
		//$sql = "UPDATE cart SET user_id = '$_SESSION[uid]' WHERE ip_add='$ip_add' AND user_id = -1";
		if(mysqli_query($con,$sql)){

            //echo '<div class="alert alert-success">You account has been successfully been created, You may now login.</div>';
             
            $message = "Thank you for creating an account on our website. You may start your shopping by clicking the button below.";

            $button_link = 'https://'.$domain[$i].'/#shop?email';
            $link_text = "Start Shopping";

            $reg_name = $f_name;
            $reg_email = $email;


            include 'templates/email.php';

            $subject = 'Confirmation Of Registration For '.ucwords($f_name).' ';

           $sql = "SELECT * FROM user_info WHERE email = '$reg_email';";

            if(!mysqli_num_rows(mysqli_query($con, $sql))){
            echo "<center class='alert alert-warning'>Email Not Found!</center>";
            }else {

                $row = mysqli_fetch_assoc(mysqli_query($con, $sql));

                $_SESSION['user_id'] = $row["user_id"];
                $_SESSION['first_name'] = $row["first_name"];

                mail($email,$subject,$message2,$headers);
        
                echo '<script>window.location.href="my-orders?login=success&'.$_SESSION['first_name'].'";</script>';

            }

            

		}
	}
	}

endfor;

?>






















































