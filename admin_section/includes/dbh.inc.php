<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbh = "stationeryavenue";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>


<?php
/*
$servername = "localhost";
$username = "sabooks";
$password = "!Emmanuel@1632";
$dbh = "stationeryavanue";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}*/
?>