
    <?php

        $servername = "localhost";
        $username = "sbusiso_manqa_200";
        $password = "6512e5b59";
        $dbh = "sbusiso_manqa_200";

        //Create connection
        $con = new mysqli($servername, $username, $password, $dbh);

        //Check connection
        if($con->connect_error){
            die("Connection failed: ". $con->connect_error);
        }

    ?>
