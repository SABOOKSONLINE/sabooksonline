<?php

    /*ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/
            
    session_start();

    include '../../database_connections/sabooks.php';
    include '../../database_connections/sabooks_plesk.php';

    $domain_type = mysqli_real_escape_string($mysqli, $_POST['type']);
    $domain_name = mysqli_real_escape_string($mysqli, $_POST['domain']);
    $merchant_id = mysqli_real_escape_string($mysqli, $_POST['merchant_id']);
    $merchant_key = mysqli_real_escape_string($mysqli, $_POST['merchant_key']);
    $number = mysqli_real_escape_string($mysqli, $_POST['number']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $address = mysqli_real_escape_string($mysqli, $_POST['address']);
    $desc = mysqli_real_escape_string($mysqli, $_POST['desc']);
    $profile = mysqli_real_escape_string($mysqli, $_POST['profile']);
    $color = 'color';
    $desc = mysqli_real_escape_string($mysqli, $_POST['desc']);
    $books = 'books';

    $tld = $_POST['tld'];
    $sld = $_POST['domain'];

    // Plesk API settings
    $host = 'jabu.onerserv.co.za';
    $login = 'sabookso';
    $password = 'lOpF5s0cB~28&Q';
    $apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';


    $date = $current_time = date('l jS \of F Y');

    // Customer details
    $userkey = $_SESSION['ADMIN_USERKEY'];
    $customerName = $_SESSION['ADMIN_NAME']; // Replace with the customer's name
    $customerAddress = $_SESSION['ADMIN_GOOGLE']; // Replace with the customer's name
    $customerUsername = strtolower(str_replace(' ','_',$_SESSION['ADMIN_NAME'])); // Replace with the customer's name
    $customerEmail = $_SESSION['ADMIN_EMAIL']; // Replace with the customer's email
    $customerPassword = substr(uniqid(),0,9); // Replace with the customer's email

    // Customer details
    $customerLogin = strtolower(str_replace(' ','_',$_SESSION['ADMIN_NAME'])); // Replace with the customer's username

    // Domain details
   // $domainName = strtolower(str_replace(' ','-',$_SESSION['ADMIN_NAME'])).'.co.za';// Replace with the desired domain name
    $domainName = $sld.'.'.$tld;// Replace with the desired domain name
    $serverIpAddress = '41.76.111.78'; // Replace with the server's IP address

    // Specify the allowed characters using a regular expression
    $allowedCharacters = '/[a-zA-Z0-9\s-]/';

    // Subdomain details
    $subdomainName = strtolower(str_replace(' ','-',$_SESSION['ADMIN_NAME'])); // Replace with the desired subdomain name
    //$domainName = 'sabooksonline.africa'; // Replace with the main domain where the subdomain will be created

    // Remove all characters except the allowed ones
    //$subdomainName = preg_replace($allowedCharacters, '', $subdomainName);

    // Database details
    $databaseName = $customerUsername; // Replace with the desired database name
    $dbType = 'mysql'; // Database type (e.g., mysql)

    //Create the user first account on plesk


    if($domain_type == 'main'){

        include_once 'website/create_customer.php';

    } else if($domain_type == 'sub'){
        $client_success = true;
    }

    if($client_success == false){
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Account could not be created, An account already exists!',showConfirmButton: false,timer: 6000});</script>";
    } else {

        //insert the data into database

        //insert the data into database

        if($domain_type == 'main'){

            //Domain creation process
            include_once 'website/create_domain.php';

            $full_domain = $sld.'.'.$tld;

            $_SESSION['domain_key'] = $subscriptionID;

            $domain_id_id = $_SESSION['domain_key'];

    
        } elseif($domain_type == 'sub'){

            $domainName = "sabooksonline.co.za";
    
            //Sub-Domain creation process
            include_once 'website/create_subdomain.php';

            $full_domain = $domain_name.'.'.$domainName;
    
        }

        if($domain_success == false){
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Domain could not be created, An account already exists!',showConfirmButton: false,timer: 6000});</script>";
        } else {

            //insert the data into database

            //insert the data into database

            // Database details
            $subscriptionId = $customerID; // Replace with the subscription ID

            //CREATE A DATABASE UNDER THE CUSTOMERS ACCOUNT
            include_once 'website/create_database.php';

            if($database_success == false){
                echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Database could not be created, An account already exists! Please contact support.',showConfirmButton: false,timer: 6000});</script>";
            } else {
    
                // Database details
                $databaseId = $databaseID; // Replace with the ID of the existing database
                $dbUsername = $customerUsername; // Replace with the desired database username
                $dbUserPassword = $customerPassword; // Replace with the desired database user password
    
                //CREATE A DATABASE UNDER THE CUSTOMERS ACCOUNT
                include_once 'website/create_user.php';

                if($user_success == false){
                    echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Database User could not be created! Please contact support.',showConfirmButton: false,timer: 6000});</script>";
                } else {
        
                    //insert the data into database
        
                    $ipAddress = $_SERVER['REMOTE_ADDR'];
                    $deviceType = $_SERVER['HTTP_USER_AGENT'];

                    $user_id = $_SESSION['ADMIN_USERKEY'];

                    $logMessage = "Website for $domainName - has been created in Plesk, Password: $customerPassword, Username: $customerEmail";
                    $subject = "Newly created website! By SA Books Online.";
                    $headers = "From: info@sabooksonline.co.za";
                    $logInsertSql = "INSERT INTO logs (user_id, ip_address, device_type, action) VALUES (?, ?, ?, ?)";
                    $logStmt = $conn->prepare($logInsertSql);
                    $logStmt->bind_param("isss", $user_id, $ipAddress, $deviceType, $logMessage);

                    if ($logStmt->execute()) {
                        
                        $status = 'active';

                        // Prepare the INSERT statement
                        $query = "INSERT INTO plesk_accounts (ACCOUNT_NAME, EMAIL, CREATED, PLESK_ID, DOMAIN, IP_ADDRESS, USERNAME, PASSWORD, STATUS, SITE_TITLE, SITE_MERCHANT_ID, SITE_MERCHANT_KEY, SITE_NUMBER, SITE_EMAIL, SITE_ADDRESS, SITE_BOOKS, LOGO, USERKEY, COLOR, DESCRIPTION, DOMAIN_ID) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                        // Prepare and bind the parameters
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param('ssssssssssssssssssssi', $customerName, $customerEmail, $date, $subscriptionId, $full_domain, $serverIpAddress, $customerUsername, $customerPassword, $status, $customerName, $merchant_id, $merchant_key, $number, $email, $address, $books, $profile, $userkey, $color, $desc, $domain_id_id);

                        $_SESSION['ADMIN_CUSTOMER_PLESK'] = $subscriptionId;

                        // Execute the statement
                        if ($stmt->execute()) {
                            //echo "Data inserted successfully!";
                            //CREATE THE FTP CREDENTIALS
                            include_once 'website/create_ftp.php';

                                //CREATE THE DATABASE SCRIPT
                                include_once 'ftp_scripts/generate_db.php';

                                
                                    //UPLOAD THE DEFAULT THEME
                                    include_once 'ftp_scripts/theme_upload.php';

                                    include_once 'ftp_scripts/db_upload.php';

                                         // SQL query to update the table
                                        $sql = "UPDATE users SET ADMIN_PINTEREST = '$subscriptionId' WHERE ADMIN_USERKEY = '$userkey'";

                                        // Execute the query
                                        if ($conn->query($sql) === TRUE) {
                                            //CREATE A DATABASE UNDER THE CUSTOMERS ACCOUNT
                                            include_once 'website/create_tables.php';

                                            //REGISTER DOMAIN SCRIPT ONLY FOR PRODUCTION
                                            include_once 'domains/register_domain.php';

                                            mail($customerEmail, $subject, $logMessage, $headers);

                                            echo "<script>Swal.fire({position: 'top-end',icon: 'success',title: 'Your website with domain ".$domainName." has been created!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('websites');},3000);</script>";

                                        } else {
                                            //echo "Error updating record: " . $conn->error;
                                        }

                        } else {
                            echo "Error inserting data: " . $stmt->error;
                        }

                        // Close the statement and the connection
                        $stmt->close();
                        $mysqli->close();

                    } else {
                        echo "Error inserting log: " . $conn->error;
                    }

                    $logStmt->close();
        
                }
    
    
            }


        }
    

        
        
    }

?>