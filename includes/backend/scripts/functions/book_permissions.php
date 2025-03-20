<?php
session_start();

// Assuming you have a database connection established
$user_id = $_SESSION['ADMIN_USERKEY'];
$name = $_SESSION['ADMIN_NAME'];
$user_subscription = $_SESSION['ADMIN_SUBSCRIPTION'];
$retailprice = 0;

// Fetch user's subscription plan and allowed book count
$sql_subscription = "SELECT PRICED FROM subscriptions WHERE PLAN = ?";
$stmt_subscription = $conn->prepare($sql_subscription);
$stmt_subscription->bind_param("s", $user_subscription);
$stmt_subscription->execute();
$result_subscription = $stmt_subscription->get_result();

if ($result_subscription->num_rows > 0) {
    $row_subscription = $result_subscription->fetch_assoc();
    
    $books_allowed = ($price == 0) ? 1000000 : $row_subscription["PRICED"];

    // Count the amount of books the user has uploaded
    $sql_count_books = "SELECT * FROM posts WHERE USERID = ? AND RETAILPRICE != ?";
    $stmt_count_books = $conn->prepare($sql_count_books);
    $stmt_count_books->bind_param("si", $user_id, $retailprice);
    $stmt_count_books->execute();
    $result_count_books = $stmt_count_books->get_result();
    $uploaded_books = $result_count_books->num_rows;

    if ($uploaded_books < $books_allowed) {
        $remainingUploads = $books_allowed - $uploaded_books;

        $type = $_SESSION["ADMIN_TYPE"];
        $extension = pathinfo($_FILES["book_cover"]["name"], PATHINFO_EXTENSION);

        // Check file size before moving the file
        if ($_FILES['book_cover']['size'] > 1048576) {
            echo "<script>Swal.fire({position: 'info',icon: 'info',title: 'File too big, try uploading a file less than 1MB',showConfirmButton: false,timer: 3000});</script>";
            exit;
        }

        $targetPath = "../../cms-data/book-covers/" . $contentid . "." . $extension;
        $targetPath1 = $contentid . "." . $extension;

        // Move file to target directory
        if (move_uploaded_file($sourcePath, $targetPath)) {
            // Insert book data into the database using prepared statements
            $sql = "INSERT INTO posts (TITLE, CATEGORY, WEBSITE, DESCRIPTION, COVER, CONTENTID, USERID, TYPE, DATEPOSTED, STATUS, ISBN, RETAILPRICE, KEYWORDS, PUBLISHER, BOOKSTORES, BOOKSTORE_CODE, LANGUAGES, STOCK, AUTHORS)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, '', ?, ?, ?, ?, ?, ?)";
            
            $stmt_insert = $conn->prepare($sql);
            $stmt_insert->bind_param(
                "sssssssssssssssss",
                $title,
                $category_str,
                $website,
                $desc,
                $targetPath1,
                $contentid,
                $user_id,
                $type,
                $current_time,
                $isbn,
                $price,
                $name,
                $book_stores_str,
                $book_store_code,
                $book_lang_str,
                $stock,
                $authors
            );
            
            if ($stmt_insert->execute()) {
                include '../database_connections/sabooks_plesk.php';  
                
                // Prepare and execute the SELECT query for plesk accounts
                $query = "SELECT * FROM plesk_accounts WHERE USERKEY = ?";
                $stmt_plesk = $mysqli->prepare($query);
                $stmt_plesk->bind_param("s", $user_id);
                $stmt_plesk->execute();
                $result_plesk = $stmt_plesk->get_result();

                if ($result_plesk->num_rows > 0) {
                    include_once 'scripts/select/select_website_data.php';
                    $customerUsername = $customer_username;
                    $customerPassword = $customer_password;
                    include_once 'functions/book_transfer.php';
                }

                echo "<script>Swal.fire({position: 'center',icon: 'success',title: 'Your Book with title <b>".$title."</b> has been uploaded!',showConfirmButton: false,timer: 6000});setInterval(function(){window.location.replace('listings');},3000);</script>";
            } else {
                echo "<script>Swal.fire({position: 'info',icon: 'warning',title: 'Could not insert into Database!',showConfirmButton: false,timer: 3000});</script>";
            }

        } else {
            echo "<script>Swal.fire({position: 'info',icon: 'info',title: 'File upload failed',showConfirmButton: false,timer: 3000});</script>";
        }
        
    } else {
        echo "<script>Swal.fire({position: 'danger',icon: 'warning',title: 'You have reached your book upload limit!',showConfirmButton: false,timer: 3000});</script>";
    }

} else {
    echo "<script>Swal.fire({position: 'danger',icon: 'warning',title: 'Subscription plan not found!',showConfirmButton: false,timer: 3000});</script>";
}

$stmt_subscription->close();
$stmt_count_books->close();
?>
