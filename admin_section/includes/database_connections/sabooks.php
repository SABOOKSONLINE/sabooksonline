<?php
$servername = "localhost";
/*$username = "root";
$password = "";
$dbh = "sabooksonline";*/

$username = "sabooks_library";
$password = "1m0g7mR3$";
$dbh = "Sibusisomanqa_updated";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>