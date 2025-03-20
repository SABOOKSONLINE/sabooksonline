
    <?php

        $servername = "localhost";
        $username = "oner_hosting";
        $password = "64df6f129";
        $dbh = "oner_hosting";

        //Create connection
        $con = new mysqli($servername, $username, $password, $dbh);

        //Check connection
        if($con->connect_error){
            die("Connection failed: ". $con->connect_error);
        }

    ?>
