<?php
$servername = "localhost";
/*$username = "root";
$password = "";
$dbh = "sabooksonline";*/

$username = "sabooks_library";
$password = "1m0g7mR3$";
$dbh = "Sibusisomanqa_website_plesk";

// Create connection
$demo_conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($demo_conn->connect_error) {
  die("Connection failed: " . $demo_conn->connect_error);
}
?>