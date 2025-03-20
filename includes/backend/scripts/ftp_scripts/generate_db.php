<?php

$userkey = $_SESSION['ADMIN_USERKEY'];

// SQL to select data
$sql = "SELECT * FROM plesk_accounts WHERE USERKEY = '$userkey'";

$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $username = $row["USERNAME"];
        $password = $row["PASSWORD"];
    }
} else {
    echo "0 results";
}


$fileName = 'ftp_scripts/db.php'; // Name of the new file

// Content to write to the file
$fileContent = '
    <?php

        $servername = "localhost";
        $username = "'.$username.'";
        $password = "'.$password.'";
        $dbh = "'.$username.'";

        //Create connection
        $con = new mysqli($servername, $username, $password, $dbh);

        //Check connection
        if($con->connect_error){
            die("Connection failed: ". $con->connect_error);
        }

    ?>
';



// Open the file for writing (create if it doesn't exist)
$fileHandle = fopen($fileName, 'w');

if ($fileHandle === false) {
   // echo "Error opening the file.";
} else {
    // Write the content to the file
    if (fwrite($fileHandle, $fileContent) === false) {
      //  echo "Error writing to the file.";

    } else {
      //  echo "File created and content written successfully.";

      $fileHandler = fopen('ftp_scripts/api_key.txt', 'w');

      fwrite($fileHandler, $userkey);

      fclose($fileHandler);
    }
    
    // Close the file
    fclose($fileHandle);
}


$mysqli->close();

?>