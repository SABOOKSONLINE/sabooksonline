<?php
$servername = "localhost";
/*$username = "root";
$password = "";
$dbh = "sabooksonline";*/

$username = "sabooks_library";
// $username = "root";

$password = "1m0g7mR3$";
// $password = "root";

$dbh = "Sibusisomanqa_website_plesk";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}
?>