<?php
$servername = "localhost";
/*$username = "root";
$password = "";
$dbh = "sabooksonline";*/

$username = "root";
$password = "root";
$dbh = "Sibusisomanqa_update_3";

/*$username = "root";
$password = "";
$dbh = "sabooksonline";*/

$dbh_2 = "Sibusisomanqa_website_plesk";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbh_2);
// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
  }
?>