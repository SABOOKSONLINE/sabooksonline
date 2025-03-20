<?php

// API SCRIPT FOR FETCHING DATA FROM SABOOKS ONLINE
$fileContent = file_get_contents('includes/api_key.txt');
include 'includes/api_fetch.php';

for ($i = 0; $i < count($title); $i++):

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
$headers .= 'From: <website@'.$domain[$i].'>' . "\r\n";
$headers .= 'Cc: ' . "\r\n";

//mail($to,$subject,$message,$headers);

$toEmail = $email[$i];
if(mail($toEmail, $subject, $content, $headers)){
    echo '<p class="alert alert-success mt-3">Your message has been successfully been sent.</p>';
}

endfor;

?>
