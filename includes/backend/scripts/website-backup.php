<?php

error_reporting(0);
ini_set('display_errors', 0);

session_start();

include '../../database_connections/sabooks.php';
include '../../database_connections/sabooks_plesk.php';

// Escape and sanitize inputs
$domain_type = $_POST['type'];
$domain_name = $_POST['domain'];
$merchant_id = $_POST['merchant_id'];
$merchant_key = $_POST['merchant_key'];
$number = $_POST['number'];
$email = $_POST['email'];
$address = $_POST['address'];
$desc = $_POST['desc'];
$profile = $_POST['profile'];
$color = 'color';
$books = $_POST['shipping'];
$passphrase = $_POST['passphrase'];

$tld = $_POST['tld'];
$sld = $_POST['domain'];

// Plesk API settings
$host = 'jabu.onerserv.co.za';
$login = 'sabookso';
$password = 'lOpF5s0cB~28&Q';
$apiKey = 'afd79b2d-a246-9008-7646-e4c285e82c1b';

$date = date('l jS \of F Y');

// Customer details
$userid = $_SESSION['ADMIN_ID'];
$userkey = $_SESSION['ADMIN_USERKEY'];
$customerName = $_SESSION['ADMIN_NAME'];
$customerAddress = $_SESSION['ADMIN_GOOGLE'];
$customerUsername = strtolower(str_replace(' ', '_', $_SESSION['ADMIN_NAME'])) . '_' . $userid;
$customerEmail = $_SESSION['ADMIN_EMAIL'];
$customerPassword = substr(uniqid(), 0, 9);
$_SESSION['customerPassword'] = $customerPassword;

$customerLogin = strtolower(str_replace(' ', '_', $_SESSION['ADMIN_NAME']));
$domainName = $sld . '.' . $tld;
$serverIpAddress = '41.76.111.78';

$allowedCharacters = '/[a-zA-Z0-9\s-]/';
$subdomainName = strtolower(str_replace(' ', '-', $_SESSION['ADMIN_NAME']));

$databaseName = $customerUsername;
$dbType = 'mysql';

// Check if account exists
$sqls = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";
$stmt = $mysqli->prepare($sqls);
$stmt->bind_param("s", $userkey);
$stmt->execute();
$results = $stmt->get_result();

if ($results->num_rows > 0) {
    echo "<script>Swal.fire({position: 'center', icon: 'warning', title: 'Website creation limit reached! You can only create one website.', showConfirmButton: false, timer: 10000});</script>";
} else {
    include_once 'website/remove_ftp.php';

    if ($domain_type == 'main') {
        include_once 'website/create_customer.php';
    } else if ($domain_type == 'sub') {
        $client_success = true;
    }

    if ($client_success == false) {
        include_once 'select/select_subscribers.php';
        $customerID = empty($customer_id) ? 32 : $customer_id;
    }

    if ($domain_type == 'main') {
        include_once 'website/create_domain.php';
        $full_domain = $sld . '.' . $tld;
        $_SESSION['domain_key'] = $subscriptionID;
        $domain_id_id = $_SESSION['domain_key'];
        $subscriptionId = $customerID;
    } elseif ($domain_type == 'sub') {
        $domainName = "sabooksonline.co.za";
        include_once 'website/create_subdomain.php';
        $full_domain = $domain_name . '.' . $domainName;
        $subscriptionID = 32;
        $_SESSION['domain_key'] = $subscriptionID;
        $domain_id_id = $_SESSION['domain_key'];
        $subscriptionId = $subscriptionID;
    }

    if ($domain_success == false) {
        echo "<script>Swal.fire({position: 'center', icon: 'warning', title: 'Domain could not be created, An account already exists!', showConfirmButton: false, timer: 6000});</script>";
    } else {
        include_once 'website/create_database.php';

        if ($database_success == false) {
            echo "<script>Swal.fire({position: 'center', icon: 'warning', title: 'Database could not be created, An account already exists! Please contact support.', showConfirmButton: false, timer: 6000});</script>";
        } else {
            $databaseId = $databaseID;
            $dbUsername = $customerUsername;
            $dbUserPassword = $customerPassword;

            include_once 'website/create_user.php';

            if ($user_success == false) {
                echo "<script>Swal.fire({position: 'center', icon: 'warning', title: 'Database User could not be created! Please contact support.', showConfirmButton: false, timer: 6000});</script>";
            } else {
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                $deviceType = $_SERVER['HTTP_USER_AGENT'];

                $logMessage = "Website for $domainName - has been created in Plesk, Password: $customerPassword, Username: $customerEmail";
                $subject = "Newly created website! By SA Books Online.";
                $headers = "From: info@sabooksonline.co.za";

                $logInsertSql = "INSERT INTO logs (user_id, ip_address, device_type, action) VALUES (?, ?, ?, ?)";
                $logStmt = $conn->prepare($logInsertSql);
                $logStmt->bind_param("isss", $user_id, $ipAddress, $deviceType, $logMessage);

                if ($logStmt->execute()) {
                    $status = 'active';

                    $query = "INSERT INTO plesk_accounts (ACCOUNT_NAME, EMAIL, CREATED, PLESK_ID, DOMAIN, IP_ADDRESS, USERNAME, PASSWORD, STATUS, SITE_TITLE, SITE_MERCHANT_ID, SITE_MERCHANT_KEY, SITE_NUMBER, SITE_EMAIL, SITE_ADDRESS, SITE_BOOKS, LOGO, USERKEY, COLOR, DESCRIPTION, DOMAIN_ID, DATABASE_ID, SITE_LONGITUDE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param('ssssssssssssssssssssiis', $customerName, $customerEmail, $date, $subscriptionId, $full_domain, $serverIpAddress, $customerUsername, $customerPassword, $status, $customerName, $merchant_id, $merchant_key, $number, $email, $address, $books, $profile, $userkey, $color, $desc, $domain_id_id, $databaseID, $passphrase);

                    $_SESSION['ADMIN_CUSTOMER_PLESK'] = $subscriptionId;

                    if ($stmt->execute()) {
                        include_once 'website/create_ftp.php';
                        include_once 'ftp_scripts/generate_db.php';
                        include_once 'ftp_scripts/theme_upload.php';
                        include_once 'ftp_scripts/db_upload.php';
                        include_once 'ftp_scripts/key_upload.php';

                        $sql = "UPDATE users SET ADMIN_PINTEREST = ? WHERE ADMIN_USERKEY = ?";
                        $updateStmt = $conn->prepare($sql);
                        $updateStmt->bind_param("ss", $subscriptionId, $userkey);

                        if ($updateStmt->execute()) {
                            include_once 'website/create_tables.php';
                            include_once '../functions/book_transfer.php';
                            include_once 'domains/register_domain.php';

                            echo "<script>Swal.fire({position: 'center', icon: 'success', title: 'Your website with domain $domainName has been created!', showConfirmButton: false, timer: 6000}); setInterval(function(){window.location.replace('websites?status=message');}, 3000);</script>";
                        }
                    } else {
                        echo "Error inserting data: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error inserting log: " . $conn->error;
                }

                $logStmt->close();
            }
        }
    }
}
?>
