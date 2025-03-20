<?php


$public_key = "6LcSLLgbAAAAAO4s29wdWKNy4MkhJuWXlNuxW3gz"; /* Your reCaptcha public key */
$private_key = "6LcSLLgbAAAAAHzKookYR16F5kngTrbp-qRvu0T1"; /* Enter your reCaptcha private key */
$url = "https://www.google.com/recaptcha/api/siteverify"; /* Default end-point, please verify this before using it */


/* The response given by the form being submitted */
$response_key = $_POST['g-recaptcha-response'];
/* Send the data to the API for a response */
$response = file_get_contents($url.'?secret='.$private_key.'&response='.$response_key.'&remoteip='.$_SERVER['REMOTE_ADDR']);
/* json decode the response to an object */
$response = json_decode($response);

/* if success */
if($response->success == 1)
{
  $first = $_POST["name"];
  $phone = $_POST["phone"];
$email = $_POST["email"];
$subject = $_POST["subject"];
$message = $_POST["message"];

$content = "
<html>
<head>
<title>Message From The Website</title>
</head>
<body>
<h1>Message From $first $last (From Website)</h1>
<p>Message: $message</p>
<table>
<tr>
<th>Name</th>
<th>Subject</th>
<th>Email</th>
<th>Phone</th>
</tr>
<tr>
<td>$first</td>
<td>$subject</td>
<td>$email</td>
<td>$phone</td>

</tr>
</table>

<h3><a href='mailto:$email'>Send Reply Message</a></h3>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <website@sabooksonline.co.za>' . "\r\n";
$headers .= 'Cc: ' . "\r\n";

//mail($to,$subject,$message,$headers);

$toEmail = "admin@sabooksonline.co.za";
mail($toEmail, $subject, $content, $headers);

echo '<p class="alert alert-success">Your message request has been sent!</p>';
}
else
{
echo '<p class="alert alert-info">Please verify that you are not a robot!</p>';
}





?>
