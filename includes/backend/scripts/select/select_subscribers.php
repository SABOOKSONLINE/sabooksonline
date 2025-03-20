<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*include '../../../database_connections/sabooks.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbh);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
*/
//$plan = 1; // Replace this with the desired ID value

// SQL to select data
$sql = "SELECT * FROM users WHERE ADMIN_USERKEY = '$userkey'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $customer_id = $row["ADMIN_PINTEREST"];
    }
} else {
    echo "0 results";
}

?>
