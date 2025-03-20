<?php
session_start();
include '../../../database_connections/sabooks.php';

$user_id = $_SESSION['ADMIN_USERKEY'];
$user_subscription = $_SESSION['ADMIN_SUBSCRIPTION'];
$status = 'Active';

$sql_subscription = "SELECT BOOKSTORES FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    $books_allowed = $row_subscription["BOOKSTORES"];

    $sql_count_services = "SELECT * FROM book_stores WHERE USERID = ?";
    $stmt_count_services = $conn->prepare($sql_count_services);
    $stmt_count_services->bind_param("s", $user_id);
    $stmt_count_services->execute();
    $result_count_services = $stmt_count_services->get_result();
    
    $current_services_count = $result_count_services->num_rows;

    if ($current_services_count < $books_allowed) {
        $remainingServices = $books_allowed - $current_services_count;

        $sql_check_service = "SELECT * FROM book_stores WHERE USERID = ?";
        $stmt_check_service = $conn->prepare($sql_check_service);
        $stmt_check_service->bind_param("s", $user_id);
        $stmt_check_service->execute();
        $result_check_service = $stmt_check_service->get_result();

        if ($result_check_service->num_rows > 0) {
            echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You already have this service under your profile.',showConfirmButton: false,timer: 3000});";
        } else {

            $storeName = $_POST['store_name'];
            $storeEmail = $_POST['store_email'];
            $storeNumber = $_POST['store_number'];
            $storeAddress = $_POST['store_address'];
            $storeDesc = $_POST['store_desc'];
            $storeProvince = $_POST['store_province'];
            
            $storeTimes = implode(' - ', $_POST['store_times']);
            $storeLogo = $_FILES['store_logo']['name'];

            //$storeAmnemities = implode(', ', $_POST['store_amnemities']);
            $storeImages = $_FILES['store_images']['name'];

            $storeAmnemities = isset($_POST['store_amnemities']) ? $_POST['store_amnemities'] : array();
            // Convert arrays to comma-separated strings
            $storeAmnemities_str = implode('|', $storeAmnemities);
        
            // Perform validation and sanitization on user inputs here
        
            // Upload logo image
            $logoUploadPath = '../../../../cms-data/profile-images/' . $storeLogo;
            move_uploaded_file($_FILES['store_logo']['tmp_name'], $logoUploadPath);
        
            // Upload store images
            $imagePaths = [];
            foreach ($_FILES['store_images']['tmp_name'] as $index => $tmpName) {
                $imageName = $_FILES['store_images']['name'][$index];
                $imageUploadPath = '../../../../cms-data/user-images/' . $imageName;
                move_uploaded_file($tmpName, $imageUploadPath);
                $imagePaths[] = $imageUploadPath;
            }
        
            // Insert data into the database using prepared statements
            $sql = "INSERT INTO book_stores (STORE_NAME, STORE_EMAIL, STORE_NUMBER, STORE_ADDRESS, STORE_DESC, STORE_TIMES, STORE_LOGO, STORE_AMNEMITIES, STORE_IMAGES, STORE_CREATED, USERID, STATUS, STORE_PROVINCE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(),?,?, ?)";
        
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssss", $storeName, $storeEmail, $storeNumber, $storeAddress, $storeDesc, $storeTimes, $logoUploadPath, $storeAmnemities_str, json_encode($imagePaths), $user_id, $status, $storeProvince);
        

            if ($stmt->execute()) {
                // Insert images into bookstores_images table
                $bookstore_id = $stmt->insert_id; // Get the ID of the inserted book store

                $status = 'active';

                foreach ($imagePaths as $imagePath) {
                    $sql_insert_image = "INSERT INTO bookstores_images (IMAGE, CONTENTID, STOREID, USERID, STATUS) VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert_image = $conn->prepare($sql_insert_image);
                    $stmt_insert_image->bind_param("sssss",$imagePath, $contentid ,$bookstore_id, $userkey, $status);
                    
                    if ($stmt_insert_image->execute()) {
                        // Image inserted successfully
                    } else {
                        // Handle the case where the image couldn't be inserted
                        echo "Error inserting image: " . $stmt_insert_image->error;
                    }

                    $stmt_insert_image->close();
                }

                echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book store page has been created!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('page-dashboard-bookstore');},3000);</script>";
            } else {
                echo "Error inserting data: " . $stmt->error;
                echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'Your Book store could not be added.',showConfirmButton: false,timer: 3000});";
            }

            $stmt->close();
        }

        $stmt_check_service->close();
    } else {
        echo "<script>Swal.fire({position: 'center',icon: 'warning',title: 'You have reached your limit in listing a Book Store.',showConfirmButton: false,timer: 6000});";
    }
} else {
    echo 'bad';
    echo "<script>Swal.fire({position: 'center',icon: 'danger',title: 'Subscription plan not found.',showConfirmButton: false,timer: 3000});";
}

$stmt_subscription->close();
$stmt_count_services->close();
?>
