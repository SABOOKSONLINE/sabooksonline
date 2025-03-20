
    <?php

        $servername = "localhost";
        $username = "sifiso_zondo_899";
        $password = "674835ebb";
        $dbh = "sifiso_zondo_899";

        //Create connection
        $con = new mysqli($servername, $username, $password, $dbh);

        //Check connection
        if($con->connect_error){
            die("Connection failed: ". $con->connect_error);
        }

    ?>
