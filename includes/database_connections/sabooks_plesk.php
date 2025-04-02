<?php
$servername = "localhost";
/*$username = "root";
$password = "";
$dbh = "sabooksonline";*/

$username = "root";
$password = "root";
$dbh = "Sibusisomanqa_website_plesk";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}
?>